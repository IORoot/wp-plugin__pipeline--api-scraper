<?php

namespace yt\instagram\request;

use yt\interfaces\requestInterface;
use yt\instagram\response;
use yt\instagram\request\account_info;

class multi_accounts implements requestInterface
{
    public $nice_name = "IG Multi Account Info";

    public $description = "Performs a search on multiple instagram accounts. (CSV separated)";

    public $parameters = '(int) Number of posts per channel to retrieve.';

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
        if (isset($this->response->items)) {
            $this->response->items = [];
        }

        foreach ($this->csv_explode() as $accountID) {
            $config = [
                'api_key' => null,
                'query_string' => $accountID,
                'extra_parameters' => null,
            ];
            
            $instagram = new account_info;
            $instagram->config($config);
            $instagram->request();

            $instagram_response = $instagram->response();

            if (isset($instagram_response->graphql->user->edge_owner_to_timeline_media->edges)) {
                $sliced_instagram = array_slice($instagram_response->graphql->user->edge_owner_to_timeline_media->edges, 0, $this->config['extra_parameters']);
                $this->response->items = array_merge($this->response->items, $sliced_instagram);
            }
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

    public function csv_explode()
    {
        $this->config['query_string'] = str_replace(' ', '', $this->config['query_string']);
        return explode(',', $this->config['query_string']);
    }
}
