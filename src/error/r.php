<?php

namespace yt;

// reporting
class r
{

    
    public function __construct()
    {

        return $this;
    }

    public function clear($field)
    {
        $field = 'yt_debug_'.$field;
        update_field($field, '', 'option');
    }

    
    public function last($field, $value)
    {
        if (is_array($value)){
            $value = json_encode($value, JSON_PRETTY_PRINT);
        }

        $field = 'yt_debug_'.$field;
        $old_value = get_field( $field, 'options' ). '
        ';

        update_field($field, $old_value.$value, 'option');
    }



}