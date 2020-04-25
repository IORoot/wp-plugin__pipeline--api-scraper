<?php

namespace yt;

use \yt\filter;

class filter_layer
{

    public $filter_layer;

    public $collection;


    public function __construct()
    {
        return $this;
    }

    public function set_collection($collection)
    {
        $this->collection = $collection;
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
        return $this->instantiate_filter();
    }


    public function check_filter_exists()
    {
        $filter_name = '\\yt\\filter\\'.$this->filter_layer['yt_filter'];

        if (!class_exists($filter_name)) {
            throw new \Exception('This filter - '.$filter_name.' - does not exist, cannot instantiate class of this name in src/filter/filter_layer->run()');
        }

        return;
    }



    public function instantiate_filter()
    {

        $filter_name = '\\yt\\filter\\'.$this->filter_layer['yt_filter'];
        $parameters = $this->filter_layer['yt_filter_parameters'];

        $filter_object = new $filter_name;
        $filter_object->config($parameters);
        $filter_object->in($this->collection);

        $out = $filter_object->out();

        return $out;

    }
}
