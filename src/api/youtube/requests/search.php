<?php

namespace yt\youtube\request;

use yt\interfaces\requestInterface;
use yt\quota;
use yt\youtube\response;

class search implements requestInterface
{
    public $nice_name = "YouTube Search";

    public $description = "Performs a search on the youtube search:list endpoint.";

    public $parameters = 'none';

    public $cost = 100;

    public $domain = 'https://www.googleapis.com/youtube/v3';

    public $config = [
        'api_key' => null,
        'query_string' => null,
        'extra_parameters' => null,
    ];

    public $built_request_url;

    public $response;

    public function config($config)
    {
        $this->config = $config;
        return;
    }

    public function response()
    {
        return $this->response;
    }

    public function get_cost()
    {
        return $this->cost;
    }


    public function request()
    {
        $this->build_request_url();

        (new \yt\e)->line('- Calling API.', 1);

        try {
            $this->response = json_decode(wp_remote_fopen($this->built_request_url));
        } catch (\Exception $e) {
            (new \yt\e)->line('- \Exception calling YouTube' . $e->getMessage(), 1);
            return false;
        }

        $this->add_index_to_items();

        (new \yt\r)->last('search', 'RESPONSE:'. json_encode($this->response, JSON_PRETTY_PRINT));
        
        if (!(new response)->is_errored($this->response)) {
            return false;
        }

        (new quota)->update_quota_by_api_key($this->cost, $this->config['api_key']);


        return true;
    }



    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │░
    // │                                                                         │░
    // │                                PRIVATE                                  │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    public function build_request_url()
    {
        if(!$this->check_url()){return false;}  
        $this->built_request_url = $this->domain . '/search?' . $this->config['query_string'] . "&key=" . $this->config['api_key'];
        (new \yt\r)->last('search', 'QUERSTRING = '. $this->built_request_url); 
    }

    public function add_index_to_items()
    {
        foreach ($this->response->items as $index => $item)
        {
            $item->index = $index;
        }
        return;
    }

    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │░
    // │                                                                         │░
    // │                                 CHECKS                                  │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    public function check_url()
    {
        if ($this->domain == '') {
            (new \yt\e)->line('- $this->domain is blank. Please set.', 1);
            return false;
        }
        if ($this->config['query_string'] == '') {
            (new \yt\e)->line('- $this->config[query_string] is blank. Please set.', 1);
            return false;
        }
        if ($this->config['api_key'] == '') {
            (new \yt\e)->line('- $this->config[api_key] is blank. Please set.', 1);
            return false;
        }

        return true;
    }
}
