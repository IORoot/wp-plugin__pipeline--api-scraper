<?php

function acf_populate_yt_import_taxonomy_type_choices($field)
{
    $field['choices'] = get_taxonomies();

    return $field;
}

add_filter('acf/load_field/name=yt_import_taxonomy_type', 'acf_populate_yt_import_taxonomy_type_choices');
