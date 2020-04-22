<?php

function acf_load_post_types_field_choices( $field ) {
        
    $field['choices'] = get_post_types();

    // return the field
    return $field;
    
}

add_filter('acf/load_field/name=yt_import_post_type', 'acf_load_post_types_field_choices');