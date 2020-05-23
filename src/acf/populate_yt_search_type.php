<?php

function acf_populate_yt_search_type_choices( $field ) {
    
    $request_list = new yt\request_list;
    $field['choices'] = $request_list->list;
    return $field;
    
}

add_filter('acf/load_field/name=yt_search_type', 'acf_populate_yt_search_type_choices');