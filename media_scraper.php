<?php

/*
 * @package   YouTube Scraper
 * @author    Andy Pearson <andy@londonparkour.com>
 * @copyright 2020 LondonParkour
 * 
 * @wordpress-plugin
 * Plugin Name:       _ANDYP - YouTube API Scraper
 * Plugin URI:        http://londonparkour.com
 * Description:       Query a social media API, cache results, output to shortcode.
 * Version:           1.0.0
 * Author:            Andy Pearson
 * Author URI:        https://londonparkour.com
 * Text Domain:       andyp-media-api-scraper
 * Domain Path:       /languages
 */

set_time_limit(0);
date_default_timezone_set('UTC');

// Composer Autoloader -  mgp25 - Instagram API 
require __DIR__.'/vendor/autoload.php';

// Admin Page
require_once __DIR__.'/src/admin/acf_admin_page.php';


class media_scraper {

    /**
     * Use Traits
     * 
     * These are all the add-ons for the class.
     */
    use helpers;
    use transients;
    use retrieveOptions;


    /**
     * $result_array
     * 
     * This is an array of options for the specific instance.
     *
     * @var array
     */
    private $options = [];


    /**
     * $current_api_opject
     *
     * @var undefined
     */
    private $current_api_opject;

    /**
     * $output_array
     *
     * @var array
     */
    private $output_stream = [];

    
    /**
     * $display_stream
     *
     * @var undefined
     */
    private $display_stream;


    /**
     * $current_filter
     * 
     * Which filter to run on the $output_Stream.
     *
     * @var undefined
     */
    private $current_filter;

    /**
     * $current_sort
     * 
     * Which sorting to run on the $output_stream.
     *
     * @var undefined
     */
    private $current_sort;


    /**
     * __construct
     * 
     * Set the specific instance we will be using.
     *
     * @param mixed $row_slug
     * @return void
     */
    public function __construct($row_slug){

        if ($row_slug == ''){ echo 'No instance slug specified.'; die; }
        
        $this->row_slug = $row_slug;

    }


    /**
     * API
     * 
     * Create a new API object based on the one that's been set.
     *
     * @return void
     */
    public function api(){

        if ($this->current_api_opject == null){
            echo 'No API selected.'; return;
        }

        return $this->current_api_opject;

    }


    /**
     * set_slug
     *
     * @param mixed $slug
     * @return void
     */
    public function set_slug($slug){

        if ($slug == null){ echo 'Please specify a $slug.'; return; }

        $this->row_slug = $slug;
    }


    /**
     * obtain_data
     * 
     * This will collect the data from the current API object and set it to the output_stream.
     *
     * @return void
     */
    public function pull_results(){
        
        $this->output_stream = $this->current_api_opject->results();

        return;
    }


    /**
     * run_filters
     *
     * @param mixed $value
     * @return void
     */
    public function run_filters(){

        // If no filters set, return.
        if ( empty($this->options['filter_script_csv']) ){ return; }

        // CSV, remove whitespace.
        foreach (array_map('trim', explode(',', $this->options['filter_script_csv'])) as $filter){

            // Create new object of type filtername.
            $this->current_filter = new $filter;

            // Set the stream to the current (unfiltered) one.
            $this->current_filter->set_stream($this->output_stream);

            // IF a value has been detected via $_GET, set it.
            if (!empty($this->options['filter_value'])){
                $this->current_filter->set_value($this->options['filter_value']);
            }

            // Run its filter with the current output_Stream.
            $this->output_stream = $this->current_filter->filter();

        }

        return;
    }



    /**
     * run_filters
     *
     * @param mixed $value
     * @return void
     */
    public function run_filter_scripts(){

        // If no filters set, return.
        if ( empty($this->options['filters_scripts']) ){ return; }
        if ( $this->options['filters_scripts'][0]['filter_name'] == '' ){ return; }
        
        foreach ( $this->options['filters_scripts'] as $filter){

            // Create new object of type filtername.
            $this->current_filter = new $filter['filter_name'];

            // Set the stream to the current (unfiltered) one.
            $this->current_filter->set_stream($this->output_stream);


            // If a value has been set in the admin panel, set it.
            if (!empty($filter['filter_values']) && $filter['filter_values'] !== ''){
                $this->current_filter->set_value($filter['filter_values']);
            }

            // IF a value has been detected via $_GET, set it.
            if (!empty($this->options['filter_value'])){
                $this->current_filter->set_value($this->options['filter_value']);
            }

            // Run its filter with the current output_Stream.
            $this->output_stream = $this->current_filter->filter();

        }

        return;
    }

    /**
     * sort
     * 
     * Sorts by a field name. Default is date.
     *
     * @return void
     */
    public function sort(){

        // Set a Default.
        if ( empty($this->options['sort']) ){ return; }

        // Create new object of type sort.
        $this->current_sort = new by_field;

        // Set the field
        $this->current_sort->set_field($this->options['sort']);

        // Set the stream in the class
        $this->current_sort->set_stream($this->output_stream);

        // Set the output stream to the new reordered one.
        $this->output_stream = $this->current_sort->sort();
        
        return;

    }


    /**
     * order
     * 
     * Reverse order of output array if requested.
     *
     * @return void
     */
    public function order(){

        if ( empty($this->options['sort_reverse']) ){ return; }

        // Reverse the order of array.
        $this->output_stream = array_reverse($this->output_stream);

        return;

    }


    /**
     * display_data
     * 
     * Output the results
     *
     * @return void
     */
    public function display(){


        if (!$this->output_stream){ echo ' No items. '; return; }

        $limit = 1;
        $output = '';

        // Get the shortcode item template.
        $item_template = $this->options['shortcode_items'];

        $container = $this->before_after_curlies($this->options['shortcode_container']);

        // Echo container start.
        $output = $container[0];

        // Find out number of items.
        $count = count($this->output_stream);   

        $iteration = 0;

        // Loop through each output_stream item and build up a display item to output.
        foreach ($this->output_stream as $item){

            $iteration++;

            // Add the count value to each item 
            $item['count'] = $count;
            $item['iteration'] = $iteration;

            // replace curlies
            $item_process = $this->substitute_curlies( $item_template, $item);

            // write to output
            $output .= $item_process;

            // Only return the limit of results set.         
            if ($limit++ == $this->options['result_limit']){ break; }

        }

        // Echo container end.
        $output .= $container[1];
   
        $this->display_stream = $output;

        return $output;
    }


    
    /**
     * set_api
     * 
     * Set which API you are going to use and instatiate new object
     *
     * @param mixed $stream
     * @return void
     */
    public function set_api(){

        if ($this->options !== ''){

            switch($this->options['api_type']){

                case 'Instagram':
                    $this->current_api_opject = new instagram($this->options);
                    return;

                case 'YouTube':
                    $this->current_api_opject = new youtube($this->options);
                    return;
    
                default:
                    echo 'No API exists of that name';
                    return;
            }

        }    
        return;
    }

}

// Create shortcodes
require_once __DIR__.'/src/shortcodes/shortcodes.php';
require_once __DIR__.'/src/shortcodes/filtering_shortcode.php';

// Crontab
require_once __DIR__.'/src/admin/acf_crontab.php';

// Transient Reset
require_once __DIR__.'/src/admin/deleteTransients.php';
