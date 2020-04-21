<?php
/**
 * Include ACF into plugin.
 * 
 */

// Create New Menu
if( function_exists('acf_add_options_page') ) {
    
    $args = array(

        'page_title' => 'YouTube Settings',
        'menu_title' => 'YouTube Settings',
        'menu_slug' => 'youtube_scraper',
        'capability' => 'manage_options',
        'position' => '100.1',
        'parent_slug' => 'edit.php?post_type=youtube',
        'icon_url' => 'dashicons-screenoptions',
        'redirect' => true,
        'post_id' => 'options',
        'autoload' => false,
        'update_button'		=> __('Update', 'acf'),
        'updated_message'	=> __("Options Updated", 'acf'),
    );

    /**
     * Create a new options page.
     */
    acf_add_options_sub_page($args);
    
}