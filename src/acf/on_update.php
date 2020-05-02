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
    
    return;
}

// MUST be in a hook
add_action('acf/save_post', 'save_yt_options', 20);