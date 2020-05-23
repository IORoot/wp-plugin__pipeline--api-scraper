<?php

namespace yt\itunes;

class response
{

    public $response;

    public $result = true;



    public function __construct()
    {
        return $this;
    }


    
    public function is_errored($response)
    {

        $this->response = $response;

        $this->check_there_are_no_response_errors();

        return $this->result;
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │░
    // │                                                                         │░
    // │                                PRIVATE                                  │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    private function check_there_are_no_response_errors()
    {
        return;
    }


}