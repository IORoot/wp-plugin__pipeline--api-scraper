<?php

namespace yt\import;

class post
{
    public $args;

    public $result;

    public function __construct()
    {
        $this->include_for_cron();

        (new \yt\r)->clear('import'); 
        return $this;
    }



    public function set_args($args)
    {
        $this->args = $args;

        return $this;
    }


    public function add()
    {

        (new \yt\e)->line('Inserting Post : ' . $this->args['post_title'], 2 );
        (new \yt\e)->line('import - Inserting Post : ' . $this->args['post_title']); 
        
        $this->result = wp_insert_post(
            $this->args
        );

        return;
    }


    public function result()
    {
        return $this->result;
    }

    public function include_for_cron()
    {
        if ( ! function_exists( 'post_exists' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/post.php' );
        }
    }


}
