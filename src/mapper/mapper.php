<?php

namespace yt;

class mapper
{
    public $mappings;

    public $source;

    public $current_mapping;
    public $source_mapping;

    public $mapped_result;

    public function __construct()
    {
        return $this;
    }


    public function set_mappings($mappings_array)
    {
        $this->mappings = $mappings_array;
        return $this;
    }

    public function set_source($source)
    {
        $this->source = $source;
        return $this;
    }

    public function run()
    {
        $this->process_mappings();
        return $this->mapped_result;
    }


    public function process_mappings()
    {
        foreach($this->mappings as $this->current_mapping)
        {
            $this->process_mapping();
        }
        return;
    }

    public function process_mapping()
    {
        
        $this->mapped_result[$this->destination()] = $this->source_value();

        return $this->mapped_result;

    }



    /**
     * This is a tricky one to figure out.
     * Essentially, the loop will iterate down the tree
     * setting the $value to a narrower part of the object on
     * each loop, until it gets to it's destination.
     * It will return the value of that final level.
     */
    public function source_value()
    {
        
        $this->explode_source();

        $value = $this->source;
        foreach($this->source_mapping as $object_level)
        {
            $value = $value->$object_level;
        } 
        return $value;
    }


    /**
     * Explode the source out into an array
     * so we can traverse it within the source_value()
     * method.
     */
    public function explode_source()
    {
        $this->source_mapping = explode('->', $this->current_mapping['yt_mapper_source']);
        return;
    }
    

    public function destination()
    {
        return $this->current_mapping['yt_mapper_destination'];
    }


    public function filter_source_field()
    {
        return;
    }
}
