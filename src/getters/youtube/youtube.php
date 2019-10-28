<?php

class youtube {

    /**
     * Load Traits
     */
    use helpers;
    use transients;

    /**
     * $options
     *
     * @var undefined
     */
    private $options;


    /**
     * $api_object
     *
     * @var undefined
     */
    private $api_object;


    /**
     * $current_request
     *
     * @var undefined
     */
    private $current_request;


    /**
     * $output_array
     * 
     * Holds the formatted results from response() in an array to be called.
     *
     * @var undefined
     */
    private $output_array;



    /**
     * __construct
     *
     * @return void
     */
    public function __construct($options){
        $this->options = $options;
    }

    /**
     * login
     *
     * @return void
     */
    public function login() {
        // ping youtube to see we're online.
        if (!wp_remote_fopen("https://youtube.com")){
            echo 'No connection to YouTube.com';
            return false;
        }

        // No login required. Using the API Key to make requests instead.
        return $this;

    }

    /**
     * request
     *
     * @return void
     */
    public function request() {

        // Create a new object based of the class name defined in the options.
        $this->current_request = new $this->options['request_script'];

        // Use the request method from that class - 
        $this->current_request->request(
            $this->api_object, 
            $this->options['request_params'], 
            $this->options['api_password']
        );

        return;
    }

    /**
     * response
     *
     * @return void
     */
    public function response() {

        // Use the request method from that class and set the output_array.
        $this->output_array = $this->current_request->response();

        return;

    }

    /**
     * results
     *
     * @return void
     */
    public function results() {

        // If this has been set by 'response', should contain array of values.
        return $this->output_array;

    }


    /**
     * set_options
     *
     * @param mixed $options
     * @return void
     */
    public function set_options($options){

        $this->options = $options;

    }

}