<?php

namespace yt;


class cron
{

    public function __construct()
    {
        return;
    }

}


function start_yt_crontab(){
    new \yt\cron;
}

// Only start AFTER ACF plugins are loaded.
// This is because this will load immediately otherwise and crash ACF 
// (which wouldn't have loaded yet).
add_action( 'plugins_loaded', 'start_yt_crontab' );