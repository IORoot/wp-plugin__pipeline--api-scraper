<?php


class getUserID {

    /**
     * $returned_stream
     * 
     * This is the objects returned from the request.
     * 
     * @var undefined
     */
    public $returned_stream = [];


    /**
     * $output_array
     *
     * @var array
     */
    private $output_array = [];

    /**
     * $classname
     * 
     * Used to populate the ACF options.
     *
     * @var string
     */
    public $classname = 'getUserID';

    //  ┌─────────────────────────────────────────────────────────────────────────┐ 
    //  │                                                                         │░
    //  │                                 Request                                 │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
    public function request($api_object, $params = null){
        
        // result always needs to be an array.
        $this->returned_stream = $api_object->people->getUserIdForName($params) ;

        return true;

    }



    //  ┌─────────────────────────────────────────────────────────────────────────┐ 
    //  │                                                                         │░
    //  │                                Response                                 │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
    public function response(){

        $new_item = array (
            'id' => $this->returned_stream,
        );

        array_push($this->output_array, $new_item);

        return $this->output_array;

    }
    

}