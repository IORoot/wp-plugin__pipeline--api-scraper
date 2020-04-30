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
            (new \yt\r)->last('import','Post exists, skipping : ' . $this->args['post_title']); 
            return;
        } else {
            (new \yt\e)->line('Inserting Post : ' . $this->args['post_title'], 2 );
            (new \yt\r)->last('import','Inserting Post : ' . $this->args['post_title']); 
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
