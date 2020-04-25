<?php

namespace yt;

use yt\filter_group;

class mapper_item
{
    public $all_filters;
    
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

    public function set_filters($filters)
    {
        $this->all_filters = $filters;
        return $this;
    }

    public function run()
    {
        $this->process_mappings();
        return $this->mapped_result;
    }


    public function process_mappings()
    {
        foreach ($this->mappings as $this->current_mapping) {
            $this->process_mapping();
        }
        return;
    }

    public function process_mapping()
    {
        $this->mapped_result[$this->destination()] = $this->source_value();
        return $this->mapped_result;
    }


    public function source_value()
    {
        $this->explode_source();

        $value = $this->source;
        foreach ($this->source_mapping as $object_level) {
            $value = $value->$object_level;
        }

        return $this->filter_value($value);
    }


    public function explode_source()
    {
        $this->source_mapping = explode('->', $this->current_mapping['yt_mapper_source']);
        return;
    }
    

    public function destination()
    {
        return $this->current_mapping['yt_mapper_destination'];
    }


    public function filter_value($value)
    {

        // // filter to run
        // $filter_group = $this->current_mapping['yt_mapper_filter'];

        // foreach ($this->all_filters as $filter){
        //     if ($filter['yt_filter_id'] == $filter_group)
        //     {
        //         $filter_layers = $filter['yt_filter_layers'];
        //     }
        // }



        

        // $filter_group = new filter_group;
        // $filter_group->set_filter_group();
        // $filter_group->set_item_collection();
        // $filter_group->run();

        return $value;
    }
}
