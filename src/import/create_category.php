<?php

namespace yt;

class category
{
    public $taxonomy_type;

    public $taxonomy_term;

    public $taxonomy_slug;

    public $taxonomy_description = '';

    public $result;



    public function __construct()
    {
        return $this;
    }


    public function set_type($taxonomy)
    {
        if ($taxonomy == null || $taxonomy == '') {
            throw new Exception('No Taxonomy has been specified. Cannot set.');
        }
        $this->taxonomy_type = $taxonomy;
        return $this;
    }


    public function set_term($term)
    {
        if ($term == null || $term == '') {
            throw new Exception('No Term has been specified. Cannot set.');
        }
        $this->taxonomy_term = $term;
        return $this;
    }


    public function set_desc($description)
    {
        $this->taxonomy_description = $description;
        return $this;
    }


    /**
     * create category
     *
     * @return void
     */
    public function add_term()
    {
        if ($this->taxonomy_type == null || $this->taxonomy_type == '') {
            throw new Exception('No Taxonomy has been specified. Cannot create taxonomy.');
        }
        if ($this->taxonomy_term == null || $this->taxonomy_term == '') {
            throw new Exception('No Term has been specified. Cannot create taxonomy.');
        }
        if (term_exists($this->taxonomy_term, $this->taxonomy_type)){
            return $this;
        }

        $this->taxonomy_slug = strtolower(str_replace(' ', '-', $this->taxonomy_term));

        $this->insert();
        
        return $this;
    }




    private function insert()
    {
        try {
            $this->result = wp_insert_term(
                $this->taxonomy_term,
                $this->taxonomy_type,
                array(
                    'description' => $this->taxonomy_description,
                    'slug' => $this->taxonomy_slug
                )
            );
        } catch (Exception $e) {
            echo 'Insert term : '.$this->taxonomy_term.' into taxonomy '.$this->taxonomy_type.' Failed: ',  $e->getMessage(), "\n";
        }

        return $this;
    }




}
