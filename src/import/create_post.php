<?php

namespace yt;

class post
{

    public $post;

    public function __construct()
    {
        return $this;
    }

    public function set_post($post)
    {
        $this->post = $post;
        
        return $this;
    }



}
