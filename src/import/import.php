<?php

namespace yt;

use \yt\import\taxonomy;
use \yt\import\post;
use \yt\import\image;
use \yt\import\meta;

class import
{

    public $taxonomy;

    public $post;

    public $image;

    public $meta;

    public $returned_ids;




    public function __construct()
    {
        $this->taxonomy = new taxonomy;

        $this->post = new post;

        $this->image = new image;

        $this->meta = new meta;

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
            $item['post']['post_type'] = $post_type;
            $this->add_post($item);
        }

        return $this;
    }


    
    public function add_post($item)
    {
        
        foreach ($item as $target_object => $post_args)
        {
            $this->$target_object->set_args($post_args);
            $this->$target_object->add();
            $this->returned_ids[$target_object] = $this->$target_object->result();
        }

        //$this->set_post_taxonomy($postID);

        return $this;
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


    public function set_post_taxonomy($postID)
    {
        if (isset($this->taxonomy->taxonomy_term) && isset($this->taxonomy->taxonomy_type)) {
            $cat = $this->taxonomy->taxonomy_type;
            $term = $this->taxonomy->taxonomy_term;
            $result = wp_set_object_terms($postID, $term, $cat);
        }

        return;
    }
}
