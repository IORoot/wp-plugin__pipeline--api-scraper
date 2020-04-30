<?php

namespace yt\request;

use yt\interfaces\requestInterface;

class playlistitems implements requestInterface
{
    public $description = "Performs a search on the youtube search:list endpoint.";

    public $parameters = 'none';

    public $cost = 1;

    public $domain = 'https://www.googleapis.com/youtube/v3';

    public $config = [
        'api_key' => null,
        'query_string' => null,
        'extra_parameters' => null,
        'page_token' => null,
    ];

    public $built_request_url;

    public $response;

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

        $this->build_request_url();

        (new \yt\e)->line('- Calling API.', 1);

        try {
            $this->response[] = json_decode(wp_remote_fopen($this->built_request_url));
        } catch (\Exception $e) {
            (new \yt\e)->line('- \Exception calling YouTube' . $e->getMessage(), 1);
            return false;
        }

        $this->iterate_all_pages();

        $this->combine_results();

        $this->check_result();

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
        if ($this->config['page_token'] != null) {
            $pageToken = '&pageToken='.$this->config['page_token'];
        }
        $this->built_request_url = $this->domain . '/playlistItems?' . $this->config['query_string'] . "&key=" . $this->config['api_key'] . $pageToken;
    }


    private function iterate_all_pages()
    {
        // safety feature to not infinitely loop
        if ($this->nest_level >= $this->nest_limit){ return; }

        $last_entry = end($this->response);
        if ($last_entry->nextPageToken != '') {
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

    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │░
    // │                                                                         │░
    // │                                 CHECKS                                  │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    public function check_result()
    {
        if (isset($this->response->error)) {
            (new \yt\e)->line('- ERROR Code : ' . $this->response->error->code, 2);
            (new \yt\e)->line('- ERROR Reason : ' . $this->response->error->errors[0]->reason, 2);
            (new \yt\e)->line('- ERROR Message : ' . $this->response->error->message, 2);
            return false;
        }

        (new \yt\e)->line('- OK Response : ' . $this->response->kind, 2);
        (new \yt\e)->line('- Retrieved Rows : ' . $this->response->pageInfo->resultsPerPage, 2);
        (new \yt\e)->line('- Quota Cost : '.$this->cost, 2);

        return true;
    }

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
