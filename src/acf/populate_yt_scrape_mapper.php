<?php

function acf_populate_yt_scrape_mapper_choices( $field ) {
    
    // reset choices
    $field['choices'] = array();
    
    // get the textarea value from options page without any formatting
    $choices = get_field('yt_mapper_instance', 'option', true);
    
    // loop through array and add to field 'choices'
    if( is_array($choices) ) {
        
        foreach( $choices as $choice ) {
            
            $choice_name = $choice['yt_mapper_id'];
            $field['choices'][ $choice_name ] = $choice_name;
            
        }
        
    }
    

    // return the field
    return $field;
    
}

add_filter('acf/load_field/name=yt_scrape_mapper', 'acf_populate_yt_scrape_mapper_choices');