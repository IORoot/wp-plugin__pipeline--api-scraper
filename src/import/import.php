<?php

namespace yt;

use \yt\import\category;
use \yt\import\post;

class import
{
    public $taxonomy;

    public $post;



    public function __construct()
    {
        $this->taxonomy = new category;

        $this->post = new post;

        return $this;
    }


    public function add_term($taxonomy, $term, $description = '')
    {
        if ($taxonomy == null || $taxonomy == '') {
            throw new \Exception('No Taxonomy has been specified. Cannot import->add_term().');
        }
        if ($term == null || $term == '') {
            throw new \Exception('No Term has been specified. Cannot import->add_term().');
        }

        $this->taxonomy->set_type($taxonomy);
        $this->taxonomy->set_term($term);
        $this->taxonomy->set_desc($description);   // optional
        $this->taxonomy->add_term();

        return $this;
    }


    public function add_posts($post_type, $collection)
    {
        if ($post_type == null || $post_type == '') {
            throw new \Exception('No post_type has been set. Cannot import->add_posts().');
        }
        if ($collection == null || $collection == '') {
            throw new \Exception('No search_results has been specified. Cannot import->add_posts().');
        }

        foreach ($collection as $item) {
            $this->add_post($post_type, $item);
        }

        return $this;
    }



    public function add_post($post_type, $yt_result)
    {    
        $this->post->set_postargs($yt_result);
        $this->post->add_posttype($post_type);

        $this->post->add();

        return $this;
    }




}
