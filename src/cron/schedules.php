<?php

namespace yt;

/**
 * 
 * Worth installing wp-control to make sure the cron schedules are installed.
 * 
 */
class schedules
{
    
    public $schedules = [
        '1min'    => [ 'interval' => 60,      'display' => '1 Minute' ],
        '10mins'  => [ 'interval' => 600,     'display' => '10 Minutes' ],
        '30mins'  => [ 'interval' => 1800,    'display' => '30 Minutes' ],
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
    

    public function __construct()
    {
        return;
    }
    
    public function run()
    {
        add_filter( 'cron_schedules', array($this, 'create_schedules') );
        return;
    }

    /**
     * create_schedules
     * 
     * Add in new schedules
     *
     * @param mixed $schedules
     * @return void
     */
    public function create_schedules($schedules){

        $schedules = array_merge($schedules,$this->schedules);
        
        return $schedules;
    }
}