<?php

namespace yt;

use \yt\filter;

class filter_layer
{
    public $filter_object;

    public $filter_layer;

    public $item_collection;


    public function __construct()
    {
        return $this;
    }

    public function set_item_collection($item_collection)
    {
        $this->item_collection = $item_collection;
        return;
    }

    public function set_filter($filter_layer)
    {
        $this->filter_layer = $filter_layer;
        return;
    }


    public function run()
    {
        $this->check_filter_exists();
        $result = $this->filter_instance();

        return;
    }


    public function check_filter_exists()
    {
        $filter_name = '\\yt\\filter\\'.$this->filter_layer['yt_filter'];

        if (!class_exists($filter_name)) {
            throw new \Exception('This filter - '.$filter_name.' - does not exist, cannot instantiate class of this name in src/filter/filter_layer->run()');
        }

        return;
    }


    public function filter_instance()
    {
        $filter_name = '\\yt\\filter\\'.$this->filter_layer['yt_filter'];
        $filter_parameters = $this->filter_layer['yt_filter_parameters'];
        
        return $this->instantiate_filter($filter_name, $filter_parameters);
    }

    
    public function instantiate_filter($filter_name, $parameters)
    {
        $this->filter_object = new $filter_name;
        $this->filter_object->config($parameters);
        $this->filter_object->in($this->item_collection);

        return $this->filter_object->out();
    }
}
