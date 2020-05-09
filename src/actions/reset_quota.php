<?php

function yt_reset_quotas()
{

    if (have_rows('yt_auth_instance', 'option')) {

        // Go through all rows of 'repeater' genimage_filters
        while (have_rows('yt_auth_instance', 'option')): 
            
            the_row(true);

            update_sub_field('yt_api_quota', 10000);

            error_log('Daily Reseting of all quotas in Scraper :');

        endwhile;
    }

    return;
}

add_action( 'yt_reset_quotas', 'yt_reset_quotas', 10, 0 );

if (! wp_next_scheduled('yt_reset_quotas')){
    wp_clear_scheduled_hook('yt_reset_quotas');
    wp_schedule_event(time(), '1hour', 'yt_reset_quotas');
}



// NOTE : if action changes, you need to remove it, then re-add it.
// error_log('remove_action yt_run_scraper');
// remove_action( 'yt_reset_quotas', 'yt_reset_quotas' );