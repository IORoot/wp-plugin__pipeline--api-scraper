<?php


//  ┌─────────────────────────────────────────────────────────────────────────┐ 
//  │                                                                         │░
//  │ Options                                                                 │░
//  │                                                                         │░
//  │ - msc = Cache (Skip cache)                                              │░
//  │ - msi = Instance (Pick Instance)                                        │░
//  │ - msp = Parameter (Use this value as a parameter)                       │░
//  │ - msf = Filter (Use this value for any filter specified)                │░
//  │ - mss = Sort (Sort results by this field)                               │░
//  │ - mso = Order (Sort results in reverse order)                           │░
//  │                                                                         │░
//  └─────────────────────────────────────────────────────────────────────────┘░
//   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


class shortcodes {


    /**
     * $media_scraper
     *
     * @var undefined
     */
    private $media_scraper;

    /**
     * $api
     *
     * @var undefined
     */
    private $api;

    /**
     * $cache_status
     *
     * @var undefined
     */
    public $cache_status = 'notset';

    /**
     * $cached_result
     *
     * @var undefined
     */
    private $cached_result;


    /**
     * __construct
     *
     * @return void
     */
    public function __construct(){
        add_filter('init', array( $this, 'media_scrapper_shortcode' ));
    }

    /**
     * media_scrapper_shortcode
     *
     * @return void
     */
    public function media_scrapper_shortcode() {
        return;
    }


    //  ┌─────────────────────────────────────────────────────────────────────────┐ 
    //  │                                                                         │░
    //  │                          Main Shortcode method                          │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
    
    /**
     * media_scrapper_func
     *
     * @param mixed $atts
     * @return void
     */
    public function media_scrapper_func($atts){

        extract(
            shortcode_atts(
                array(
                    // General
                    'slug' => '',
                    'params' => null,
                    'filters' => null
                ),
                $atts
            )
        );

        // Make sure there is a slug!
        if ($slug == ''){ echo 'No instance slug specified.'; return; }    

        // Check for any URL filters set.
        if ($filters !== ''){ $this->filters = explode(',', $filters); }    
    
        // Send the name of the slug to use and any GET params.
        $cache = new cache($slug, $_GET);
        $cache_result = $cache->check_cache();
        
        if (!empty($cache_result)){ 
            // Check status
            $this->cache_status = $cache->check_status();
            $this->cache_indicator();

            // return cache.
            return $cache_result; 
        } 

        // Create a new media_Scraper Object
        $this->media_scraper = new media_scraper($slug);
    
        // Get the Parameters.
        $this->get_params($params, $slug);

        // Set the API to use.s
        $this->media_scraper->set_api();
        
        // Make API call.
        $this->make_api_request();
    
        return;
    }


    /**
     * cache_indicator
     * 
     * Little indicator to show if you are seeing a cached result or not.
     *
     * @return void
     */
    public function cache_indicator(){

        $output = '<style> .cache-status{ display:inline-block; border-radius:29px; padding:0px 10px; position: absolute; top: -40px; right: 20px;}';
        $output .= '.cache-status__live { background:#E34F65;}';
        $output .= '.cache-status__cached {background:#38EF7D; color:#242424;}';
        $output .= '.cache-status p { line-height:18px; font-size:12px; }';
        $output .= '</style>';
        $output .= '<div class="cache-status cache-status__'. $this->cache_status .'"><p>'. $this->cache_status .'</p></div>';

        echo $output;

        return;

    }


    //  ┌─────────────────────────────────────────────────────────────────────────┐ 
    //  │                                                                         │░
    //  │                               Cache check                               │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
    public function check_cache($slug){
    
        $this->cached_result = $this->media_scraper->is_this_cached();

        $this->cache_status = $this->media_scraper->get_cache_status();

        // $this->cache_indicator();

        // Make sure the 'instance' is set, otherwise this will apply to all shortcodes
        // on the page. Not the targeted one.
        if ( isset($_GET["msi"]) && $_GET["msi"] == $slug){

            // Skip Cache flag set.
            // msc = media scraper cache (if set, ignore cache.)
            if ( isset($_GET["msc"])){ 
                echo 'Skipping cache flag found.'; 
                return false; 
            }

            // Return Cached result is exists.
            if ($this->cached_result !== false){
                echo $this->cached_result;
                return true;
            }

        }

        return false;
    }


    //  ┌─────────────────────────────────────────────────────────────────────────┐ 
    //  │                                                                         │░
    //  │                         Check $_GET Parameters                          │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
    /**
     * set_params_order
     * 
     * Order of importance.
     * 1. In the API Scraper dashboard page.
     * 2. As a parameter on in the shortcode.
     * 3. As a $_GET HTTP Request parameter.
     *
     * @return void
     */
    public function get_params($params, $slug){

        // Get Options from the ACF admin panel.
        $this->media_scraper->get_options_array();


        // Override Options if set on shortcode.
        if ($params){ $this->media_scraper->set_option([ 'request_params' => $params]); }
        
         // msi = media scraper instance to target
         // This MUST be set to target the correct media_scraper (if many is on the page)
        if (isset($_GET["msi"]) && $_GET["msi"] == $slug){

                // msp = media scraper parameter
                if (isset($_GET["msp"])) {
                    $this->media_scraper->set_option([ 'request_params' => htmlspecialchars($_GET["msp"])]);
                }

                // msf = media scraper filter value. Use the filter and pass this value to it. 
                //       - NOTE, this can only work with one mediascraper on the page.
                if ( isset($_GET["msf"]) ) {
                    $this->media_scraper->set_option([ 'filter_value' => htmlspecialchars($_GET["msf"])]);
                }

                //mss - sort
                if ( isset($_GET["mss"]) ) {
                    $this->media_scraper->set_option([ 'sort' => htmlspecialchars($_GET["mss"])]);
                }

                //mso - reverse order
                if ( isset($_GET["mso"]) ) {
                    $this->media_scraper->set_option(['sort_reverse' => TRUE]);
                }
        }
        return;

    }



    //  ┌─────────────────────────────────────────────────────────────────────────┐ 
    //  │                                                                         │░
    //  │                       Make an actual API Request                        │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
    /**
     * make_api_request
     *
     * @return void
     */
    public function make_api_request(){
       
        // Create new object.
        $this->api = $this->media_scraper->api();
    
        // Login ONCE!
        if ($this->api->login()) {

            // Run Request
            $this->api->request();
                
            // Grab Response
            $this->api->response();

            // Grab returned data from API.
            $this->media_scraper->pull_results();

            // If filters are specified, run them.
            $this->media_scraper->run_filter_scripts();
            
            // If filters are specified, run them.
            $this->media_scraper->sort();

            // If order has been specified to be in reverse, do it.
            $this->media_scraper->order();

            // Create Output
            echo $this->media_scraper->display();

            // set cache with result.
            $this->media_scraper->set_cache();

        }
    }



    //  ┌─────────────────────────────────────────────────────────────────────────┐ 
    //  │                                                                         │░
    //  │           Example using same $IG Object with second request.            │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
    /**
     * make_second_request
     *
     * @return void
     */
    public function make_second_request(){
    
        // Output Results as array.
        var_dump($this->api->results());
    
        // Change to another script.
        $this->media_scraper->set_slug('instance_2');
    
        // Re-get options
        $new_options = $this->media_scraper->get_options_array();
    
        // Set the Instagram object to have the new options.
        $this->api->set_options($new_options);
    
        // Make request with same object
        $this->api->request();
    
        // Grab Response
        $this->api->response();
    
        // Grab returned data from API.
        $this->media_scraper->pull_results();
    
        // Create Output
        $this->media_scraper->display();

    }

}

add_shortcode( 'media_scrapper', [ new shortcodes, 'media_scrapper_func' ] );