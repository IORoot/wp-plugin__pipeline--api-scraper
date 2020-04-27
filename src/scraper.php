<?php

namespace yt;

use \yt\api;
use \yt\filter as filter;
use \yt\mapper_collection as mapper;
use \yt\import;
use \yt\options;

// ┌─────────────────────────────────────────────────────────────────────────┐ 
// │                                                                         │░
// │                                 SCRAPER                                 │░
// │                                                                         │░
// │                                                                         │░
// │ The $options parameter is the main part.                                │░
// │ It hold:                                                                │░
// │                                                                         │░
// │ 1. all of the options set for each particular scrape instance.          │░
// │                                                                         │░
// │ 2. As we move through the process of each step: auth, scrape,           │░
// │ filter, map, import, etc... the results from each step are added        │░
// │ to the scrape instance. This is so this single array contains           │░
// │ all results and options for only that particular scrape.                │░
// │                                                                         │░
// │ yt_scrape_enabled = is this on/off to scrape                            │░
// │ yt_scrape_id      = unique id.                                          │░
// │ yt_scrape_auth    = authentication details.                             │░
// │ yt_scrape_search  = search details                                      │░
// │ yt_scrape_filter  = filter details                                      │░
// │ yt_scrape_mapper  = mapper details                                      │░
// │ yt_scrape_import  = import details                                      │░
// │ yt_scrape_response = response back from the API.                        │░
// │ yt_scrape_filtered = response back after being filtered.                │░
// │ yt_scrape_mapped   = response after being mapped (post array)           │░
// │ yt_scrape_imported = response from import job.                          │░
// │                                                                         │░
// └─────────────────────────────────────────────────────────────────────────┘░
//  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

class scraper
{

    public $options;

    public $filter;

    private $api;

    private $mapper;

    private $importer;

    // Temporary parameters
    private $_scrape_key;


    public function __construct()
    {
        (new e)->clear();

        $this->options = new options;

        $this->api = new api;

        $this->filter = new filter;

        $this->mapper = new mapper;

        $this->importer = new import;

        return;
    }



    public function run()
    {

        // loop over each scrape instance.
        foreach ($this->options->scrape as $this->_scrape_key => $value){
            
            // has this scrape been enabled?
            if ($this->options->scrape[$this->_scrape_key]['yt_scrape_enabled'] != true){ continue; }

            // run it.
            $this->process_single_scrape();

        }

        return;
    }



    public function process_single_scrape(){

        (new e)->line('RUNNING scrape - '.$this->options->scrape[$this->_scrape_key]['yt_scrape_id'] );

        // Query API.
        $this->scrape_api();

        // // Filter the results returned
        $this->filter();

        // Map fields
        $this->mapper();

        // Import results into CPT
        $this->import();

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
        (new e)->line('[ Auth ] : '.$this->options->scrape[$this->_scrape_key]['yt_scrape_auth']['yt_auth_id'] );
        (new e)->line('[ Search ] : '.$this->options->scrape[$this->_scrape_key]['yt_scrape_search']['yt_search_id'] );

        // Set the API Key.
        //
        // Scrape Instance
        //   - auth array
        //     - api key  
        $this->api->set_api_key($this->options->scrape[$this->_scrape_key]['yt_scrape_auth']['yt_api_key']);

        // set the search string
        // 
        // Scrape Instance
        //   - search array
        //     - search string  
        $this->api->set_query($this->options->scrape[$this->_scrape_key]['yt_scrape_search']['yt_search_string']);

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
    // ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░  ░░░░


    public function filter()
    {

        (new e)->line('[ Filter ] : '.$this->options->scrape[$this->_scrape_key]['yt_scrape_filter']['yt_filter_id'] );

        // do some checks.
        $this->has_response();

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


    public function mapper()
    {

        (new e)->line('[ Mapper ] : '.$this->options->scrape[$this->_scrape_key]['yt_scrape_mapper']['yt_mapper_id'] );

        // Do some checks
        $this->has_response();
        $this->has_filtered();

        // Give the mapper all transforms
        // This is because we'll need the parameters for
        // every transform to be able to run it.
        $this->mapper->set_transforms($this->options->transform);


        // Give the mapper the mappings we want it to perform
        $this->mapper->set_mappings($this->options->scrape[$this->_scrape_key]['yt_scrape_mapper']['yt_mapper_row']);


        // set the mapper to use the filtered array collection
        // returned from youtube.
        $this->mapper->set_collection($this->options->scrape[$this->_scrape_key]['yt_scrape_filtered']->items);

        // run it and set to the next array entry 
        // of the scrape.
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


    public function import()
    {
        (new e)->line('[ Import ] : '.$this->options->scrape[$this->_scrape_key]['yt_scrape_import']['yt_import_id'] );

        $this->add_term();
        $this->add_posts();
        
        return $this;
    }


    public function add_term()
    {
        
        $scrape_taxonomy    = $this->options->scrape[$this->_scrape_key]['yt_scrape_import']['yt_import_taxonomy_type'];
        $term_name          = $this->options->scrape[$this->_scrape_key]['yt_scrape_search']['yt_search_id'];
        $term_desc          = $this->options->scrape[$this->_scrape_key]['yt_scrape_search']['yt_search_description'];
        
        (new e)->line('- Add_Term : '.$scrape_taxonomy, 1 );

        $this->importer->add_term($scrape_taxonomy, $term_name, $term_desc);

        return;
    }

    public function add_posts()
    {
        $post_type  = $this->options->scrape[$this->_scrape_key]['yt_scrape_import']['yt_import_post_type'];
        $collection = $this->options->scrape[$this->_scrape_key]['yt_scrape_mapped'];

        (new e)->line('- Add_Posts : '.$post_type, 1 );
        
        $this->importer->add_posts($post_type, $collection);
    }



    // ┌─────────────────────────────────────────────────────────────────────────┐ 
    // │                                                                         │░
    // │                                                                         │░
    // │                                 CHECKING                                │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ 


    public function has_response()
    {
        if ($this->options->scrape[$this->_scrape_key]['yt_scrape_response'] == null) {
            throw new \Exception('YouTube Response is empty or does not exist. Check $this->options->scrape['.$this->_scrape_key.'][yt_scrape_response]');
        }
        return;
    }


    public function has_filtered()
    {
        if ($this->options->scrape[$this->_scrape_key]['yt_scrape_filtered'] == null) {
            throw new \Exception('There is no filtered array. Check that the filtering is working in Scraper.php->filter().');
        }
        return;
    }

}
