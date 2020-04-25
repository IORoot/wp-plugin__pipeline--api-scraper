<?php

function acf_populate_yt_filter_choices($field)
{

    $filter_list = new yt\filter_list;
    $field['choices'] = $filter_list->list;
    return $field;

}

add_filter('acf/load_field/name=yt_filter', 'acf_populate_yt_filter_choices');
