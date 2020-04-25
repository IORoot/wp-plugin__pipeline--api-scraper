<?php

namespace yt;

use \yt\api;
use \yt\filter as filter;
use \yt\mapper_collection as mapper;
use \yt\import;
use \yt\options;

class scraper
{
    /**
     * The $options parameter is the main one. 
     * It holds two things. 
     * 
     * 1. all of the options set for each particular scrape instance.
     * 
     * 2. As we move through the process of each step: auth, scrape, 
     * filter, map, import, etc... the results from each step are added
     * to the scrape instance. This is so this single array contains
     * all results and options for only that particular scrape.
     * 
     * yt_scrape_enabled = is this on/off to scrape
     * yt_scrape_id      = unique id.
     * yt_scrape_auth    = authentication details.
     * yt_scrape_search  = search details
     * yt_scrape_filter  = filter details
     * yt_scrape_mapper  = mapper details
     * yt_scrape_import  = import details
     * yt_scrape_response = response back from the API.
     * yt_scrape_filtered = response back after being filtered.
     * yt_scrape_mapped   = response after being mapped (post array)
     * yt_scrape_imported = response from import job.
     */
    public $options;

    public $filter;

    private $api;

    private $mapper;

    // Temporary parameters
    // Used in the scrape process.
    private $_scrape_instance;
    private $_scrape_key;

    // Temporary parameters
    // used in the mapping process
    private $_map_item;


    public function __construct()
    {
        $this->options = new options;

        $this->api = new api;

        $this->filter = new filter;

        $this->mapper = new mapper;

        $this->import = new import;

        return;
    }



    public function run()
    {

        // loop over each scrape instance.
        foreach ($this->options->scrape as $this->_scrape_key => $this->_scrape_instance){

            // has this scrape been enabled?
            if ($this->_scrape_instance['yt_scrape_enabled'] != true){ continue; }

            // run it.
            $this->process_single_scrape();

        }

        return;
    }



    public function process_single_scrape(){

        // Query API.
        $this->scrape_api();

        // // Filter the results returned
        $this->filter();

        // Map fields
        // $this->mapper_all_items();

        // // Import results into CPT
        // $this->import_terms();

        // // Import post into CPT
        // $this->import_all_posts_from_all_searches();

        return;
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐ 
    // │                                                                         │░
    // │                                                                         │░
    // │                                SCRAPING                                 │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    // ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


    public function scrape_api()
    {

        // Set the API Key.
        //
        // Scrape Instance
        //   - auth array
        //     - api key  
        $this->api->set_api_key($this->_scrape_instance['yt_scrape_auth']['yt_api_key']);

        // set the search string
        // 
        // Scrape Instance
        //   - search array
        //     - search string  
        $this->api->set_query($this->_scrape_instance['yt_scrape_search']['yt_search_string']);

        // Get the YouTube results and add to scrape array.
        $this->options->scrape[$this->_scrape_key]['yt_scrape_response'] = $this->api->run();

        return;
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐ 
    // │                                                                         │░
    // │                                                                         │░
    // │                                FILTERING                                │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    // ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


    public function filter()
    {

        // This is the group of filters to
        // perform on the results of the API response.
        $this->filter->set_filter_group($this->options->scrape[$this->_scrape_key]['yt_scrape_filter']);

        // We also need the response from the API
        // This is so we can perform all of the filters on them
        $this->filter->set_item_collection($this->options->scrape[$this->_scrape_key]['yt_scrape_response']);

        // once everything is set, run it.
        // Then add the respons of the filtering into the scrape
        // object.
        $this->options->scrape[$this->_scrape_key]['yt_scrape_filtered'] = $this->filter->run();

        return;
    }



    // ┌─────────────────────────────────────────────────────────────────────────┐ 
    // │                                                                         │░
    // │                                                                         │░
    // │                                 MAPPING                                 │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    // ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


    public function mapper_all_items()
    {

        // Is there a response to use?
        // Check that the response array has a value, otherwise we can't
        // map anything.
        if ($this->options->scrape[$this->_scrape_key]['yt_scrape_response'] == null) {
            throw new Exception('YouTube Response is empty or does not exist. Check $this->options->scrape['.$this->_scrape_key.'][yt_scrape_response]');
        }

        // Give the mapper all filters
        // This is because we'll need the parameters for
        // every filter to be able to run it.
        $this->mapper->set_filters($this->options->filter);


        // Give the mapper the mappings we want it to perform
        $this->mapper->set_mappings($this->options->scrape[$this->_scrape_key]['yt_scrape_mapper']['yt_mapper_row']);


        // set the mapper to use the array collection
        // returned from youtube.
        $this->mapper->set_collection($this->options->scrape[$this->_scrape_key]['yt_scrape_filtered']->items);


        // run it!
        $this->options->scrape[$this->_scrape_key]['yt_scrape_mapped'] = $this->mapper->run();


        return;
    }



    // ┌─────────────────────────────────────────────────────────────────────────┐ 
    // │                                                                         │░
    // │                                                                         │░
    // │                                IMPORTING                                │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    public function import_terms()
    {

        $taxonomy = $this->options->import["yt_import_taxonomy_type"];

        $this->import->add_terms($taxonomy, $this->options->search);

        return $this;
    }



    public function import_all_posts_from_all_searches()
    {

        if ($this->api->results == null) {
            return false;
        }

        $post_type = $this->options->import["yt_import_post_type"];

        foreach ($this->api->results as $result_posts) {
            $this->import->add_posts($post_type, $result_posts);
        }
        

        return $this;
    }

}
