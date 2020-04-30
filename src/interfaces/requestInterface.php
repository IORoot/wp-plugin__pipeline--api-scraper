<?php

namespace yt\interfaces;

interface requestInterface { 

    public function config($config);
    public function request();
    public function response();
    public function get_cost();

}