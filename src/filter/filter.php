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
        if(!$this->check_filter()){return false;}
        
        $filter_item = new filter_group;
        
        $filter_item->set_collection($this->item_collection);
        $filter_item->set_filter_group($this->filter_group);

        return $filter_item->run();
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐ 
    // │                                                                         │░
    // │                                                                         │░
    // │                                 CHECKS                                  │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    public function check_filter(){

        if ($this->filter_group == '') {
            (new e)->line('- filter->filter_group is blank. Please set.',1);
            return false;
        }

        if ($this->item_collection == '') {
            (new e)->line('- filter->filter_group is blank. Please set.',1);
            return false;
        }

        return true;

    }

}
