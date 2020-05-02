<?php

namespace yt;

use \yt\e;
use \yt\api;
use \yt\filter as filter;
use \yt\mapper_collection as mapper;
use \yt\import;
use \yt\scheduler;
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

    private $scheduler;

    // Temporary parameters
    private $_scrape_key;


    public function __construct()
    {
        set_time_limit (600); // 10 mins - apache Timeout = 300 (5 mins)

        (new \yt\e)->clear();

        $this->options = new options;

        $this->api = new api;

        $this->filter = new filter;

        $this->mapper = new mapper;

        $this->importer = new import;

        $this->scheduler = new scheduler;

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

        $this->houseclean();

        return;
    }

    public function run_scrape_instance($scrape_id)
    {

        error_log('Scheduled scrape instance running - ' . $scrape_id);

        // loop over each scrape instance.
        foreach ($this->options->scrape as $this->_scrape_key => $value){
            
            // Does this match the scrape_id given?
            if ($this->options->scrape[$this->_scrape_key]['yt_scrape_id'] != $scrape_id){ continue; }

            // has this scrape been enabled?
            if ($this->options->scrape[$this->_scrape_key]['yt_scrape_enabled'] != true){ continue; }

            // run it.
            $this->process_single_scrape();

        }

        $this->houseclean();

        return;
    }




    public function process_single_scrape(){
        
        (new \yt\e)->line(date("M,d,Y h:i:s A") .' RUNNING scrape - '.$this->options->scrape[$this->_scrape_key]['yt_scrape_id'] );

        // Query API.
        $this->scrape_api();

        // // Filter the results returned
        $this->filter();

        // Map fields
        $this->mapper();

        // Import results into CPT
        $this->import();

        // Add new schedule into WP_CRON
        $this->schedule();

        return;
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐ 
    // │                                                                         │░
    // │                                                                         │░
    // │                                SCRAPING                                 │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


    public function scrape_api()
    {
        (new \yt\e)->line('[ Auth ] : '.$this->options->scrape[$this->_scrape_key]['yt_scrape_auth']['yt_auth_id'] );
        (new \yt\e)->line('- Quota : '. $this->options->scrape[$this->_scrape_key]['yt_scrape_auth']['yt_api_quota'],1);
        (new \yt\e)->line('[ Search ] : '.$this->options->scrape[$this->_scrape_key]['yt_scrape_search']['yt_search_id'] );

        // Set the API Key.
        // Scrape Instance -> auth array -> api key  
        $this->api->set_api_key($this->options->scrape[$this->_scrape_key]['yt_scrape_auth']['yt_api_key']);

        // Pass all of the available substitutions into 
        // the class so we can do some swapping.
        $this->api->set_substitutions($this->options->substitutions);

        // set the search string
        // Scrape Instance -> search array -> search string  
        $this->api->set_query($this->options->scrape[$this->_scrape_key]['yt_scrape_search']['yt_search_string']);

        // What type of request is this?
        $this->api->set_request_type($this->options->scrape[$this->_scrape_key]['yt_scrape_search']['yt_search_type']);

        // Add any extra parameters passed through.
        $this->api->set_extra_parameters($this->options->scrape[$this->_scrape_key]['yt_scrape_search']['yt_search_parameters']);
        
        // Get the YouTube results and add to scrape array.
        $this->options->scrape[$this->_scrape_key]['yt_scrape_response'] = $this->api->run();

        // Report last response.
        (new \yt\r)->clear('search');
        (new \yt\r)->last('search',$this->options->scrape[$this->_scrape_key]['yt_scrape_response']); 

        return;
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐ 
    // │                                                                         │░
    // │                                                                         │░
    // │                                FILTERING                                │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


    public function filter()
    {

        (new \yt\e)->line('[ Filter ] : '.$this->options->scrape[$this->_scrape_key]['yt_scrape_filter']['yt_filter_id'] );

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

        // Report last response.
        (new \yt\r)->clear('filter');
        (new \yt\r)->last('filter',$this->options->scrape[$this->_scrape_key]['yt_scrape_filtered']->items); 

        return;
    }



    // ┌─────────────────────────────────────────────────────────────────────────┐ 
    // │                                                                         │░
    // │                                                                         │░
    // │                                 MAPPING                                 │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


    public function mapper()
    {

        (new \yt\e)->line('[ Mapper ] : '.$this->options->scrape[$this->_scrape_key]['yt_scrape_mapper']['yt_mapper_id'] );

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

        // Report last response.
        (new \yt\r)->clear('mapper');
        (new \yt\r)->last('mapper',$this->options->scrape[$this->_scrape_key]['yt_scrape_mapped']); 


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
        (new \yt\e)->line('[ Import ] : '.$this->options->scrape[$this->_scrape_key]['yt_scrape_import']['yt_import_id'] );

        $this->add_term();
        $this->add_posts();
        
        return $this;
    }


    public function add_term()
    {
        
        $scrape_taxonomy    = $this->options->scrape[$this->_scrape_key]['yt_scrape_import']['yt_import_taxonomy_type'];
        $term_name          = $this->options->scrape[$this->_scrape_key]['yt_scrape_search']['yt_search_id'];
        $term_desc          = $this->options->scrape[$this->_scrape_key]['yt_scrape_search']['yt_search_description'];
        
        (new \yt\e)->line('- Add_Term : '.$scrape_taxonomy, 1 );

        $this->importer->add_term($scrape_taxonomy, $term_name, $term_desc);

        return;
    }

    public function add_posts()
    {
        $post_type  = $this->options->scrape[$this->_scrape_key]['yt_scrape_import']['yt_import_post_type'];
        $collection = $this->options->scrape[$this->_scrape_key]['yt_scrape_mapped'];

        (new \yt\e)->line('- Add_Posts : '.$post_type, 1 );
        
        $this->importer->add_posts($post_type, $collection);
    }




    // ┌─────────────────────────────────────────────────────────────────────────┐ 
    // │                                                                         │░
    // │                                                                         │░
    // │                                SCHEDULER                                │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    public function schedule()
    {
        (new \yt\e)->line('[ Scheduler ] : '.$this->options->scrape[$this->_scrape_key]['yt_scrape_schedule']['yt_schedule_id'] );

        $this->scheduler->set_scrape_id($this->options->scrape[$this->_scrape_key]['yt_scrape_id']);

        $this->scheduler->set_schedule_id($this->options->scrape[$this->_scrape_key]['yt_scrape_schedule']['yt_schedule_id']);

        $this->scheduler->set_schedule_time($this->options->scrape[$this->_scrape_key]['yt_scrape_schedule']['yt_schedule_starts']);

        $this->scheduler->set_schedule_repeat($this->options->scrape[$this->_scrape_key]['yt_scrape_schedule']['yt_schedule_repeat']);

        $this->scheduler->run();

        return;
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐ 
    // │                                                                         │░
    // │                                                                         │░
    // │                                HOUSECLEAN                               │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    public function houseclean()
    {
        // Need to do some housecleaning here.
        // These must run every time, because nothing will run if the ENABLED
        // is off on the scrape.
        //
        // 1. Stop any scheduled jobs if they've been disabled.
        // 2. Remove any older posts.
        // 3. Remove any older images.

        // loop EVERY scrape instance.
        foreach ($this->options->scrape as $this->_scrape_key => $value){
            
            $this->remove_any_schedules_for_disabled_scrapes();
        }

        return;
    }



    public function remove_any_schedules_for_disabled_scrapes()
    {
        // has this scrape been disabled?
        if ($this->options->scrape[$this->_scrape_key]['yt_scrape_enabled'] != true)
        {
            $this->scheduler->set_scrape_id($this->options->scrape[$this->_scrape_key]['yt_scrape_id']);
            $this->scheduler->remove_schedule();
        }

        return;
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
            (new \yt\e)->line('- There is no response from YouTube.', 1 );
        }
        return;
    }


    public function has_filtered()
    {
        if ($this->options->scrape[$this->_scrape_key]['yt_scrape_filtered'] == null) {
            (new \yt\e)->line('- There is no filtered array results to map and import.', 1 );
        }
        return;
    }

}
