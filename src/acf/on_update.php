<?php

/**
 * On save of options page, run.
 */
function save_yt_options()
{
    $screen = get_current_screen();
    if ($screen->id != "youtube_page_youtube_scraper") {
        return;
    }
        
    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                           Kick off the program                          │
    // └─────────────────────────────────────────────────────────────────────────┘
    $yt = new \yt\scraper;
    $yt->run();

    add_any_schedules_in();
    
    return;
}

// MUST be in a hook
add_action('acf/save_post', 'save_yt_options', 20);





function add_any_schedules_in()
{
    if (!wp_next_scheduled('yt_run_scraper')) {

        $args = array( 'scrape_id' => 'single_tester' );

        // remove old instance
        error_log('removing scheduled event');
        wp_clear_scheduled_hook( 'yt_run_scraper', $args );

        //update with new one.      
        error_log('registering scheduled event');
        wp_schedule_event(time(), '1min', 'yt_run_scraper', $args);

    }
}
