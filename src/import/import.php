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
            $this->add_post($post_type, $item);
        }

        return $this;
    }



    public function add_post($post_type, $yt_result)
    {
        $this->post->set_postargs($yt_result);
        $this->post->add_posttype($post_type);

        $postID = $this->post->add();

        $this->set_post_taxonomy($postID);

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
