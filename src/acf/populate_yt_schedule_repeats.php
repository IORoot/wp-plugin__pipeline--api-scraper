<?php

function acf_populate_yt_schedule_repeats_choices($field)
{

    // reset choices
    $field['choices'] = array();

    $schedules = wp_get_schedules();

    // Remove any ones that shouldn't be in there.
    unset($schedules['30secs']);

    // Add none;
    $field['choices']['none'] = 'None';

    if (is_array($schedules)) {
        foreach ($schedules as $schedule) {
            $schedule_name = $schedule['display'];
            $field['choices'][ $schedule_name ] = $schedule_name;
        }
    }



    return $field;

}

add_filter('acf/load_field/name=yt_schedule_repeats', 'acf_populate_yt_schedule_repeats_choices');
