<?php

namespace yt;

class api
{
    public $key;

    public $cost;

    public $domain = "https://www.googleapis.com/youtube/v3";

    public $query_string;

    public $request_url;

    public $results;
    
    public function __construct()
    {
        return $this;
    }



    public function set_api_key($api_key)
    {
        if(!$this->check_key($api_key)){return false;}
        $this->key = $api_key;
        return true;
    }

    public function set_query($string)
    {
        if(!$this->check_query($string)){return false;}
        $this->query_string = $string;
        return $this;
    }

    public function set_cost($cost = 0)
    {
        $this->cost = $cost;
        return $this;
    }

    public function run()
    {
        $this->create_url();
        return $this->make_request();
    }

    public function create_url()
    {
        if(!$this->check_url()){return false;}  
        $this->request_url = $this->domain . '/search?' . $this->query_string . "&key=" . $this->key;
        return $this;
    }

    public function make_request()
    {
        if(!$this->check_request()){return false;}

        (new e)->line('- Calling API.',1);

        try {
            $result = json_decode(wp_remote_fopen($this->request_url));
        } catch (\Exception $e) {
            (new e)->line('- \Exception calling YouTube' . $e->getMessage(),1);
            return false;
        }

        return $this->check_result($result);
    }



    public function update_quota()
    {
        while( have_rows('yt_auth_instance', 'option') ): $row = the_row(true);
            $new_quota = $row['yt_api_quota'] - $this->cost;
            update_sub_field('yt_api_quota', $new_quota, 'option');
        endwhile;

        return;
    }

    
    // ┌─────────────────────────────────────────────────────────────────────────┐ 
    // │                                                                         │░
    // │                                                                         │░
    // │                                 CHECKS                                  │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


    public function check_result($result){

        if (isset($result->error)){
            (new e)->line('- ERROR Code : ' . $result->error->code ,2);
            (new e)->line('- ERROR Reason : ' . $result->error->errors[0]->reason,2);
            (new e)->line('- ERROR Message : ' . $result->error->message,2);
            return false;
        }

        $this->update_quota();
        (new e)->line('- OK Response : ' . $result->kind,2);
        (new e)->line('- Retrieved Rows : ' . $result->pageInfo->resultsPerPage,2);
        (new e)->line('- Quota Cost : 100',2);

        return $result;
    }

    public function check_query($string){

        if (!$string) {
            (new e)->line('- No search string has been supplied. Please supply query_string().',1);
            return false;
        }
        if ($string == '') {
            (new e)->line('- Search string is blank. Please supply query_string.().',1);
        }

        return true;
    }

    public function check_request(){
        if ($this->request_url == null) {
            (new e)->line('- api->request_url is blank. Please set.',1);
            return false;
        }

        return true;
    }

    public function check_key($api_key){
        if (!$api_key) {
            (new e)->line('- No API_KEY has been set.',1);
            return false;
        }
        if ($api_key == '') {
            (new e)->line('- No API_KEY is empty.',1);
            return false;
        }

        return true;
    }

    public function check_url(){

        if ($this->domain == '') {
            (new e)->line('- api->domain is blank. Please set.',1);
            return false;
        }
        if ($this->query_string == '') {
            (new e)->line('- api->query_string is blank. Please set.',1);
            return false;
        }
        if ($this->key == '') {
            (new e)->line('- api->key is blank. Please set.',1);
            return false;
        }

        return true;
    }

    

}
