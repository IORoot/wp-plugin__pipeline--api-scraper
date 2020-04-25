<?php

namespace yt;

class api
{
    public $key;

    public $domain = "https://www.googleapis.com/youtube/v3";

    public $query_string;

    public $request_url;

    public $results;
    
    public function __construct()
    {
        return $this;
    }



    public function set_api_key($api_key)
    {
        if (!$api_key) {
            throw new \Exception('No API_KEY has been supplied.');
        }
        if ($api_key == '') {
            throw new \Exception('API_KEY is blank.');
        }

        $this->key = $api_key;
        return true;
    }



    public function set_query($string)
    {
        if (!$string) {
            throw new \Exception('No search string has been supplied. Please supply query_string.');
        }
        if ($string == '') {
            throw new \Exception('Search string is blank. Please supply query_string.');
        }

        $this->query_string = $string;
        return $this;
    }


    
    public function run()
    {
        $this->create_url();
        return $this->make_request();
    }



    public function create_url()
    {
        if ($this->domain == '') {
            throw new \Exception('api->domain is blank. Please set.');
        }
        if ($this->query_string == '') {
            throw new \Exception('api->query_string is blank. Please set.');
        }
        if ($this->key == '') {
            throw new \Exception('api->key is blank. Please set.');
        }

        $this->request_url = $this->domain . '/search?' . $this->query_string . "&key=" . $this->key;
        return $this;
    }



    public function make_request()
    {
        if ($this->request_url == null) {
            throw new \Exception('api->request_url is blank. Please set.');
        }

        try {
            $result = json_decode(wp_remote_fopen($this->request_url));
        } catch (Exception $e) {
            echo 'Caught \Exception calling YouTube: ',  $e->getMessage(), "\n";
        }

        return $result;
    }
}
