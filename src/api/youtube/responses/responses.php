<?php

namespace yt\youtube;

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
        if (isset($this->response->error)) {
            (new \yt\e)->line('- ERROR Code : ' . $this->response->error->code, 2);
            (new \yt\e)->line('- ERROR Reason : ' . $this->response->error->errors[0]->reason, 2);
            (new \yt\e)->line('- ERROR Message : ' . $this->response->error->message, 2);
            $this->result = false;
            return;
        }

        (new \yt\e)->line('- OK Response : ' . $this->response->kind, 2);
        (new \yt\e)->line('- Retrieved Rows : ' . $this->response->pageInfo->resultsPerPage, 2);

        return;
    }


}