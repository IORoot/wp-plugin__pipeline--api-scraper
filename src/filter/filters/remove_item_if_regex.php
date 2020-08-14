<?php

namespace yt\filter;

use yt\interfaces\filterInterface;

class remove_item_if_regex implements filterInterface
{

    public $description = "
    Removes an item in the array if the REGEX is true.
        'item_field' is the field you wish to perform the REGEX on.
        'regex' is the REGEX string to preg_match() on.
    
    If the REGEX is true, the item in the collection will be removed.
    ";

    public $parameters = "
    (array) 
    [
        'item_field' => 'snippet->title', 
        'regex' => '/Roblox|GTA/' 
    ]";

    public $collection;

    public $config;




    public function config($config)
    {
        $this->config = $config;
        $this->config_string_to_array();
        return;
    }

    public function in($collection)
    {
        $this->collection = $collection;
        return;
    }

    public function out()
    {
        $this->loop_collection();

        return $this->collection;
    }




    public function config_string_to_array()
    {
        $config = preg_replace("/\r|\n/", "", $this->config);
        $this->config = eval("return " . $config . ";");
        return;
    }




    public function loop_collection(){

        foreach($this->collection->items as $key => $current_item)
        {
            $value_in_field = $this->value_at_reference($current_item, $this->config['item_field']);

            if ($this->should_item_be_in_collection($value_in_field))
            {
                unset($this->collection->items[$key]);
            }
        }

        return;
    }




    public function should_item_be_in_collection($value_in_field)
    {

        if (!is_string($value_in_field)){
            $value_in_field = json_encode($value_in_field);
        }

        return preg_match($this->config['regex'], $value_in_field);
    }




    public function value_at_reference($current_item, $pathString, $delimiter = '->'){

        //split the string into an array
        $pathArray = explode($delimiter, $pathString);
    
        //get the first and last of the array
        $module = array_shift($pathArray);
        $property = array_pop($pathArray);
    
        //if the array is now empty, we can access simply without a loop
        if(count($pathArray) == 0){
            if (!isset($current_item->{$module}->{$property})){ return; }
            return $current_item->{$module}->{$property};
        }
    
        //we need to go deeper
        //$tmp = $this->Foo
        $tmp = $current_item->{$module};
    
        foreach($pathArray as $deeper){
            //re-assign $tmp to be the next level of the object
            // $tmp = $Foo->Bar --- then $tmp = $Bar->baz
            $tmp = $tmp->{$deeper};
        }
    
        //now we are at the level we need to be and can access the property
        return $tmp->{$property};
    
    }

}
