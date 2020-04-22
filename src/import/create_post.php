<?php

namespace yt;

class post
{

    public $post_data;

    public $post_type;


    public function __construct()
    {
        return $this;
    }



    public function set_posttype($post_type)
    {
        $this->post_type = $post_type;

        return $this;
    }


    
    public function set_postdata($post_data)
    {
        $this->post_data = $post_data;

        return $this;
    }



    public function process_postdata()
    {

    }
    



}
