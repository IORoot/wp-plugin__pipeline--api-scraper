<?php

function acf_populate_yt_transform_choices($field)
{

    $transform_list = new yt\transform_list;
    $field['choices'] = $transform_list->list;
    return $field;

}

add_filter('acf/load_field/name=yt_transform', 'acf_populate_yt_transform_choices');
