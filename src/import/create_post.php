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
        
        if($this->does_it_match_title()){ return; };
        if($this->does_slug_match_santized_title()){ return; };

        (new \yt\e)->line('Inserting Post : ' . $this->args['post_title'], 2 );
        (new \yt\r)->last('import','Inserting Post : ' . $this->args['post_title']); 
        
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

    public function does_it_match_title()
    {
        $post_title = $this->args['post_title'];
        $does_it_exist = post_exists($post_title);

        if ($does_it_exist) {
            (new \yt\e)->line('Post exists, skipping : ' . $this->args['post_title'], 2);
            (new \yt\r)->last('import','Post exists, skipping : ' . $this->args['post_title']); 
            return true;
        } 

        return false;
    }
    

    public function does_slug_match_santized_title()
    {
        $sanitized_title = sanitize_title($this->args['post_title']);
        
        global $wpdb;
        if($wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = '" . $sanitized_title . "'", 'ARRAY_A')) {
            (new \yt\e)->line('Post exists, skipping : ' . $this->args['post_title'], 2);
            (new \yt\r)->last('import','Post exists, skipping : ' . $this->args['post_title']); 
            return true;
        }

        return false;
    }
}
