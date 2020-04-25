<?php

function acf_populate_yt_import_post_type_choices($field)
{
    $field['choices'] = get_post_types();

    return $field;
}

add_filter('acf/load_field/name=yt_import_post_type', 'acf_populate_yt_import_post_type_choices');
