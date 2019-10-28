<?php


trait transients {


    private $transient_seconds = 30;

    private $cached_status = 'notset';

    private $id = '';


    /**
     * is_this_cached
     *
     * @return void
     */
    public function is_this_cached(){ 

        // Set $this->ID to equal just the slug.
        $this->instance_id();
        // Set $this->ID to equal an MD5 combination of parameters.
        if ($this->options['allow_get_params'] == true){ $this->md5_id(); }

        // Try to get the transient.
        $transient_object = get_transient($this->id);

        // If theres a result, return it.
        if ( $transient_object !== false ){

            // Set the 'cached' status.
            $this->cached_status = 'cached';

            return $transient_object;
        }
        
        // Cache isn't set, so get live.
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
    public function get_cache_status(){     
        return $this->cached_status; 
    }

    /**
     * set_cache
     *
     * @param mixed $value
     * @return void
     */
    public function set_cache(){   

        $this->set_transient_length();

        $this->instance_id();

        // Create a unique ID based off MD5.
        if ($this->options['allow_get_params'] == true){ $this->md5_id(); }

        set_transient($this->id , $this->display_stream, $this->transient_seconds);

        return $this;
    }


    /**
     * set_transient_length
     *
     * @param mixed $seconds
     * @return void
     */
    public function set_transient_length(){

        $this->transient_seconds = $this->options['caching_length'];

        return $this;
    }


    /**
     * unique_id
     *
     * @return void
     */
    public function md5_id(){   

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

    /**
     * unique_id
     * 
     * Set the parameter ID.
     *
     * @return void
     */
    public function instance_id(){

        $this->id =  'ms__' . $this->options['instance_slug'];

        return;
    }


}