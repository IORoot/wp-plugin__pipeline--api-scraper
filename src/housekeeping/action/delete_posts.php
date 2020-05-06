<?php

namespace yt\housekeep;

use yt\interfaces\housekeepInterface;

class delete_posts implements housekeepInterface{


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
        
        foreach($this->post_list as $post)
        {
            $this->response[] = wp_delete_post( $post->ID, true);
        }

        return;
    }

    /**
     * Report : What WILL happen and what HAS happened.
     */
    public function result()
    {
        (new \yt\r)->clear('housekeep');
        (new \yt\r)->last('housekeep', 'Will delete ' . count($this->post_list) . ' records.'); 
        (new \yt\r)->last('housekeep', 'Response : ' . count($this->response) . ' deleted.'); 
        return ;
    }


    public function post_list()
    {
        $this->post_list = get_posts($this->query);
    }


}