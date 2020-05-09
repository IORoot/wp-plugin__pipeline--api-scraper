<?php

// Define path and URL to the ACF plugin.
define( 'MY_ACF_PATH', plugin_dir_path(__FILE__) . '../../vendor/advanced-custom-fields/' );
define( 'MY_ACF_URL', plugin_dir_url(__FILE__) . '../../vendor/advanced-custom-fields/' );

// Include the ACF plugin.
include_once( MY_ACF_PATH . 'acf.php' );

// Customize the url setting to fix incorrect asset URLs.
add_filter('acf/settings/url', 'my_acf_settings_url');
function my_acf_settings_url( $url ) {
    return MY_ACF_URL;
}