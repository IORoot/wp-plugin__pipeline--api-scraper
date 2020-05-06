<?php

namespace yt\import;

class meta
{

    public $args;

    public $result = 0;


    public function __construct()
    {
        return $this;
    }

    public function set_args($args)
    {
        $this->args = $args;

        return $this;
    }

    public function add()
    {
        return;
    }

    
    public function result()
    {

        return;
    }
    


}