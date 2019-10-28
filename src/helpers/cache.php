<?php

/*  ┌─────────────────────────────────────────────────────────────────────────┐ 
*   │                                                                         │░
*   │                              Cache Checker                              │░
*   │                                                                         │░
*   │ This is a separate class to the transients trait. This is so the cache  │░
*   │    can be checked WITHOUT having to instantiate a new media_scraper     │░
*   │                                 object.                                 │░
*   │                                                                         │░
*   │            Much quicker to do this that build the object up.            │░
*   │                                                                         │░
*   └─────────────────────────────────────────────────────────────────────────┘░
*    ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
*/                                                                              
class cache {


    // Passed slug
    public $option_slug;

    // Passed $GET params
    public $GET_params;

    // Options retrieved from ACF
    public $options;

    // Slug or MD5_ID identifier used for transient.
    public $id;

    // live or cached.
    public $cached_status = 'notset';

    // transient results.
    public $cache_result;


    /**
     * __construct
     * 
     * Set the slug and any $_GET parameters.
     *
     * @return void
     */
    public function __construct($slug, $GET_params = null){

        if (!$slug){ return FALSE; }
        $this->option_slug = $slug;

        // Set Parameters and defaults.
        $this->GET_params = $GET_params;

        return;
    }



    /**
     * get_options
     * 
     * Get all the options needed from ACF.
     *
     * @return void
     */
    public function get_options(){

        if( have_rows( 'scrape_instance', 'option') ) {

            while( have_rows('scrape_instance', 'option') ): the_row();

                if (get_sub_field('instance_slug') != $this->option_slug){ continue; }

                // Check any passed params and set defaults if not set.
                if (!isset($this->GET_params['mso'])){ $this->GET_params['mso'] = false; } else {$this->GET_params['mso'] = true; }
                if (!isset($this->GET_params['mss'])){ $this->GET_params['mss'] = ''; }
                if (!isset($this->GET_params['msf'])){ $this->GET_params['msf'] = ''; }
                if (!isset($this->GET_params['msp'])){ $this->GET_params['msp'] = ''; }
                
                $this->options = array ( 
                    'instance_slug'                 => get_sub_field('instance_slug'),
                    'request_params'                => $this->GET_params['msp'],
                    'sort'                          => $this->GET_params['mss'],
                    'sort_reverse'                  => $this->GET_params['mso'],
                    'filter_script_csv'             => get_sub_field('filter_script_csv'),
                    'filter_value'                  => $this->GET_params['msf'],  
                    'allow_get_params'              => get_sub_field('allow_get_params'),           
                );

            endwhile;
        }

        if ($this->options == ''){ return false; }

        return $this;
        
    }


    /**
     * unique_id
     *
     * @return void
     */
    public function build_id(){   

        if ($this->options == ''){ echo 'Options not set. Try get_options() first.'; return false; }

        // $GET params allowed for this scraper. 
        // Therefore, only the slug is used for transient identifier.
        if ($this->options['allow_get_params']) {

            // Else, build a unique transient name based off the GET parameters
            // and options set in ACF.
            if ($this->options['sort_reverse']){ $sr = 1; } else { $sr = 0; }

            $unique_name  = $this->options['instance_slug'];
            $unique_name .= $this->options['request_params'];
            $unique_name .= $this->options['filter_script_csv'];
            $unique_name .= $this->options['filter_value'];
            $unique_name .= $this->options['sort'];
            $unique_name .= $sr;

            $this->id = 'ms__' . md5($unique_name);

            return;

        }

        $this->id = 'ms__' . $this->options['instance_slug'];

        return $this;
    }



    /**
     * check_cache
     *
     * @return void
     */
    public function check_cache(){ 

        // Get the options out of the $GET Params and ACF.
        $this->get_options();

        // Create the unique ID
        $this->build_id();

        if (!$this->id){ echo 'ID not set. Try build_id() first.'; return false; }

        // Try to get the transient.
        $this->cache_result = get_transient($this->id);
        
        // If theres a result, return it.
        if ( $this->cache_result !== false ){

            // Set the 'cached' status.
            $this->cached_status = 'cached';

            return $this->cache_result;
        }

        // Set the 'cached' status.
        $this->cached_status = 'live';

        return false;

    }


    /**
     * get_cache_status
     * 
     * Returns 'live' or 'cached'
     *
     * @return void
     */
    public function check_status(){     
        return $this->cached_status; 
    }

}