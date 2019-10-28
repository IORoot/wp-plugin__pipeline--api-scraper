<?php

$refresh_transients = get_field('reset_transients', 'option');

// If the 'Reset All transients' is set, clear the transient cache for all essential grids.
if ($refresh_transients){
    
    /**
     *  Delete all transients
     */
    global $wpdb;

	$sql = "DELETE 
		FROM `wp_options`
		WHERE 
		`option_name` 
        LIKE 
        '%_transient_ms__%'
    ";

    //$sql = "SELECT * FROM `wp_options` WHERE  `option_name` LIKE '%_transient_ms__%'";

    $results = $wpdb->get_results( $sql );


}