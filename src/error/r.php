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
        $field = 'yt_'.$field.'_group_yt_debug_'.$field;
        $result = update_field( $field, '', 'option');
        return $result;
    }

        
    public function last($field, $value)
    {
        if (is_array($value)){
            $value = $this->utf8ize($value);
            $value = json_encode($value, JSON_PRETTY_PRINT);
        }
        if (is_object($value)){
            $value = json_encode($value, JSON_PRETTY_PRINT);
        }

        $trim_length = intval(get_field( 'yt_'.$field.'_group_yt_debug_'.$field.'_length' , 'options' ));

        $field = 'yt_'.$field.'_group_yt_debug_'.$field;

        $old_value = get_field( $field, 'options' ). '
        ';

        if ($trim_length != 0){
            $trimmed_string = substr($old_value . $value, 0, $trim_length);
        }

        update_field($field, $trimmed_string, 'option');

    }


    public function new($field, $value)
    {
        $this->clear($field);
        $this->last($field, $value);
    }


    public function utf8ize($d) {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = $this->utf8ize($v);
            }
        } else if (is_string ($d)) {
            return utf8_encode($d);
        }
        return $d;
    }

}