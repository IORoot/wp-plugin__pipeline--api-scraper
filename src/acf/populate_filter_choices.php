<?php

function acf_load_filter_selection_field_choices( $field ) {
    
    // reset choices
    $field['choices'] = array();
    
    // get the textarea value from options page without any formatting
    $choices = get_field('yt_filter_group', 'option', true);
    
    // loop through array and add to field 'choices'
    if( is_array($choices) ) {
        
        foreach( $choices as $choice ) {
            
            $choice_name = $choice['yt_filter_group_name'];
            $field['choices'][ $choice_name ] = $choice_name;
            
        }
        
    }
    

    // return the field
    return $field;
    
}

add_filter('acf/load_field/name=yt_filter_selection', 'acf_load_filter_selection_field_choices');