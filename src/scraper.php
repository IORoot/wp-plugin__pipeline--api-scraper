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

        // Import post into CPT
        // $this->import_posts();
    }



    public function scrape()
    {

        // Has the API key been set?
        $this->api->set_creds($this->options->creds['api_key']);

        // set the search string
        $this->api->set_queries($this->options->search);

        // Get the YouTube results.
        $this->api->run_all_queries();
        
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

        $this->import->add_terms($taxonomy, $this->options->search);

        return $this;
    }



    // public function import_posts()
    // {
        
    //     // Check if import is enabled.
    //     if ($this->options->import['yt_import_enabled'] == 0) {
    //         return false;
    //     }

    //     // Check if there are results back from youtube


    //     $post_type = $this->options->import["yt_import_post_type"];

    //     $this->import->add_posts($post_type, $this->search);

    //     return $this;
    // }
}
