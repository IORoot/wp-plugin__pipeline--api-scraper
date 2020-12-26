<?php

add_action( 'plugins_loaded', function() {
    do_action('register_andyp_plugin', [
        'title'     => 'Pipeline - API Scraper',
        'icon'      => 'circle-double',
        'color'     => '#212121',
        'path'      => __FILE__,
    ]);
} );