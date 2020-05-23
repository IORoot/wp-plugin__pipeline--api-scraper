<?php

namespace yt;

use \yt\api_list;

class request_list
{
    public $list = [];

    public $api_list = [
        'youtube',
        'instagram',
    ];


    public function __construct()
    {
        $this->get_api_list();
        $this->get_request_list();
        return;
    }


    public function get_api_list()
    {
        $this->api_list = new api_list;
    }

    
    public function get_request_list()
    {
        $files = [];

        foreach ($this->api_list->list as $api)
        {

            $files = scandir(__DIR__ . '/' . $api . '/requests');

            $this->add_files_to_list($api, $files);
        }

        return;
    }


    public function add_files_to_list($api, $files)
    {

        foreach ($files as $file) {

            if ($file == '.' || $file == '..'){ continue; }
            // remove PHP filetype
            $file = str_replace('.php', '', $file);

            //create instance and get nicename
            $instance = '\\yt\\'.$api.'\\request\\'.$file;
            $object = new $instance;
            $nice_name = $object->nice_name;

            // add to result array
            $this->list[$file] = $nice_name;
        }

        return;

    }

}
