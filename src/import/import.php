<?php

namespace yt;

use \yt\import\exists;
use \yt\import\taxonomy;
use \yt\import\post;
use \yt\import\image;
use \yt\import\meta;
use \yt\import\attach;

class import
{

    public $taxonomy;

    public $post;

    public $image;

    public $meta;

    public $returned_ids;

    public $attach;


    public function __construct()
    {
        $this->taxonomy = new taxonomy;

        $this->post = new post;

        $this->image = new image;

        $this->meta = new meta;

        $this->attach = new attach;

        return $this;
    }



    

    public function add_posts($post_type, $collection)
    {

        if ($post_type == null || $post_type == '') {
            (new e)->line('- No post_type has been set. Cannot import->add_posts().', 1);
            return false;
        }
        if ($collection == null || $collection == '') {
            (new e)->line('- No search_results has been specified. Cannot import->add_posts().', 1);
            return false;
        }

        foreach ($collection as $item) {

            if ($this->does_post_exist($item)){ continue; }

            $item['post']['post_type'] = $post_type;
            $item = $this->append_taxonomy_to_image_content($item);

            $this->add_post_and_combine($item);
        }

        return $this;
    }


    
    public function add_post_and_combine($item)
    {
        
        foreach ($item as $target_object => $post_args)
        {
            $this->$target_object->set_args($post_args);
            $this->$target_object->add();
            $this->returned_ids[$target_object] = $this->$target_object->result();
        }

        $this->combine();

        return $this;
    }


    public function combine()
    {
        $this->attach->image_to_post($this->returned_ids['image'], $this->returned_ids['post']);
        $this->attach->meta_to_post($this->meta->args, $this->returned_ids['post']);
        $this->attach->tax_to_post($this->taxonomy->taxonomy_type, $this->taxonomy->taxonomy_term, $this->returned_ids['post']);

        return;
    }




    public function add_term($taxonomy, $term, $description = '')
    {
        if ($taxonomy == null || $taxonomy == '') {
            (new e)->line('- No Taxonomy has been specified. Cannot import->add_term().', 1);
            return false;
        }
        if ($term == null || $term == '') {
            (new e)->line('- No Term has been specified. Cannot import->add_term().', 1);
            return false;
        }

        $this->taxonomy->set_type($taxonomy);
        $this->taxonomy->set_term($term);
        $this->taxonomy->set_desc($description);   // optional
        $this->taxonomy->add_term(); 

        return $this;
    }



    public function does_post_exist($item)
    {
        $does_it_exist = false;

        $exist = new exists;
        $does_it_exist = $exist->post_by_title($item['post']['post_title']);



        return $does_it_exist;
    }


    




    public function append_taxonomy_to_image_content($item)
    {
        if (isset($item['image']['post_content']))
        {
            $item['image']['post_content'] = $item['image']['post_content'] . ' ' . $this->taxonomy->taxonomy_term;
            return $item;
        } 

        $item['image']['post_content'] = $this->taxonomy->taxonomy_term;

        return $item;

    }

}
