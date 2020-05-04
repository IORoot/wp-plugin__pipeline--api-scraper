<?php

namespace yt\housekeep;

use yt\interfaces\housekeepInterface;

class bin_all implements housekeepInterface{


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
            $this->bin_image($post->ID);
            $this->bin_post($post->ID);
        }

        return;
    }

    /**
     * Report : What WILL happen and what HAS happened.
     */
    public function result()
    {
        (new \yt\r)->clear('housekeep');
        (new \yt\r)->last('housekeep', 'Will bin ' . count($this->post_list) . ' post (and image attachments).'); 
        (new \yt\r)->last('housekeep', 'Response : ' . count($this->response) . ' were placed in the bin.'); 
        return ;
    }


    public function post_list()
    {
        $this->post_list = get_posts($this->query);
    }



    public function bin_post($post_id)
    {
        $this->response[] = wp_trash_post( $post_id, false);
    }

    
    
    public function bin_image($post_id)
    {
        $attachment_id = get_post_thumbnail_id($post_id);
        if ($attachment_id != ''){
            $this->response[] = wp_delete_attachment($attachment_id, false);
        }
        
    }


}