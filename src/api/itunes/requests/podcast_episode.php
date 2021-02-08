<?php

namespace yt\itunes\request;

use yt\interfaces\requestInterface;
use yt\itunes\response;

class episode implements requestInterface
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

    public $artwork_url;

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

        (new \yt\e)->line('search - QUERSTRING = '. $this->built_request_url);
    }


    private function request_to_apple()
    {
        try {
            $this->response['itunes'] = json_decode(wp_remote_fopen($this->built_request_url));
        } catch (\Exception $e) {
            (new \yt\e)->line('- \Exception calling API' . $e->getMessage(), 1);
            return false;
        }

        return true;
    }



    private function retreive_rss_feed()
    {
        $rss_url = $this->response['itunes']->results[0]->feedUrl;
        $this->artwork_url = $this->response['itunes']->results[0]->artworkUrl600;

        try {
            $this->response['rss'] = wp_remote_fopen($rss_url);
        } catch (\Exception $e) {
            (new \yt\e)->line('- \Exception calling RSS' . $e->getMessage(), 1);
            return false;
        }

        return true;
    }



    public function convert_rss_to_object()
    {

        $original = $this->response['rss'] ;
        $this->response['rss'] = simplexml_load_string($original, "SimpleXMLElement", LIBXML_NOCDATA);

        // Try again with some sanitising.
        if ($this->response['rss'] == false){
            $fileContents = str_replace(array("\n", "\r", "\t"), '', $this->response['rss']);
            $fileContents = trim(str_replace('"', "'", $fileContents));
            $simpleXml = simplexml_load_string($fileContents);
            $this->response['rss'] = $simpleXml;
        }

        return;
    }


    public function reorganise_object()
    {

        /**
         * Convert SimpleXMLElement to STDClass. (using JSON)
         */
        $this->response['rss'] = json_decode(json_encode($this->response['rss']));

        /**
         * Change response
         */
        $this->response['rss'] = $this->response['rss']->channel;

        /**
         * Add iTunes data into every RSS item
         */
        foreach ($this->response['rss']->item as $item)
        {
            $item->itunes = $this->response['itunes']->results[0];
        }
        
        /**
         * Add image to all items
         */
        if (isset($this->response['rss']->image)){

            // replace for the itunes artwork URL - more reliable URL.
            $this->response['rss']->image->url = $this->artwork_url;

            foreach ($this->response['rss']->item as $item)
            {
                $item->image = $this->response['rss']->image;
            }
        }

        /**
         * Add any missing links
         */
        foreach ($this->response['rss']->item as $item)
        {
            if (!isset($item->link))
            {
                $item->link = $this->response['rss']->link;
            }
        }
        
        /**
         * Relabel
         */
        $this->response = $this->response['rss'];
        
        $this->response->items = $this->response->item;
        unset($this->response->item);

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

        return true;
    }
}
