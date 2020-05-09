<?php

namespace yt\itunes\request;

use yt\interfaces\requestInterface;
use yt\itunes\response;

class podcast_episode implements requestInterface
{
    public $nice_name = "iTunes Podcast Episodes";

    public $description = "Get the RSS feed as JSON from iTunes API";

    public $parameters = 'none';

    public $cost = 0;

    public $domain = 'https://itunes.apple.com/lookup?id=';

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

        if (!$this->request_to_apple()) {
            return false;
        };
        
        (new \yt\e)->line('- Calling RSS Feed.', 1);

        $this->retreive_rss_feed();

        $this->convert_rss_to_object();

        $this->reorganise_object();

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
        if (!$this->check_url()) {
            return false;
        }

        $this->built_request_url = $this->domain . $this->config['query_string'];

        (new \yt\r)->last('search', 'QUERSTRING = '. $this->built_request_url);
    }


    private function request_to_apple()
    {
        try {
            $this->response = json_decode(wp_remote_fopen($this->built_request_url));
        } catch (\Exception $e) {
            (new \yt\e)->line('- \Exception calling API' . $e->getMessage(), 1);
            return false;
        }

        (new \yt\r)->last('search', 'RESPONSE:'. json_encode($this->response, JSON_PRETTY_PRINT));

        return true;
    }



    private function retreive_rss_feed()
    {
        $rss_url = $this->response->results[0]->feedUrl;

        try {
            $this->response = wp_remote_fopen($rss_url);
        } catch (\Exception $e) {
            (new \yt\e)->line('- \Exception calling RSS' . $e->getMessage(), 1);
            return false;
        }

        return true;
    }



    public function convert_rss_to_object()
    {
        $fileContents = str_replace(array("\n", "\r", "\t"), '', $this->response);
        $fileContents = trim(str_replace('"', "'", $fileContents));
        $simpleXml = simplexml_load_string($fileContents);
        $this->response = $simpleXml;

        (new \yt\r)->last('search', 'RSS->JSON RESPONSE:'. json_encode($this->response->channel, JSON_PRETTY_PRINT));

        return;
    }


    public function reorganise_object()
    {

        /**
         * Convert SimpleXMLElement to STDClass. (using JSON)
         */
        $this->response = json_decode(json_encode($this->response));

        /**
         * Change response
         */
        $this->response = $this->response->channel;

        /**
         * Relabel
         */
        $this->response->items = $this->response->item;

        /**
         * Add image to all items
         */
        if (isset($this->response->image)){
            foreach ($this->response->items as $item)
            {
                $item->image = $this->response->image;
            }
        }        

        unset($this->response->item);
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
