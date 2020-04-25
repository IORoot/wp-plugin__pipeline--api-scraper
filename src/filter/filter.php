<?php

namespace yt;

use \yt\filter_group;

class filter
{

    public $filter_group;

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

        $filter_item = new filter_group;
        
        $filter_item->set_collection($this->item_collection);
        $filter_item->set_filter_group($this->filter_group);

        return $filter_item->run();

        return $this->item_collection;
    }


}
