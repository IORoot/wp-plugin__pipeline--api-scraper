<?php

function acf_load_tax_types_field_choices( $field ) {
        
    $field['choices'] = get_taxonomies();

    // return the field
    return $field;
    
}

add_filter('acf/load_field/name=yt_import_taxonomy_type', 'acf_load_tax_types_field_choices');