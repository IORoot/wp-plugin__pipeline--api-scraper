<?php

namespace yt\import;

use \yt\import\downloader;

class image
{

    public $url;

    public $alt;

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
        // create a unique filename.
        $this->filename = uniqid();

        // override with optional supplied filename
        if (isset($this->args['filename'])){
            $this->filename = $this->args['filename'];
            unset($this->args['filename']);
        }

        return;
    }


    public function set_alt_unset()
    {
        $this->alt = $this->args['post_title'];

        if ($this->args['alt'])
        {
            $this->alt = $this->args['alt'];
            unset($this->args['alt']);
        }

        return;
    }

}