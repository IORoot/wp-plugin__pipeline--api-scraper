<?php

namespace yt\housekeep;

use yt\interfaces\housekeepInterface;

class bin_posts implements housekeepInterface{


    public $query;

    public $post_list;

    public $response;

    
    public function __construct()
    {
        return $this;
    }

    public function wp_query($wp_query)
    {
        $config = preg_replace( "/\r|\n/", "", $wp_query );
        $this->query = eval("return $config;");

        $this->post_list();

        return;
    }

    public function run()
    {
        if (!isset($this->post_list)){
            return;
        }

        foreach($this->post_list as $post)
        {
            $this->response[] = wp_trash_post( $post->ID, true);
        }

        return;
    }

    /**
     * Report : What WILL happen and what HAS happened.
     */
    public function result()
    {
        (new \yt\r)->clear('housekeep');
        if (!isset($this->post_list)){
            (new \yt\r)->last('housekeep', 'Category empty, skipping.');
            return;
        }
        if (!is_array($this->post_list)){
            (new \yt\r)->last('housekeep', 'Category not an array, skipping.');
            return;
        }
        (new \yt\r)->last('housekeep', 'Will bin ' . count($this->post_list) . ' records.'); 
        (new \yt\r)->last('housekeep', 'Response : ' . count($this->response) . ' put in the bin.'); 
        return ;
    }


    public function post_list()
    {
        if (!isset($this->query)){
            return;
        }
        $this->post_list = get_posts($this->query);
    }


}