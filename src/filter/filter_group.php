<?php

namespace yt;

use \yt\filter_layer;

class filter_group
{

    public $filter_group;

    public $collection;

    public function __construct()
    {
        return $this;
    }   

    public function set_filter_group($filter_group)
    {
        $this->filter_group = $filter_group;

        return;
    }


    public function set_collection($collection)
    {
        $this->collection = $collection;
        return;
    }


    public function run()
    {

        // iterate over each filter in the filter group
        foreach ($this->filter_group['yt_filter_layers'] as $filter_layer)
        {
            $this->filter_item($filter_layer);
        }

        (new e)->line('- Filtered Rows : ' . count($this->collection) ,1);

        return $this->collection;
    }



    public function filter_item($filter_layer)
    {

        $filter_item = new filter_layer;
        $filter_item->set_collection($this->collection);
        $filter_item->set_filter($filter_layer);

        $this->collection = $filter_item->run();

        return;

    }
}
