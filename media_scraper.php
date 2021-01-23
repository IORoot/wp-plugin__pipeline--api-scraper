<?php

/*
 * @package   YouTube Scraper
 * @author    Andy Pearson <andy@londonparkour.com>
 * @copyright 2020 LondonParkour
 *
 * @wordpress-plugin
 * Plugin Name:       _ANDYP - Pipeline - API Scraper V2
 * Plugin URI:        http://londonparkour.com
 * Description:       <strong>🤖 Pipeline</strong> | <em>Pipeline > API Scraper</em> | Query APIs, add results to a CPT, output to shortcode.
 * Version:           2.0.0
 * Author:            Andy Pearson
 * Author URI:        https://londonparkour.com
 * Text Domain:       andyp-media-api-scraper
 * Domain Path:       /languages
 */

// Load this on ALL pages. 
require __DIR__.'/src/acf/options_page.php';

// Load everything else ONLY on API Scraper pages.
add_action( 'current_screen', 'media_scraper_initialise' );

function media_scraper_initialise() {
    
    $current_screen = \get_current_screen();

    if ($current_screen->id != "pipeline_page_youtube_scraper"){ return; }

    date_default_timezone_set('Europe/London'); // make sure the Scheduler is correct with time picker.

    //  ┌─────────────────────────────────────────────────────────────────────────┐
    //  │                    Register with ANDYP Plugins                          │
    //  └─────────────────────────────────────────────────────────────────────────┘
    require __DIR__.'/src/acf/andyp_plugin_register.php';
    
    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                         Use composer autoloader                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    require __DIR__.'/vendor/autoload.php';
    
    //  ┌─────────────────────────────────────────────────────────────────────────┐
    //  │                      The CPT for YouTube Videos                         │
    //  └─────────────────────────────────────────────────────────────────────────┘
    // require __DIR__.'/src/cpt/youtube_cpt.php';
    
    //  ┌─────────────────────────────────────────────────────────────────────────┐
    //  │                           The ACF Parts                                 │
    //  └─────────────────────────────────────────────────────────────────────────┘
    require __DIR__.'/src/acf/acf_init.php';
    
    //  ┌─────────────────────────────────────────────────────────────────────────┐
    //  │                           Add schedule times                            │
    //  └─────────────────────────────────────────────────────────────────────────┘
    require __DIR__.'/src/scheduler/add_schedules.php';
    
    //  ┌─────────────────────────────────────────────────────────────────────────┐
    //  │                              Actions                                    │
    //  └─────────────────────────────────────────────────────────────────────────┘
    require __DIR__.'/src/actions/reset_quota.php';
    require __DIR__.'/src/actions/action_run_scraper.php';
    
    //  ┌─────────────────────────────────────────────────────────────────────────┐
    //  │                            Shortcodes                                   │
    //  └─────────────────────────────────────────────────────────────────────────┘
    require __DIR__.'/src/shortcodes/scrape_date.php';
    
    //  ┌─────────────────────────────────────────────────────────────────────────┐
    //  │                           Plugin (De)activate                           │
    //  └─────────────────────────────────────────────────────────────────────────┘
    require __DIR__.'/deactivate.php';
    
}