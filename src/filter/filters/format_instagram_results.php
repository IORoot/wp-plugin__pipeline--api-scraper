<?php

namespace yt\filter;

use yt\interfaces\filterInterface;

class format_instagram_results implements filterInterface
{
    
    public $description = "Converts Instagram Results to expected input for mapper";

    public $parameters = "None";

    public $input;
    
    public function config($config)
    {
        return;
    }

    public function in($input)
    {
        $this->input = $input;
        return;
    }

    public function out()
    {

        // convert it to a class object with an items array.
        $items = new ig($this->input->graphql->user->edge_owner_to_timeline_media->edges);

        return $items;
    }

}



class ig 
{
    public $items;

    public function __construct($items)
    {
        $this->items = $items;
        return $this;
    }
}
