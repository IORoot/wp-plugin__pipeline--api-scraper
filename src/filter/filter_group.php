<?php

namespace yt;

use \yt\filter_layer;

class filter_group
{

    public $filter_group;

    public $filter_layer;

    public $item_collection;

    public function __construct()
    {
        return $this;
    }

    public function set_filter_group($filter_group)
    {
        $this->filter_group = $filter_group;
        return;
    }


    public function set_item_collection($item_collection)
    {
        $this->item_collection = $item_collection;
        return;
    }


    public function run()
    {
        
        foreach ($this->filter_group['yt_filter_layers'] as $this->filter_layer)
        {
            $this->filter_item();
        }

        return $this;
    }


    public function filter_item()
    {
        $filter_item = new filter_layer;
        $filter_item->set_item_collection($this->item_collection);
        $filter_item->set_filter($this->filter_layer);
        $filter_item->run();

    }
}
