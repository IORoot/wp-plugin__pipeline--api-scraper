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


    public function add_terms($taxonomy, $searches_array)
    {
        if ($taxonomy == null || $taxonomy == '') {
            throw new Exception('No Taxonomy has been specified. Cannot import->add_terms().');
        }
        if ($searches_array == null || $searches_array == '') {
            throw new Exception('No Term has been specified. Cannot import->add_terms().');
        }

        foreach ($searches_array as $search_row) {
            $term = $search_row['yt_search_name'];
            $desc = $search_row['yt_search_description']; // optional
            $this->add_term($taxonomy, $term, $desc);
        }

        return $this;
    }



    public function add_term($taxonomy, $term, $description = '')
    {
        if ($taxonomy == null || $taxonomy == '') {
            throw new Exception('No Taxonomy has been specified. Cannot import->add_term().');
        }
        if ($term == null || $term == '') {
            throw new Exception('No Term has been specified. Cannot import->add_term().');
        }

        $this->taxonomy->set_type($taxonomy);
        $this->taxonomy->set_term($term);
        $this->taxonomy->set_desc($description);   // optional
        $this->taxonomy->add_term();

        return $this;
    }


    public function add_posts($post_type, $yt_results)
    {
        if ($post_type == null || $post_type == '') {
            throw new Exception('No post_type has been set. Cannot import->add_posts().');
        }
        if ($yt_results == null || $yt_results == '') {
            throw new Exception('No search_results has been specified. Cannot import->add_posts().');
        }

        foreach ($yt_results->items as $yt_item) {
            $this->add_post($post_type, $yt_item);
        }

        return $this;
    }



    public function add_post($post_type, $yt_result)
    {
        $this->post->set_posttype($post_type);
        $this->post->set_postdata($yt_result);

        return $this;
    }




}
