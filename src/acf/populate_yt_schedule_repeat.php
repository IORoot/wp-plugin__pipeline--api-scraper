<?php

function acf_populate_yt_schedule_repeat_choices( $field ) {
    
    $field['choices'] = [];

    $schedules = wp_get_schedules();

    foreach ($schedules as $key => $value)
    {
        $field['choices'][$key] = $value['display'];
    }

    $field['choices']['none'] = "None";

    return $field;
}

add_filter('acf/load_field/name=yt_schedule_repeat', 'acf_populate_yt_schedule_repeat_choices');