<?php

namespace yt;

class option
{
    public $returned;
    
    public function __construct()
    {
        return $this;
    }


    public function get_all($field)
    {
        $this->get_repeater_options($field, 'returned');   
        return $this->returned;
    }



    public function get_repeater_options($repeater_field_name, $result_parameter)
    {
        // If field exists as an option
        if (have_rows($repeater_field_name, 'option')) {

            // Go through all rows of 'repeater' genimage_filters
            while (have_rows($repeater_field_name, 'option')): $row = the_row(true);

            $this->get_repeater_row($row, $result_parameter);

            endwhile;
        } else {
            $this->$result_parameter = get_field($repeater_field_name);
        }

        return;
    }



    public function get_repeater_row($row, $result_parameter)
    {

        $this->$result_parameter[] = $row;

        return $this;
    }
    
}
