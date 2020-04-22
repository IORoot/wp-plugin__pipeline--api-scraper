<?php

namespace yt;

use \yt\api;
use \yt\filter;
use \yt\options;

class scraper
{
    public $options;

    public $api;

    public $filter;


    public function __construct()
    {
        $this->options = new options;

        $this->api = new api;

        $this->filter = new filter;

        return;
    }

    public function run()
    {

        // Query youtube.
        $this->scrape();

        // Filter the results returned
        $this->filter();

        // Insert results into CPT
    }



    public function scrape()
    {

        // Check if search is enabled.
        if ($this->options->search['search_enabled'] == 0) {
            return false;
        }

        // Has the API key been set?
        $this->api->set_creds($this->options->creds['api_key']);

        // set the search string
        $this->api->set_query_string($this->options->search['search_string']);

        // Get the YouTube result.
        $this->api->search();
        
        return $this;
    }



    public function filter()
    {
        return;
    }
}
