<?php


add_action('acf/init', 'scraper_acf_add_menus_init');


function scraper_acf_add_menus_init() {

// Create Parent Menu
    if (function_exists('acf_add_options_page')) {
        $argsparent = array(
        'page_title' => 'Pipeline',
        'menu_title' => 'Pipeline',
        'menu_slug' => 'pipeline',
        'capability' => 'manage_options',
        'position' => '1',
        'parent_slug' => '',
        'icon_url' => 'data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgMjQgMjQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTEyLDJBMTAsMTAgMCAwLDAgMiwxMkExMCwxMCAwIDAsMCAxMiwyMkExMCwxMCAwIDAsMCAyMiwxMkExMCwxMCAwIDAsMCAxMiwyTTEyLDRBOCw4IDAgMCwxIDIwLDEyQTgsOCAwIDAsMSAxMiwyMEE4LDggMCAwLDEgNCwxMkE4LDggMCAwLDEgMTIsNE0xMiw2QTYsNiAwIDAsMCA2LDEyQTYsNiAwIDAsMCAxMiwxOEE2LDYgMCAwLDAgMTgsMTJBNiw2IDAgMCwwIDEyLDZNMTIsOEE0LDQgMCAwLDEgMTYsMTJBNCw0IDAgMCwxIDEyLDE2QTQsNCAwIDAsMSA4LDEyQTQsNCAwIDAsMSAxMiw4WiIvPjwvc3ZnPg==',
        'redirect' => true,
        'post_id' => 'options',
        'autoload' => false,
        'update_button'		=> __('Update', 'acf'),
        'updated_message'	=> __("Options Updated", 'acf'),
    );
        acf_add_options_page($argsparent);
        acf_add_options_sub_page(
            array(
        'parent_slug'	=> 'pipeline',
        )
        );
    }

    // Create New Menu
    if (function_exists('acf_add_options_page')) {
        $args = array(

        'page_title' => '<span class="mdi mdi-toothbrush" style="color:#70579F"></span> API Scraper',
        'menu_title' => '<span class="mdi mdi-toothbrush" style="color:#70579F"></span> API Scraper',
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
}