<?php

namespace yt;

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
        set_time_limit(600); // 10 mins - apache Timeout = 300 (5 mins)

        (new \yt\e)->clear();

        $this->options = new options;

        return;
    }



    public function run()
    {

        // loop over each scrape instance.
        foreach ($this->options->scrape as $this->_scrape_key => $value) {

            if ($this->options->sidebar['saveonly']) {
                $this->saveonly_mode();
                continue;
            }
            
            // has this scrape been enabled?
            if ($this->options->scrape[$this->_scrape_key]['yt_scrape_group']['yt_scrape_enabled'] != true) {
                continue;
            }

            // run it.
            $this->process_single_scrape();
        }

        return;
    }


    public function run_scrape_instance($scrape_id)
    {
        error_log('Scheduled scrape instance running - ' . $scrape_id);

        // loop over each scrape instance.
        foreach ($this->options->scrape as $this->_scrape_key => $value) {
            
            // Does this match the scrape_id given?
            if ($this->options->scrape[$this->_scrape_key]['yt_scrape_group']['yt_scrape_id'] != $scrape_id) {
                continue;
            }

            // has this scrape been enabled?
            if ($this->options->scrape[$this->_scrape_key]['yt_scrape_group']['yt_scrape_enabled'] != true) {
                continue;
            }

            // run it.
            $this->process_single_scrape();
        }

        $this->after_housekeep();

        return;
    }



    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │░
    // │                                                                         │░
    // │                                 PRIVATE                                 │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    // ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    public function process_single_scrape()
    {
        (new \yt\l)->go('global');
        (new \yt\l)->go($this->options->scrape[$this->_scrape_key]['yt_scrape_group']['yt_scrape_id']);
        (new \yt\e)->line(date("M,d,Y h:i:s A") .' RUNNING scrape - '.$this->options->scrape[$this->_scrape_key]['yt_scrape_group']['yt_scrape_id']);

        // Run 'before' scrape
        $this->before_housekeep();

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

        // Post Actions
        $this->actions();

        // run any 'after scrape' tasks
        $this->after_housekeep();

        return;
    }


    

    public function saveonly_mode()
    {

        // has this scrape been enabled?
        if ($this->options->scrape[$this->_scrape_key]['yt_scrape_group']['yt_scrape_enabled'] != true) {
            return;
        }

        // Run any housekeeping.
        $this->before_housekeep();

        // Add any new schedule into WP_CRON
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

        // Create new object.
        $this->api = new api;
        
        (new \yt\e)->line('[ Auth ] : '.$this->options->scrape[$this->_scrape_key]['yt_scrape_auth']['yt_auth_id']);
        (new \yt\e)->line('- Quota : '. $this->options->scrape[$this->_scrape_key]['yt_scrape_auth']['yt_api_quota'], 1);
        (new \yt\e)->line('[ Search ] : '.$this->options->scrape[$this->_scrape_key]['yt_scrape_search']['yt_search_id']);

        // Set the API Key.
        // Scrape Instance -> auth array -> api key
        $this->api->set_api_key($this->options->scrape[$this->_scrape_key]['yt_scrape_auth']['yt_api_key']);

        // Set the API Username.
        // Scrape Instance -> auth array -> api username / project name
        $this->api->set_api_username($this->options->scrape[$this->_scrape_key]['yt_scrape_auth']['yt_api_project_name']);

        // Pass all of the available substitutions into
        // the class so we can do some swapping.
        $this->api->set_substitutions($this->options->substitutions);
        
        // Instead of passing each thing individually, just pass the array of
        // config details instead. This means that any new parameters will
        // automatically be passed through.
        $this->api->set_search_config($this->options->scrape[$this->_scrape_key]['yt_scrape_search']);

        // Get the YouTube results and add to scrape array.
        $this->options->scrape[$this->_scrape_key]['yt_scrape_response'] = $this->api->run();

        (new \yt\r)->new('search', $this->options->scrape[$this->_scrape_key]['yt_scrape_response']->items[0]);
        unset($this->api);

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
        $this->filter = new filter;

        (new \yt\e)->line('[ Filter ] : '.$this->options->scrape[$this->_scrape_key]['yt_scrape_filter']['yt_filter_id']);

        // do some checks.
        if ($this->has_response() == false) {
            return;
        }

        // This is the group of filters to
        // perform on the results of the API response.
        $this->filter->set_filter_group($this->options->scrape[$this->_scrape_key]['yt_scrape_filter']);

        // We also need the response from the API
        // This is so we can perform all of the filters on them
        $this->filter->set_item_collection($this->options->scrape[$this->_scrape_key]['yt_scrape_response']);

        // once everything is set, run it.
        // Then add the response of the filtering into the scrape
        // object.
        $this->options->scrape[$this->_scrape_key]['yt_scrape_filtered'] = $this->filter->run();

        (new \yt\r)->new('filter', $this->options->scrape[$this->_scrape_key]['yt_scrape_filtered']->items[0]);
        unset($this->filter);

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
        $this->mapper = new mapper;

        (new \yt\e)->line('[ Mapper ] : '.$this->options->scrape[$this->_scrape_key]['yt_scrape_mapper']['yt_mapper_id']);

        // Do some checks
        if ($this->has_response() == false) {
            return;
        }
        if ($this->has_filtered() == false) {
            return;
        }
        if ($this->options->scrape[$this->_scrape_key]['yt_scrape_mapper']['yt_mapper_id'] == 'none') {
            return;
        }

        // Pass in the search ID
        // this is used to substitute any {{search_id}}_Index
        // instances for destination fields.
        $this->mapper->set_search_id($this->options->scrape[$this->_scrape_key]['yt_scrape_search']['yt_search_id']);

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
        // (new \yt\r)->clear('mapper');
        (new \yt\r)->new('mapper', $this->options->scrape[$this->_scrape_key]['yt_scrape_mapped'][0]);

        unset($this->mapper);

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
        $this->importer = new import;

        if ($this->has_response() == false) {
            return;
        }
        if ($this->options->scrape[$this->_scrape_key]['yt_scrape_import'] == 'none') {
            return;
        }

        (new \yt\e)->line('[ Import ] : '.$this->options->scrape[$this->_scrape_key]['yt_scrape_import']['yt_import_id']);

        $this->add_term();
        $this->add_posts();

        (new \yt\r)->new('import', $this->options->scrape[$this->_scrape_key]['yt_scrape_imported'][0]);
        unset($this->importer);
        
        return $this;
    }


    public function add_term()
    {
        $scrape_taxonomy    = $this->options->scrape[$this->_scrape_key]['yt_scrape_import']['yt_import_taxonomy_type'];
        $term_name          = $this->options->scrape[$this->_scrape_key]['yt_scrape_search']['yt_search_id'];
        $term_desc          = $this->options->scrape[$this->_scrape_key]['yt_scrape_search']['yt_search_description'];
        
        (new \yt\e)->line('- Add_Term : '.$scrape_taxonomy, 1);

        $this->importer->add_term($scrape_taxonomy, $term_name, $term_desc);

        return;
    }

    public function add_posts()
    {
        $post_type  = $this->options->scrape[$this->_scrape_key]['yt_scrape_import']['yt_import_post_type'];
        $collection = $this->options->scrape[$this->_scrape_key]['yt_scrape_mapped'];

        (new \yt\e)->line('- Add_Posts : '.$post_type, 1);
        
        $this->options->scrape[$this->_scrape_key]['yt_scrape_imported'] = $this->importer->add_posts($post_type, $collection);
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
        $this->scheduler = new scheduler;

        (new \yt\e)->line('[ Scheduler ] : '.$this->options->scrape[$this->_scrape_key]['yt_scrape_schedule']['yt_schedule_id']);

        if ($this->options->scrape[$this->_scrape_key]['yt_scrape_schedule']['yt_schedule_id'] == 'none') {
            return;
        }

        $this->scheduler->set_scrape_id($this->options->scrape[$this->_scrape_key]['yt_scrape_group']['yt_scrape_id']);

        $this->scheduler->set_schedule_id($this->options->scrape[$this->_scrape_key]['yt_scrape_schedule']['yt_schedule_id']);

        $this->scheduler->set_schedule_time($this->options->scrape[$this->_scrape_key]['yt_scrape_schedule']['yt_schedule_starts']);

        $this->scheduler->set_schedule_repeat($this->options->scrape[$this->_scrape_key]['yt_scrape_schedule']['yt_schedule_repeat']);

        $this->scheduler->run();

        return;
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │░
    // │                                                                         │░
    // │                                HOUSEKEEP                                │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    public function before_housekeep()
    {
        $this->housekeeping('before');
        return;
    }


    public function after_housekeep()
    {
        $this->housekeeping('after');
        $this->scheduler_housekeeping();
        return;
    }


    public function housekeeping($when)
    {
        $housekeep = new housekeep();
        $housekeep->set_when($when);
        $housekeep->set_options($this->options->scrape[$this->_scrape_key]['yt_scrape_housekeep']);
        $housekeep->run();
        unset($housekeep);
    }

    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │░
    // │                                                                         │░
    // │                                 CLEANUPS                                │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    public function scheduler_housekeeping()
    {
        $this->scheduler = new scheduler;

        // Need to do some housekeeping here.
        // These must run every time, because nothing will run if the ENABLED
        // is off on the scrape.
        foreach ($this->options->scrape as $this->_scrape_key => $value) {
            $this->remove_any_schedules_for_disabled_scrapes();
            $this->remove_any_schedules_for_disabled_schedules();
        }
        
        unset($this->scheduler);
    }

    public function remove_any_schedules_for_disabled_scrapes()
    {
        // has this scrape been disabled?
        if ($this->options->scrape[$this->_scrape_key]['yt_scrape_group']['yt_scrape_enabled'] != true) {
            $this->scheduler->set_scrape_id($this->options->scrape[$this->_scrape_key]['yt_scrape_group']['yt_scrape_id']);
            $this->scheduler->remove_schedule();
        }

        return;
    }


    public function remove_any_schedules_for_disabled_schedules()
    {
        // has this scrape been disabled?
        if ($this->options->scrape[$this->_scrape_key]['yt_scrape_schedule']['yt_schedule_id'] == 'none') {
            $this->scheduler->set_scrape_id($this->options->scrape[$this->_scrape_key]['yt_scrape_group']['yt_scrape_id']);
            $this->scheduler->remove_schedule();
        }

        return;
    }

    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │░
    // │                                                                         │░
    // │                                 ACTIONS                                 │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    public function actions()
    {
        do_action('yt_action_post_process', $this->options->scrape[$this->_scrape_key]);
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
        if (isset($this->options->scrape[$this->_scrape_key]['yt_scrape_response']->error)) {
            (new \yt\e)->line('- There is no response from API.', 1);
            return false;
        }
        return true;
    }


    public function has_filtered()
    {
        if (!isset($this->options->scrape[$this->_scrape_key]['yt_scrape_filtered'])) {
            (new \yt\e)->line('- There is no filtered array results to map and import.', 1);
            return false;
        }

        return true;
    }
}
