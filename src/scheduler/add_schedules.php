<?php

function yt_create_schedules($schedules){

    $schedules = [
        '1min'    => [ 'interval' => 60,      'display' => '1 Minute' ],
        '2mins'   => [ 'interval' => 120,     'display' => '2 Minutes' ],
        '3mins'   => [ 'interval' => 180,     'display' => '3 Minutes' ],
        '5mins'   => [ 'interval' => 300,     'display' => '5 Minutes' ],
        '10mins'  => [ 'interval' => 600,     'display' => '10 Minutes' ],
        '30mins'  => [ 'interval' => 1800,    'display' => '30 Minutes' ],
        '1hour'   => [ 'interval' => 7200,    'display' => '1 Hour' ],
        '2hours'  => [ 'interval' => 7200,    'display' => '2 Hours' ],
        '3hours'  => [ 'interval' => 10800,   'display' => '3 Hours' ],
        '4hours'  => [ 'interval' => 14400,   'display' => '4 Hours' ],
        '6hours'  => [ 'interval' => 21600,   'display' => '6 Hours' ],
        '12hours' => [ 'interval' => 43200,   'display' => '12 Hours' ],
        '2days'   => [ 'interval' => 172800,  'display' => '2 days' ],
        '3days'   => [ 'interval' => 259200,  'display' => '3 days' ],
        '1week'   => [ 'interval' => 604800,  'display' => '1 week' ],
        '2weeks'  => [ 'interval' => 1209600, 'display' => '2 weeks' ],
        '4weeks'  => [ 'interval' => 2419200, 'display' => '4 weeks' ],
        '1month'  => [ 'interval' => 2620800, 'display' => '1 month' ],
        '3months' => [ 'interval' => 7862400, 'display' => '3 months' ],
    ];

    return $schedules;
}

/**
 * This being a filter, it'll be constantly run every minute.
 * this will add in these new schedules every minute.
 */
add_filter( 'cron_schedules', 'yt_create_schedules');