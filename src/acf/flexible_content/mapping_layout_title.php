<?php

add_filter('acf/fields/flexible_content/layout_title/name=yt_mapper_instance', 'andyp_mapper_fields_flexible_content_layout_title', 10, 4);


function andyp_mapper_fields_flexible_content_layout_title( $title, $field, $layout, $i ) {
    $text = '';

    if( $text = get_sub_field('yt_mapper_id') ) {
        $title .= ': <b>' . esc_html($text) . '</b>';
    }
    
    return $title;
}