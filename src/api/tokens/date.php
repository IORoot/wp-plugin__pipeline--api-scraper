<?php

namespace yt\token;

use yt\interfaces\tokenInterface;

class date implements tokenInterface
{

    public $config = '-7 days';

    public $input;

    public function config($config){
        $this->config = $config;
    }

    public function in($input){
        $this->input = $input;
    }

    public function out(){

        $DT = new \DateTime($this->config);
        $out = $DT->format(\DateTime::ATOM);
        return urlencode($out);
    }

}