<?php

namespace yt\filter;

use yt\interfaces\filterInterface;

class yt_array_front implements filterInterface
{
    public $description = "Returns the first X number of entries of the Youtube search array. Specifically the 'items' collection of results. ";

    public $parameters = "(int) 5.";

    public $input;

    public $item_collection;

    public $config;

    public $value_reference;


    public function config($config)
    {
        $this->config = $config;
        return;
    }

    public function in($input)
    {
        $this->input = $input;
        return;
    }

    public function out()
    {
        $this->config_string_to_array();
        $this->find_item_collection_within_input_array();
        $this->slice_collection();
        $this->replace_input_with_sliced_result();

        return $this->input;
    }


    public function config_string_to_array()
    {
        $config = preg_replace("/\r|\n/", "", $this->config);
        $this->config = eval("return $config;");
        return;
    }




    public function find_item_collection_within_input_array()
    {
        if (!is_array($this->config['location'])) {
            $location = $this->config['location'];
            $this->item_collection = $this->input->$location;
            return;
        }

        $value = $this->input;
        foreach ($this->config['location'] as $level) {
            $value = $value->$level;
        }

        $this->item_collection = $value;

        return;
    }


    public function slice_collection()
    {
        $this->item_collection = array_slice($this->item_collection, 0, (int)$this->config['length']);
        return;
    }

    /**
     * This has a problem TODO.
     * @TODO - Deal with multiple levels here.
     * 
     * at the moment $this->input->$location can only work if $location is a single string.
     * if location is 'items->snippet->items->something', this will break. 
     * It doesn't like arrow pointers in the string, so $location is not valid.
     * need to explode the string by -> character and then walk through the array
     * until you can get to the location it needs to be. 
     * THEN you can replace with $this->item_collection;
     * 
     * However we can ONLY use this filter on a youtube search if need be.
     */
    public function replace_input_with_sliced_result()
    {
        if (!is_array($this->config['location'])) {
            $location = $this->config['location'];
            $this->input->$location = $this->item_collection;
        } else {
            throw new \Exception('PROBLEM : $this->config[\'location\'] is an array and has multiple levels, we can only deal with one level at the moment.');
        }

        return;
    }
}
