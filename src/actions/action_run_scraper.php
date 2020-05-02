<?php

/**
 * This ACTION needs to be declared BEFORE you can
 * use wp_schedule_event (below)
 */
function yt_run_scraper_action_function($scrape_instance)
{
    error_log('scheduled running of action yt_run_scraper_action_function for scrape_instance: '.$scrape_instance);
    if ($scrape_instance == null || !is_array($scrape_instance) || !isset($scrape_instance['scrape_id'])){ return; }

    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                           Kick off the program                          │
    // └─────────────────────────────────────────────────────────────────────────┘
    $yt = new \yt\scraper;
    $yt->run_scrape_instance($scrape_instance['scrape_id']);

    return;
}

error_log('add_action yt_run_scraper');
add_action( 'yt_run_scraper', 'yt_run_scraper_action_function', 10, 1 );


// NOTE : if action changes, you need to remove it, then re-add it.
// error_log('remove_action yt_run_scraper');
// remove_action( 'yt_run_scraper', 'yt_run_scraper_action_function' );