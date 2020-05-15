<?php

namespace yt;

// ┌─────────────────────────────────────────────────────────────────────────┐ 
// │                                                                         │░
// │                             TRANSFORM_LAYER                             │░
// │                                                                         │░
// │                                                                         │░
// │ Primary Purpose:                                                        │░
// │                                                                         │░
// │ 1. Check that the specified transform exists.                           │░
// │                                                                         │░
// │ 2. Create a new instance of that transform                              │░
// │                                                                         │░
// │ 3. Pass it the field and the transform_layer                            │░
// │                                                                         │░
// │ 4. Run it and Return the result back.                                   │░
// │                                                                         │░
// │                                                                         │░
// │ Notes:                                                                  │░
// │                                                                         │░
// │ The $this->transform_layer should contain:                              │░
// │                                                                         │░
// │ 1. yt_transform                                                         │░
// │ (the name of the transform)                                             │░
// │                                                                         │░
// │ 2. yt_transform_parameters                                              │░
// │ (the parameters passed in to control it)                                │░
// │                                                                         │░
// │                                                                         │░
// └─────────────────────────────────────────────────────────────────────────┘░
//  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

class transform_layer
{

    public $transform_layer;

    public $field;

    public $index;


    public function __construct()
    {
        return $this;
    }

    public function set_field($field)
    {
        $this->field = $field;
        return;
    }


    public function set_transform_layer($transform_layer)
    {
        $this->transform_layer = $transform_layer;
        return;
    }


    public function run()
    {
        $this->check_transform_exists();
        return $this->instantiate_transform();
    }


    public function check_transform_exists()
    {
        $transform_name = '\\yt\\transform\\'.$this->transform_layer['yt_transform'];

        if (!class_exists($transform_name)) {
            throw new \Exception('This transform - '.$transform_name.' - does not exist, cannot instantiate class of this name in src/transform/transform_layer->run()');
        }

        return;
    }



    public function instantiate_transform()
    {

        $transform_name = '\\yt\\transform\\'.$this->transform_layer['yt_transform'];
        $parameters = $this->transform_layer['yt_transform_parameters'];

        $transform_object = new $transform_name;
        $transform_object->config($parameters);
        $transform_object->in($this->field);

        $out = $transform_object->out();

        return $out;

    }
}
