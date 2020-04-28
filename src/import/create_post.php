<?php

namespace yt\import;

class post
{
    public $args;

    public $result;

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
        if (post_exists($this->args['post_title'])) {
            (new \yt\e)->line('Post exists, skipping : ' . $this->args['post_title'], 2);
            return;
        } else {
            (new \yt\e)->line('Inserting Post : ' . $this->args['post_title'], 2 );
        }

        $this->result = wp_insert_post(
            $this->args
        );

        return;
    }


    public function result()
    {
        return $this->result;
    }
}
