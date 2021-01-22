<?php

namespace yt\import;

use WP_REST_Request;
use MatthiasWeb\RealMediaLibrary\rest\Service;
use MatthiasWeb\RealMediaLibrary\rest\Attachment;
use MatthiasWeb\RealMediaLibrary\rest\Folder;

class real_media_library
{

    public $att_id;

    public $taxonomy;

    public $rml_menu_id;
    public $rml_imported_menu_id;
    public $rml_taxonomy_menu_id;
    public $rml_term_menu_id;

    public function set_att_id($att_id)
    {
        $this->att_id = $att_id;
    }

    public function set_taxonomy($taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }


    public function run()
    {
        $this->get_RML_imported_folder_id();
        $this->get_RML_taxonomy_folder_id();
        $this->get_RML_term_folder_id();
        $this->move_image_into_RML_folder();
    }


    public function get_RML_imported_folder_id()
    {
        $this->rml_imported_menu_id = $this->does_folder_exist('Imported');

        if (!$this->rml_imported_menu_id){
            $this->rml_imported_menu_id = $this->create_RML_folder();
        }

        $this->rml_menu_id = $this->rml_imported_menu_id;
        
        return;
    }


    private function get_RML_taxonomy_folder_id()
    {
        if (empty($this->taxonomy)){ return; }
        if (empty($this->taxonomy['taxonomy'])){ return; }
        if (empty($this->rml_imported_menu_id)){ return; }

        $taxonomy = \sanitize_title($this->taxonomy['taxonomy']);

        $this->rml_taxonomy_menu_id = $this->does_folder_exist($taxonomy);

        if (!$this->rml_taxonomy_menu_id){
            $this->rml_taxonomy_menu_id = $this->create_RML_folder($taxonomy, $this->rml_imported_menu_id);
        }

        $this->rml_menu_id = $this->rml_taxonomy_menu_id;
        
        return;
    }


    private function get_RML_term_folder_id()
    {
        if (empty($this->taxonomy)){ return; }
        if (empty($this->taxonomy['term'])){ return; }
        if (empty($this->rml_taxonomy_menu_id)){ return; }

        $term = \sanitize_title($this->taxonomy['term']);

        $this->rml_term_menu_id = $this->does_folder_exist($term);

        if (!$this->rml_term_menu_id){
            $this->rml_term_menu_id = $this->create_RML_folder($term, $this->rml_taxonomy_menu_id);
        }

        $this->rml_menu_id = $this->rml_term_menu_id;
        
        return;
    }





    private function does_folder_exist($folder)
    {
        $rml_service = new Service;

        $request = new WP_REST_Request('GET', '/wp/v2/posts');

        $tree =  $rml_service->routeTree($request);

        $slugs = $tree->get_data();

        $id = $this->find_folder_in_tree($slugs['tree'], $folder);

        if ($id){ return $id; }

        // foreach ($slugs['slugs']['names'] as $key => $name) {

        //     if (stripos($name, $folder)) {
        //         return $slugs['slugs']['slugs'][$key];
        //     }
        // }

        return false;
    }


    /** Recursive function */
    private function find_folder_in_tree($tree, $slug)
    {
        $id = false;
        foreach($tree as $branch)
        {
            if ($branch['name'] == $slug){ return $branch['id']; }

            if (!empty($branch['children'])){ 
                $id = $this->find_folder_in_tree($branch['children'], $slug);
            }

            continue;
        }

        return $id;
    }


    private function create_RML_folder($name = 'Imported', $parent = -1)
    {
        $rml_folder = new Folder;

        $create_folder_request = new WP_REST_Request('POST', '/wp/v2/posts');
        $create_folder_request->set_param('name', $name);
        $create_folder_request->set_param('parent', $parent); // parent -1 is root level.
        $create_folder_request->set_param('type', 0);

        $folder = $rml_folder->createItem($create_folder_request);

        return $folder->data['id'];
    }


    public function move_image_into_RML_folder()
    {
        if (!$this->rml_menu_id || $this->rml_menu_id == '' || $this->rml_menu_id == null) {
            return;
        }
        
        $rml_attachment = new Attachment;

        $request = new WP_REST_Request('PUT', '/wp/v2/posts');
    
        // PULSE DIRECTORY
        $request->set_query_params([
            'ids' => [$this->att_id],
            'to' => $this->rml_menu_id
        ]);
    
        try {
            $rml_attachment->routeBulkMove($request);
        } catch (Exception $e) {
            (new \yt\e)->line('Exception trying to move into RML Folder. '. $e, 2);
        }
    
        return;
    }

}
