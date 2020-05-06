<?php

function acf_populate_yt_transform_catalog_choices($value)
{

    $value = [];
    $transform_list = new yt\transform_list;
    $list = $transform_list->catalog;

    foreach($list as $transform)
    {
        $entry = [
            "field_5ea70988d96a2" => $transform['name'],
            "field_5ea70988d96a3" => $transform['description'],
            "field_5ea70988d96a4" => $transform['parameters'],
        ];
    
        $value[] = $entry;
    }

    return $value;

}

add_filter('acf/load_value/name=yt_transform_catalog', 'acf_populate_yt_transform_catalog_choices');

