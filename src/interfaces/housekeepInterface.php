<?php

namespace yt\interfaces;

interface housekeepInterface { 

    public function wp_query($config);
    public function run();
    public function result();

}