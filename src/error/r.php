<?php

namespace yt;

class r
{

    
    public function __construct()
    {
        return $this;
    }

    public function last($field, $value)
    {
        $field = 'yt_'.$field.'_last_result';
        $value = json_encode($value, JSON_PRETTY_PRINT);
        
        update_field($field, $value, 'option');
    }

}