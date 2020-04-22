<?php

namespace yt;

use \yt\category;
use \yt\post;

class import
{

    public $taxonomy;

    public function __construct()
    {
        $this->taxonomy = new category;

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


    

    
}
