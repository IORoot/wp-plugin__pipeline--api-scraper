<?php

namespace yt;

class scheduler
{

    public $scrape_id;

    public $schedule_id;
    public $schedule_time;
    public $schedule_repeat = 'no';

    public function __construct()
    {
        return $this;
    }

    public function set_scrape_id($scrape_id)
    {
        $this->scrape_id = $scrape_id;
    }

    public function set_schedule_id($schedule_id)
    {
        $this->schedule_id = $schedule_id;
    }

    public function set_schedule_time($schedule_time = null)
    {
        if ($schedule_time == null){ $schedule_time = time();}
        $this->schedule_time = $schedule_time;
    }

    public function set_schedule_repeat($schedule_repeat)
    {
        $this->schedule_repeat = $schedule_repeat;
    }


    public function run()
    {
        $this->create_schedule();
        return;
    }


    public function create_schedule()
    {

        // this isn't scheduled, exit.
        if ($this->schedule_id == 'no' ){ return; }
        // there isn't a scrape attached to this, exit.
        if ($this->scrape_id == null){ return; }

        $args = array( 'scrape_instance' => $this->scrape_id );

        if (!wp_next_scheduled('yt_run_scraper', $args)) {

            // remove old instance
            wp_clear_scheduled_hook('yt_run_scraper', $args);

            // update with new one.
            // NOTE: $args MUST be an array.
            wp_schedule_event($this->schedule_time, $this->schedule_repeat, 'yt_run_scraper', $args);

        }
    
    }

    public function remove_schedule()
    {
        $args = array( 'scrape_instance' => $this->scrape_id );
        wp_clear_scheduled_hook( 'yt_run_scraper', $args );
    }

}