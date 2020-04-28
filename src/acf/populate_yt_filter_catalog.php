<?php

function acf_populate_yt_filter_catalog_choices($value, $post_id, $field)
{

    $value = [];
    $filter_list = new yt\filter_list;
    $list = $filter_list->catalog;

    foreach($list as $filter)
    {
        $entry = [
            "field_5ea6f807764e2" => $filter['name'],
            "field_5ea6f82b764e3" => $filter['description'],
            "field_5ea6f84d764e4" => $filter['parameters'],
        ];
    
        $value[] = $entry;
    }

    return $value;

}

add_filter('acf/load_value/name=yt_filter_catalog', 'acf_populate_yt_filter_catalog_choices');

