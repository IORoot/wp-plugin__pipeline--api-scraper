<?php

namespace yt;

use \yt\api;
use \yt\filter;
use \yt\import;
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

        $this->import = new import;

        return;
    }



    public function run()
    {

        // Query youtube.
        $this->scrape();

        // Filter the results returned
        $this->filter();

        // Import results into CPT
        $this->import_terms();
    }



    public function scrape()
    {

        // Check if search is enabled.
        if ($this->options->search['yt_search_enabled'] == 0) {
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


    public function import_terms()
    {
        // Check if import is enabled.
        if ($this->options->import['yt_import_enabled'] == 0) {
            return false;
        }

        $taxonomy = $this->options->import["yt_import_taxonomy_type"];

        // loop over each search, adding each search_name to the taxonomy.
        foreach ($this->options->search as $search_row)
        {
            $term = $search_row['yt_search_name'];
            $desc = $search_row['yt_search_description']; // optional
            $this->import->add_term($taxonomy, $term, $desc);
        }

        return $this;
    }
}
