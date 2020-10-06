<?php



// Create Parent Menu
if( function_exists('acf_add_options_page') ) {
    
    $argsparent = array(
        'page_title' => 'Pipeline',
        'menu_title' => 'Pipeline',
        'menu_slug' => 'pipeline',
        'capability' => 'manage_options',
        'position' => '1',
        'parent_slug' => '',
        'icon_url' => 'dashicons-marker',
        'redirect' => true,
        'post_id' => 'options',
        'autoload' => false,
        'update_button'		=> __('Update', 'acf'),
        'updated_message'	=> __("Options Updated", 'acf'),
    );
	acf_add_options_page($argsparent);
	acf_add_options_sub_page(array(
        'parent_slug'	=> 'pipeline',
        )
    );
}

// Create New Menu
if (function_exists('acf_add_options_page')) {
    $args = array(

        'page_title' => 'API Scraper',
        'menu_title' => '➡️ API Scraper',
        'menu_slug' => 'youtube_scraper',
        'capability' => 'manage_options',
        'position' => '1',
        'parent_slug' => 'pipeline',
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
