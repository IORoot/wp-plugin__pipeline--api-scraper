<?php


trait retrieveOptions {

    /**
     * $option_slug
     * 
     * This is the name of the repeater rows, as defined in ACF.
     *
     * @var undefined
     */
    private $option_slug  = 'scrape_instance';

    /**
     * $row_slug
     * 
     * This is the Instance we will use, specified by the 'slug' field.
     *
     * @var undefined
     */
    private $row_slug;

    /**
     * get_options_array
     *
     * @return void
     */
    public function get_options_array(){

        if( have_rows( $this->option_slug, 'option') ) {

            while( have_rows($this->option_slug, 'option') ): the_row();

                if (get_sub_field('instance_slug') != $this->row_slug){ continue; }

                $this->options = array ( 
                    'instance_slug'                 => get_sub_field('instance_slug'),
                    'instance_description'          => get_sub_field('instance_description'),
                    'api_type'                      => get_sub_field('api_type'),
                    'api_username'                  => get_sub_field('api_username'),
                    'api_password'                  => get_sub_field('api_password'),
                    'request_script'                => get_sub_field('request_script'),
                    'request_params'                => get_sub_field('request_params'),
                    'result_limit'                  => get_sub_field('result_limit'),
                    'shortcode_name'                => get_sub_field('shortcode_name'),
                    'shortcode_container'           => get_sub_field('shortcode_container'),
                    'shortcode_items'               => get_sub_field('shortcode_items'),
                    'caching_length'                => get_sub_field('caching_length'),
                    'allow_get_params'              => get_sub_field('allow_get_params'),
                    'sort'                          => '',
                    'sort_reverse'                  => false,
                    'filter_script_csv'             => get_sub_field('filter_script_csv'),
                    'filter_value'                  => '',
                    'filters_scripts'               => get_sub_field('filters_scripts'),
                );

            endwhile;
        }

        return $this->options;

    }


    /**
     * set_option
     *
     * @param mixed $option
     * @param mixed $value
     * @return void
     */
    public function set_option($params){

        if (!$params){  return 'Error. Please set both option and value.'; }

        foreach ($params as $key => $value){
            $this->options[$key] = $value;
        }

        return;
    
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