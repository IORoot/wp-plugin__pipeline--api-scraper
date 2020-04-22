<?php

namespace yt;

class options
{
    public $creds;

    public $search;

    public $filter;

    public function __construct()
    {
        return $this->get_all_options();
    }


    public function get_all_options()
    {
        // creds
        $this->creds['api_project'] = get_field('yt_api_project_name', 'option');
        $this->creds['api_key'] = get_field('yt_api_key', 'option');

        // search
        $this->get_repeater_options('yt_search_instance', 'search');

        // filters
        $this->get_repeater_options('yt_filter_group', 'filter');

        // import
        $this->import['yt_import_enabled'] = get_field('yt_import_enabled', 'option');
        $this->import['yt_import_post_type'] = get_field('yt_import_post_type', 'option');
        $this->import['yt_import_taxonomy_type'] = get_field('yt_import_taxonomy_type', 'option');
        
        return $this->result;
    }



    public function get_repeater_options($repeater_field_name, $result_parameter)
    {
        // If field exists as an option
        if (have_rows($repeater_field_name, 'option')) {

            // Go through all rows of 'repeater' genimage_filters
            while (have_rows($repeater_field_name, 'option')): $row = the_row(true);

            $this->get_repeater_row($row, $result_parameter);

            endwhile;
        }
    }



    public function get_repeater_row($row, $result_parameter)
    {
        $this->$result_parameter[] = $row;

        return $this;
    }
}
