<?php


/**
 * 1. Repeat a scrape on crontab.
 * 2. create a real cronjob - https://tommcfarlin.com/wordpress-cron-jobs/
 *    every minute =        * * * * * wget -q -O - http://dev.londonparkour.com/wp-cron.php?doing_wp_cron
 * 
 *   ┌─────────────────────────────────────────────────────────────────────────┐ 
 *   │                                                                         │░
 *   │ Scheduler                                                               │░
 *   │                                                                         │░
 *   │ Creates entries in the schedule array.                                  │░
 *   │ These entries specify different interval amounts you can select when    │░
 *   │ creating a WP Event.                                                    │░
 *   │                                                                         │░
 *   └─────────────────────────────────────────────────────────────────────────┘░
 *    ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
 * 
 *   ┌─────────────────────────────────────────────────────────────────────────┐ 
 *   │                                                                         │░
 *   │ WP Event                                                                │░
 *   │                                                                         │░
 *   │ A pseudo-crontab that only runs when there is a visit to the website.   │░
 *   │ An action hook is created to run when the event is run.                 │░
 *   │                                                                         │░
 *   │                                                                         │░
 *   └─────────────────────────────────────────────────────────────────────────┘░
 *    ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
 * 
 *   ┌─────────────────────────────────────────────────────────────────────────┐ 
 *   │                                                                         │░
 *   │ Action Hook                                                             │░
 *   │                                                                         │░
 *   │ An action hook is registered with the name of the MediaScraper          │░
 *   │ instance.                                                               │░
 *   │ This hook will run the instance shortcode if triggered. (Which it will, │░
 *   │ via the WP Event).                                                      │░
 *   │                                                                         │░
 *   └─────────────────────────────────────────────────────────────────────────┘░
 *    ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
 * 
 *   crontab  -->  WP Event  -->  Action Hook  -->  do_shortcode()  -->  check transient  -->  refresh.
 * 
 */


class mediaScraperCrontab {


    /**
     * $events
     *
     * @var array
     */
    public $events = [];


    public $scheduled_times = [];



    /**
     * __construct
     *
     * @return void
     */
    public function __construct(){

        // Get the supplied options from ACF.
        $this->get_crontab_options();

        // Get any custom schedules.
        $this->get_scheduler_options();

        // Create 1min and 30sec
        $this->add_new_schedules();

        // Create the media-scraper_cron hook
        $this->register_action();

        // Schedule any events
        $this->schedule_events();

        // Disable any events
        $this->unschedule_events();
    }



    /**
     * get_crontab_options
     *
     * @return void
     */
    public function get_crontab_options(){

        if( have_rows( 'api_crontab_entry', 'option') ) {

            while( have_rows('api_crontab_entry', 'option') ): the_row();

                $entry = array ( 
                    'api_crontab_instance'   => get_sub_field('api_crontab_instance'),
                    'api_crontab_schedule'   => get_sub_field('api_crontab_schedule'),
                    'api_crontab_disabled'   => get_sub_field('api_crontab_disabled'),
                );

                array_push($this->events, $entry);

            endwhile;
        }

        return $this->events;

    }




    //  ┌─────────────────────────────────────────────────────────────────────────┐ 
    //  │                                                                         │░
    //  │                               Scheduler.                                │░
    //  │ Add new time intervals to Wordpress, so we can use them on the crontab. │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
    /**
     * get_crontab_options
     *
     * @return void
     */
    public function get_scheduler_options(){

        if( have_rows( 'schedule_times', 'option') ) {

            while( have_rows('schedule_times', 'option') ): the_row();

                $entry = array ( 
                    'schedule_slug'         => get_sub_field('schedule_slug'),
                    'schedule_interval'     => get_sub_field('schedule_interval'),
                    'schedule_display_name' => get_sub_field('schedule_display_name'),
                );

                array_push($this->scheduled_times, $entry);

            endwhile;
        }

        return $this->scheduled_times;

    }


    /**
     * add_30sec_filter
     * 
     * Wordpress defines a bunch of named schedule amounts. This runs a filter 
     * to add a new one called '30secs' by passing it to the create_30secs_schedule method.
     *
     * @return void
     */
    public function add_new_schedules(){
        add_filter( 'cron_schedules', array($this, 'create_schedules') );

        return;
    }


    /**
     * create_schedules
     * 
     * Add in the '30secs' interval into the schedule array.
     *
     * @param mixed $schedules
     * @return void
     */
    public function create_schedules($schedules){

        // // Adds '30secs' to the existing schedules.
        // $schedules['30secs'] = array( 'interval' => 30, 'display' => '30 Seconds' );
        // $schedules['1min'] = array( 'interval' => 30, 'display' => '1 Minute' );

        foreach($this->scheduled_times as $entry){
            $schedules[$entry['schedule_slug']] = array( 
                'interval' => $entry['schedule_interval'], 
                'display' => $entry['schedule_display_name'] 
            );
        }
        
        return $schedules;
    }


    //  ┌─────────────────────────────────────────────────────────────────────────┐ 
    //  │                                                                         │░
    //  │                                 Events.                                 │░
    //  │      Create (pseudo crontab)events in wordpress to regularly run.       │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
    /**
     * schedule_event
     * 
     * What do you want to run? and how often?
     * 
     * Check if event exists and create it to run the media-scraper_action action
     * hook every 1 minute. Best to use a hook, rather than a normal function
     * beecause you can extend the hook as much as you like.
     * 
     * @return void
     */
    public function schedule_events(){

        foreach($this->events as $event){

            $instance = 'ms__' . $event['api_crontab_instance'];
            $schedule = $event['api_crontab_schedule'];
            $disabled = $event['api_crontab_disabled'];
    
            // If not already scheduled and NOT disabled.
            if (! wp_next_scheduled ( $instance ) && $disabled !== true) {
                wp_schedule_event(time(), $schedule, $instance);
            }
        }

        return;
    }



    /**
     * unschedule_event
     *
     * @return void
     */
    public function unschedule_events(){

        foreach($this->events as $event){

            $instance = 'ms__' . $event['api_crontab_instance'];
            $disabled = $event['api_crontab_disabled'];
    
            // If scheduled and IS disabled.
            if (wp_next_scheduled($instance) && $disabled == true) {
                wp_clear_scheduled_hook($instance);
            }
        }

    }



 


    //  ┌─────────────────────────────────────────────────────────────────────────┐ 
    //  │                                                                         │░
    //  │                              Action Hooks.                              │░
    //  │         Create hooks to run when the (pseudo-crontab) executes.         │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
    /**
     * create_action
     * 
     * Register an action that you can hook into and run.
     *
     * @return void
     */
    public function register_action(){

        foreach($this->events as $event){

            if ($event['api_crontab_disabled'] !== true) {

                add_action( 'ms__' . $event['api_crontab_instance'],  array($this, 'runShortcodes') );

            }

        }
    }


    /**
     * This is the function to run.
     * It will run the shortcode specified.
     */
    public function runShortcodes(){

        foreach($this->events as $event){
            
            if( $event['api_crontab_disabled'] !== true){

                error_log( print_r( 'MediaScraper: instance: '. $event['api_crontab_instance'], true ) );
                do_shortcode('[media_scrapper slug="'. $event['api_crontab_instance'] .'"]');

            }
            
        }

    }

    
}


// Only start AFTER ACF plugins are loaded.
// This is because this will load immediately otherwise and crash ACF 
// (which wouldn't have loaded yet).
add_action( 'plugins_loaded', 'start_mediaScraperCrontab' );

function start_mediaScraperCrontab(){
    new mediaScraperCrontab;
    return;
}
