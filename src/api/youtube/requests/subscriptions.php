<?php

namespace yt\youtube\request;

use yt\interfaces\requestInterface;
use yt\quota;
use yt\youtube\response;

class subscriptions implements requestInterface
{
    public $description = "Performs a search of subscriptions for a channel.";

    public $parameters = 'None';

    public $cost = 1;
    public $cost_per_result = 2;

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

        (new quota)->update_quota_by_api_key($this->cost, $this->config['api_key']);

        (new \yt\e)->line('- Calling API.', 1);

        try {
            $this->response = json_decode(wp_remote_fopen($this->built_request_url));
        } catch (\Exception $e) {
            (new \yt\e)->line('- \Exception calling YouTube' . $e->getMessage(), 1);
            return false;
        }
        
        (new \yt\r)->last('search', 'RESPONSE:'. json_encode($this->response, JSON_PRETTY_PRINT));

        if (!(new response)->is_errored($this->response)) {
            return false;
        }

        $this->cost_of_items();

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
        $this->built_request_url = $this->domain . '/subscriptions?' . $this->config['query_string'] . "&key=" . $this->config['api_key'];
    }


    private function cost_of_items()
    {
        $item_count = count($this->response->items);
        (new quota)->update_quota_by_api_key(($this->cost_per_result * $item_count), $this->config['api_key']);
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
