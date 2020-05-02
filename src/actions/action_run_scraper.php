<?php

/**
 * This ACTION needs to be declared BEFORE you can
 * use wp_schedule_event (below)
 * 
 * Note that when using the scheduler, it expects an array to pass, that maps
 * To each argument.
 * So
 * $args('scrape_instance' => 'single_test')
 * will go into this
 * action with one argument $scrape_instance.
 */
function yt_run_scraper_action2($scrape_instance)
{
    error_log('scheduled running of action yt_run_scraper_action for $scrape_instance: '. $scrape_instance);
    
    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                           Kick off the program                          │
    // └─────────────────────────────────────────────────────────────────────────┘
    $yt = new \yt\scraper;
    $yt->run_scrape_instance($scrape_instance);

    return;
}

// error_log('add_action yt_run_scraper');
add_action( 'yt_run_scraper', 'yt_run_scraper_action2', 10, 1 );


// NOTE : if action changes, you need to remove it, then re-add it.
// error_log('remove_action yt_run_scraper');
// remove_action( 'yt_run_scraper', 'yt_run_scraper_action_function' );