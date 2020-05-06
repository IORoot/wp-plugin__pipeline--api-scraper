<?php

namespace yt\request;

use yt\interfaces\requestInterface;
use yt\quota;
use yt\response;

class playlistitems implements requestInterface
{
    public $description = "Performs a search on the youtube search:list endpoint.";

    public $parameters = 'none';

    public $cost = 1;
    public $cost_per_result = 2; // snippet

    public $domain = 'https://www.googleapis.com/youtube/v3';

    public $config = [
        'api_key' => null,
        'query_string' => null,
        'extra_parameters' => null,
        'page_token' => null,
    ];

    public $built_request_url;

    public $response;

    public $last_response;

    // Nest limit is to stop nested looping beyond 5 times.
    // with a page limit of 50 items this means a max
    // of 250 items can be returned. More than enough for most
    // things.
    public $nest_level = 0;
    public $nest_limit = 5;

    public function config($config)
    {
        $this->config = $config;
        return;
    }

    public function response()
    {
        return $this->response[0];
    }

    public function get_cost()
    {
        return $this->cost;
    }


    public function request()
    {
        $this->nest_level++;
        (new quota)->update_quota_by_api_key($this->cost, $this->config['api_key']);
        $this->build_request_url();

        (new \yt\e)->line('- Calling API.', 1);

        try {
            $this->last_response = json_decode(wp_remote_fopen($this->built_request_url));
            $this->response[] = $this->last_response;
        } catch (\Exception $e) {
            (new \yt\e)->line('- \Exception calling YouTube' . $e->getMessage(), 1);
            return false;
        }
        
        (new \yt\r)->last('search', 'RESPONSE:'. json_encode($this->response, JSON_PRETTY_PRINT));

        if (!(new response)->is_errored($this->last_response)) {
            return false;
        }

        $this->iterate_all_pages();

        $this->combine_results();

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
        $pageToken = '';
        if (!$this->check_url()) {
            return false;
        }
        if (isset($this->config['page_token'])) {
            $pageToken = '&pageToken='.$this->config['page_token'];
        }
        $this->built_request_url = $this->domain . '/playlistItems?' . $this->config['query_string'] . "&key=" . $this->config['api_key'] . $pageToken;
    }


    private function iterate_all_pages()
    {
        // safety feature to not infinitely loop
        if ($this->nest_level >= $this->nest_limit){ return; }

        $last_entry = end($this->response);

        if (isset($last_entry->nextPageToken)) {
            $this->config['page_token'] = $last_entry->nextPageToken;
            $this->request();
        }

        return;
    }


    private function combine_results()
    {
        foreach($this->response as $key => $response)
        {
            if ($key == 0){ continue; }
            $this->response[0]->items = array_merge($this->response[0]->items, $response->items);
            unset($this->response[$key]);
        }
    }

    private function cost_of_items()
    {
        $item_count = count($this->response[0]->items);
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
