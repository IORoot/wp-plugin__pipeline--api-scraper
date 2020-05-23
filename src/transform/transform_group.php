<?php

namespace yt;

use yt\transform_layer;
use yt\option;

// ┌─────────────────────────────────────────────────────────────────────────┐ 
// │                                                                         │░
// │                             TRANSFORM_GROUP                             │░
// │                                                                         │░
// │                                                                         │░
// │ Primary Purpose:                                                        │░
// │                                                                         │░
// │ 1. Get all of the transform groups possible from options.               │░
// │                                                                         │░
// │ 2. Pick the group we are running on this field.                         │░
// │                                                                         │░
// │ 3. For each transform layer in the group run it against the field by    │░
// │ passing it to transform_layer                                           │░
// │                                                                         │░
// │                                                                         │░
// └─────────────────────────────────────────────────────────────────────────┘░
//  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

class transform_group
{
    public $field;

    public $transform_group_to_run;

    public $transform_layers_to_run;

    public function __construct()
    {
        return $this;
    }

    public function set_field($field)
    {
        $this->field = $field;
        return;
    }

    public function transform_group_to_run($transform_group_to_run)
    {
        $this->transform_group_to_run = $transform_group_to_run;
        return;
    }

    public function run(){

        $this->get_transform_layers();
        $this->loop_layers_on_field();
        
        return $this->field;
    }



    public function get_transform_layers()
    {
        $transforms = new option;
        $all_transform_groups = $transforms->get_all('yt_mapper_group_yt_transform_instance');

        foreach ($all_transform_groups as $transform_group)
        {
            if ($transform_group['yt_transform_id'] == $this->transform_group_to_run)
            {
                    $this->transform_layers_to_run = $transform_group;
            }
        }

        return;
        
    }


    public function loop_layers_on_field()
    {
        foreach ($this->transform_layers_to_run['yt_transform_layers'] as $layer)
        {
            $this->run_this_layer_against_field($layer);
        }
    }


    public function run_this_layer_against_field($layer)
    {
        $transform_item = new transform_layer;
        $transform_item->set_field($this->field);
        $transform_item->set_transform_layer($layer);

        $this->field = $transform_item->run();

        return;

    }


}
