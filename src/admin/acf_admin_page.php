<?php

/**
 * Include ACF into plugin.
 * 
 */

  // Create Parent Menu
if( function_exists('acf_add_options_page') ) {
    
    $argsparent = array(
        'page_title' => 'ANDYP',
        'menu_title' => 'ANDYP',
        'menu_slug' => 'andyp',
        'capability' => 'manage_options',
        'position' => '99.11',
        'parent_slug' => '',
        'icon_url' => 'dashicons-screenoptions',
        'redirect' => true,
        'post_id' => 'options',
        'autoload' => false,
        'update_button'		=> __('Update', 'acf'),
        'updated_message'	=> __("Options Updated", 'acf'),
    );
	acf_add_options_page($argsparent);
	acf_add_options_sub_page(array(
        'menu_title'	=> 'AndyP Plugins',
        'parent_slug'	=> 'andyp',
        )
    );
}


if( function_exists('acf_add_options_page') ) {
    
    $args = array(

        'page_title' => 'API Scraper',
        'menu_title' => 'API Scraper',
        'menu_slug' => 'apiscraper',
        'capability' => 'manage_options',
        'position' => '105.1',
        'parent_slug' => 'andyp',
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


//  ┌─────────────────────────────────────────────────────────────────────────┐
//  │                            Load the CSS & JS                            │
//  └─────────────────────────────────────────────────────────────────────────┘

add_action( 'admin_enqueue_scripts', 'loadMediaScraperCssAndJs' );

function loadMediaScraperCssAndJs() {
    wp_register_style( 'load_media_scraper_css', plugins_url('andyp_media_scraper/src/css/admin.css') );
    wp_enqueue_style( 'load_media_scraper_css' );

    //wp_enqueue_script( 'vc_extend_media_js', plugins_url('assets/vc_c-media.js', __FILE__), array('jquery'), false, true );
}
