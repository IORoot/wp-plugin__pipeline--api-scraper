<?php

namespace yt\filter;

use yt\interfaces\filterInterface;

class remove_array_item_if_regex implements filterInterface
{

    public $description = "
        Scans through an array for the regex and removes the array item if found.
        This is case-insensitive.
        'item_array' is the array you wish to perform the REGEX on.
        'regex' is the REGEX string to preg_match() on.
    
        If the REGEX is true, the item in the array will be removed.
    ";

    public $parameters = "
    (array) 
    [
        'item_array' => 'snippet->tags', 
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
            $array_of_values = $this->value_at_reference($current_item, $this->config['item_array']);

            if(!is_array($array_of_values)){
                return;
            }

            if ($this->should_item_be_in_collection($array_of_values))
            {
                // remove result row from collection.
                unset($this->collection->items[$key]);
            }
            
            
        }

        return;
    }




    public function should_item_be_in_collection($array_of_values)
    {
        // loop through every entry in the array.
        foreach ($array_of_values as $array_item)
        {
            // if any match the regex, return true.
            if (preg_match($this->config['regex'], $array_item))
            {
                return true;
            }
        }

        return false;
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
