<?php

namespace yt\instagram\request;

use yt\interfaces\requestInterface;
use yt\quota;
use yt\instagram\response;


class account_info implements requestInterface
{
    public $description = "Performs a search on a single instagram account.";

    public $parameters = 'none';

    public $cost = 0;

    public $domain = 'https://www.instagram.com/';

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
            (new \yt\e)->line('- \Exception calling API' . $e->getMessage(), 1);
            return false;
        }

        (new \yt\r)->last('search', 'RESPONSE:'. json_encode($this->response, JSON_PRETTY_PRINT));
        
        if (!(new response)->is_errored($this->response)) {
            return false;
        }

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

    private function build_request_url()
    {
        if(!$this->check_url()){return false;}  

        $this->built_request_url = $this->domain . $this->config['query_string'] . "/?__a=1";

        (new \yt\r)->last('search', 'QUERSTRING = '. $this->built_request_url); 
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

        return true;
    }
}
