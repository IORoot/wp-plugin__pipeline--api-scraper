<?php

namespace yt\itunes\request;

use yt\interfaces\requestInterface;
use yt\itunes\response;
use yt\itunes\request\podcast_episode;

class podcast_group implements requestInterface
{
    public $nice_name = "iTunes Podcast Group";

    public $description = "Get the RSS feed as JSON from the iTunes API for Multiple Podcasts";

    public $parameters = '(int) Number of results per Podcast to return.';

    public $cost = 0;

    public $domain = 'https://itunes.apple.com/lookup?id=';

    public $config = [
        'api_key' => null,
        'query_string' => null,
        'extra_parameters' => 5,
    ];

    public $response;

    public $built_request_url;


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

        // $this->response->items = [];

        foreach ($this->csv_explode() as $channelID)
        {
            $config = [
                'api_key' => null,
                'query_string' => $channelID,
                'extra_parameters' => null,
            ];
            
            $podcast = new podcast_episode; 
            $podcast->config($config);
            $podcast->request();

            $podcast_response = $podcast->response();

            $sliced_podcast = array_slice($podcast_response->items, 0, $this->config['extra_parameters']);

            // if the first entry.
            if (!isset($this->response->items)){
                $this->response = new \stdClass;
                $this->response->items = $sliced_podcast;
                continue;
            }

            $this->response->items = array_merge($this->response->items, $sliced_podcast);


            (new \yt\r)->new('search', 'RESPONSE:'. json_encode($this->response, JSON_PRETTY_PRINT));

        }

        return true;
    }


    public function csv_explode()
    {
        $this->config['query_string'] = str_replace(' ', '', $this->config['query_string']);
        return explode(',',$this->config['query_string']);
    }

}
