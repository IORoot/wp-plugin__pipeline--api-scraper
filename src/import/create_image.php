<?php

namespace yt\import;

use \yt\import\downloader;

class image
{

    public $url;

    public $alt = 'ParkourPulse';

    public $filename;

    public $args;

    public $result_id;


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

        // check - does image exist?

        $this->set_url_unset();
        $this->set_alt_unset();
        $this->set_filename_unset();
        
        $this->result_id = (new downloader)->download($this->url, $this->args, $this->alt, $this->filename);

        return;
    }

    public function result()
    {
        return $this->result_id;
    }


    public function set_url_unset()
    {
        $this->url = $this->args['url'];
        unset($this->args['url']);

        return;
    }

    public function set_filename_unset()
    {
        // create a unique filename based off url
        // e.g. https://i.ytimg.com/vi/Q02DIy2az2k/default_live.jpg
        $this->filename = md5($this->url);

        // override with optional supplied filename
        if (isset($this->args['filename'])){
            $this->filename = $this->args['filename'];
            unset($this->args['filename']);
        }

        $this->add_word_onto_end_of_title($this->filename);

        return;
    }


    public function set_alt_unset()
    {
        $this->alt = $this->args['post_title'];

        if (isset($this->args['alt']))
        {
            $this->alt = $this->args['alt'];
            unset($this->args['alt']);
        } 

        $this->add_word_onto_end_of_title($this->alt);

        return;
    }

    // To distinguish between a post and it's image,
    // add on the word 'image' to the title at the end.
    // otherwise when we're checking for an existing
    // post, it'll see the image as a post with the same
    // title and say it exists!
    // By adding the word 'title' it'll be different
    // to the post title slightly.
    public function add_word_onto_end_of_title($title)
    {
        $title = $title . ' image';
        return;
    }


    public function add_taxonomy_to_content()
    {
        
    }

}