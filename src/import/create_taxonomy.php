<?php

namespace yt\import;

class taxonomy
{
    public $taxonomy_type;

    public $taxonomy_term;

    public $taxonomy_slug;

    public $taxonomy_description = '';

    public $parent_id = 0;

    public $result;



    public function __construct()
    {
        return $this;
    }


    public function set_type($taxonomy)
    {
        if ($taxonomy == null || $taxonomy == '') {
            throw new \Exception('No Taxonomy has been specified. Cannot set.');
        }
        $this->taxonomy_type = $taxonomy;
        return $this;
    }


    public function set_term($term)
    {
        if ($term == null || $term == '') {
            throw new \Exception('No Term has been specified. Cannot set.');
        }
        $this->taxonomy_term = $term;
        return $this;
    }


    public function set_desc($description)
    {
        $this->taxonomy_description = $description;
        return $this;
    }


    public function set_parent_id($parent_id)
    {
        $this->parent_id = $parent_id;
    }


    /**
     * create category
     *
     * @return void
     */
    public function add_term()
    {
        if ($this->taxonomy_type == null || $this->taxonomy_type == '') {
            (new \yt\e)->line('No Taxonomy has been specified. Cannot create taxonomy. : ' . $this->taxonomy_type, 2 );
        }
        if ($this->taxonomy_term == null || $this->taxonomy_term == '') {
            (new \yt\e)->line('No Term has been specified. Cannot create term. : '.$this->taxonomy_term .' in '. $this->taxonomy_type, 2 );
        }
        if (term_exists($this->taxonomy_term, $this->taxonomy_type)){
            (new \yt\e)->line('Term already exists : '.$this->taxonomy_term .' in '. $this->taxonomy_type, 2 );
            return $this;
        }

        $this->taxonomy_slug = strtolower(str_replace(' ', '-', $this->taxonomy_term));

        $this->insert();
        
        return $this;
    }




    private function insert()
    {
        (new \yt\e)->line('Insert Term : '.$this->taxonomy_term . ' into '. $this->taxonomy_type, 2 );

        if (empty($this->parent_id)){ $this->parent_id = 0; }

        try {
            $this->result = wp_insert_term(
                $this->taxonomy_term,
                $this->taxonomy_type,
                array(
                    'description' => $this->taxonomy_description,
                    'slug' => $this->taxonomy_slug,
                    'parent' => (int) $this->parent_id,
                )
            );


        } catch (\Exception $e) {
            (new \yt\e)->line('FAILED Insert Term : '.$this->taxonomy_term . ' into '. $this->taxonomy_type, 2 );
            return false;
        }

        return $this;
    }




}
