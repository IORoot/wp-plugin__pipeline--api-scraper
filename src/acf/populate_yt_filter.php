<?php

function acf_populate_yt_filter_choices($field)
{

    $filter_list = new yt\filter_list;
    $field['choices'] = $filter_list->list;
    return $field;


    // // filter type
    // if (have_rows('yt_filter_types', 'option')) {

    //     // Go through all rows of 'repeater' genimage_filters
    //     while (have_rows('yt_filter_types', 'option')): $row = the_row(true);
    //     $filter_types[$row['yt_filter_name']] = $row['yt_filter_name'];
    //     endwhile;
    // }

    // // reset choices
    // $field['choices'] = $filter_types;
    
    // // return the field
    // return $field;
}

add_filter('acf/load_field/name=yt_filter', 'acf_populate_yt_filter_choices');
