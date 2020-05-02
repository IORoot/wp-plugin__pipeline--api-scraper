<?php


// What to do on deactivation.
register_deactivation_hook( __DIR__.'/media_scraper.php', 'yt_unregister_actions' );

function yt_unregister_actions(){

    error_log('deactivating scraper plugin');

    // remove actions
    remove_action( 'yt_run_scraper', 'yt_run_scraper_action_function' );

    // remove scheduled events in WP_CRON too.
    wp_clear_scheduled_hook( 'yt_run_scraper' );

}