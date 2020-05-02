<?php

function acf_populate_yt_scrape_schedule_choices($field)
{

    // reset choices
    $field['choices'] = array();

    // get the scrape instances
    $choices = get_field('yt_schedule_instance', 'option', true);
    
    // loop through array and add to field 'choices'
    if (is_array($choices)) {
        foreach ($choices as $choice) {
            $choice_name = $choice['yt_schedule_id'];
            $field['choices'][ $choice_name ] = $choice_name;
        }
    }

    // Add none;
    $field['choices']['none'] = 'none';

    return $field;
}

add_filter('acf/load_field/name=yt_scrape_schedule', 'acf_populate_yt_scrape_schedule_choices');
