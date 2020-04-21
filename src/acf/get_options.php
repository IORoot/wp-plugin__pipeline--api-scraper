<?php

namespace yt;

class options {

    public $result;

    public function __construct(){
        return $this->get_all_options();
    }


    public function get_all_options(){

        $this->get_api_project_name();
        $this->get_api_key();
        
        return $this->result;
    }


    public function get_api_project_name(){
        $this->result['api_project'] = get_field('yt_api_project_name' , 'option');
    }


    public function get_api_key(){
        $this->result['api_key'] = get_field('yt_api_key' , 'option');
    }

}