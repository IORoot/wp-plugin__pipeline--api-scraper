<?php

namespace yt\request;

use yt\interfaces\requestInterface;

use yt\response;

class multichannel implements requestInterface
{
    public $description = "Returns list of video uploads from each channel supplied.";

    public $parameters = '
    (array) [ \'channels\' => \'channelid1,channelid2\']
    ';

    public $cost = 4;

    public $domain = 'https://www.googleapis.com/youtube/v3';

    public $config = [
        'api_key' => null,
        'query_string' => null,
        'extra_parameters' => null,
    ];

    public $built_request_url;

    public $response;

    public $channel_list;

    public $last_response;


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
        $this->create_channel_list_from_csv();

        foreach ($this->channel_list as $channel_id) {
            (new \yt\e)->line('- Calling API for Channel_ID : '.$channel_id, 1);
            $this->build_request_url($channel_id);
            $this->call_api();

            if (!(new response)->is_errored($this->last_response)) {
                return false;
            }
        }

        $this->combine_results();

        $this->filter_for_uploads_only();

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


    private function combine_results()
    {
        foreach ($this->response as $key => $response) {
            if ($key == 0) {
                continue;
            }
            $this->response[0]->items = array_merge($this->response[0]->items, $response->items);
            unset($this->response[$key]);
        }
    }

    public function filter_for_uploads_only()
    {
        foreach ($this->response[0]->items as $key => $item) {
            if (!isset($item->contentDetails->upload)) {
                unset($this->response[0]->items[$key]);
            }
        }
    }

    private function call_api()
    {
        try {
            $this->last_response = json_decode(wp_remote_fopen($this->built_request_url));
            $this->response[] = $this->last_response;
        } catch (\Exception $e) {
            (new \yt\e)->line('- \Exception calling YouTube' . $e->getMessage(), 1);
            return false;
        }
        (new \yt\r)->last('search', 'RESPONSE:'. json_encode($this->last_response, JSON_PRETTY_PRINT));
    }

    private function create_channel_list_from_csv()
    {
        str_replace(' ', '', $this->config['extra_parameters']['channels']);
        $this->channel_list = explode(',', $this->config['extra_parameters']['channels']);
        return;
    }


    private function build_request_url($channel_id)
    {
        if (!$this->check_url()) {
            return false;
        }
        if (!$channel_id) {
            return false;
        }
        $this->built_request_url = $this->domain . '/activities?part=snippet%2CcontentDetails&channelId='.$channel_id.'&' . $this->config['query_string'] . "&key=" . $this->config['api_key'];
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
        if ($this->config['api_key'] == '') {
            (new \yt\e)->line('- $this->config[api_key] is blank. Please set.', 1);
            return false;
        }

        return true;
    }
}
