<?php

class instagram {

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
     * @param mixed $options
     * @return void
     */
    public function __construct($options){

        $this->options = $options;
   
    }
    

    /**
     * login
     * 
     * Login to the instagram API
     *
     * @return void
     */
    public function login(){
        
        sleep(5);

        // Turn on Web usage.
        InstagramAPI\Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;

        // New IG Object.
        $this->api_object = new InstagramAPI\Instagram();

        // Try logging into instagram.
        try {
            $this->api_object->login(
                $this->options['api_username'], 
                $this->options['api_password']
            );
        } catch (\Exception $e) {
            echo 'InstagramAPI - Something went wrong: '.$e->getMessage()."\n";
            //exit(0);
            return false;
        }

        return $this;
    }


    /**
     * request
     *
     * Run specified script
     * 
     * @param mixed $request_script
     * @return void
     */
    public function request(){

        // Create a new object based of the class name defined in the options.
        $this->current_request = new $this->options['request_script'];

        // Use the request method from that class.
        $this->current_request->request($this->api_object, $this->options['request_params']);

        return;
    }


    /**
     * parse_data
     *
     * @return void
     */
    public function response(){

        // Use the request method from that class and set the output_array.
        $this->output_array = $this->current_request->response();

        return;
       
    }


    /**
     * obtain_data
     *
     * @return void
     */
    public function results(){

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