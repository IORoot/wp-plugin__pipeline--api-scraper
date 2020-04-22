<?php

namespace yt;

use \yt\category;
use \yt\post;

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
            throw new Exception('No Taxonomy has been specified. Cannot import->add_term().');
        }
        if ($searches_array == null || $searches_array == '') {
            throw new Exception('No Term has been specified. Cannot import->add_term().');
        }

        // loop over each search, adding each search_name to the taxonomy.
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


    

    public function add_posts($post_type, $items)
    {

        return $this;
    }


    public function add_post()
    {
        return $this;
    }


}
