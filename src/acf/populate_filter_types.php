<?php

function acf_load_filter_layer_field_choices( $field ) {
    
    // reset choices
    $field['choices'] = [
        'none' => 'None',
        'strip_title' => 'Strip Title',
    ];
    
    // return the field
    return $field;
    
}

add_filter('acf/load_field/name=yt_filter', 'acf_load_filter_layer_field_choices');