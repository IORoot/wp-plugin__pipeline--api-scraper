<?php

namespace yt\import;

class post
{
    public $post_args;

    public function __construct()
    {
        return $this;
    }



    public function set_postargs($post_args)
    {
        $this->post_args = $post_args;

        return $this;
    }


    public function add_posttype($post_type)
    {
        $this->post_args['post_type'] = $post_type;
        return $this;
    }


    public function add()
    {
        if (post_exists($this->post_args['post_title'])) {
            (new \yt\e)->line('Post exists, skipping : ' . $this->post_args['post_title'], 2);
            return;
        } else {
            (new \yt\e)->line('Inserting Post : ' . $this->args['post_title'], 2 );
        }

        wp_insert_post(
            $this->post_args
        );

        return;
    }
}
