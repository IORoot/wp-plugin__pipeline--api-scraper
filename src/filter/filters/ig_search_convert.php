<?php

namespace yt\filter;

use yt\interfaces\filterInterface;
use yt\filter\format\instagram;

class ig_search_convert implements filterInterface
{
    
    public $description = "Converts Instagram Search Results to expected input for mapper";

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
        $items = new instagram($this->input->graphql->hashtag->edge_hashtag_to_media->edges);

        return $items;
    }

}