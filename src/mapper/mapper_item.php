<?php

namespace yt;

use yt\transform_group;


// ┌─────────────────────────────────────────────────────────────────────────┐ 
// │                                                                         │░
// │                               MAPPER_ITEM                               │░
// │                                                                         │░
// │                                                                         │░
// │ Primary Purpose:                                                        │░
// │                                                                         │░
// │ 1. Get the value from the source field                                  │░
// │ 2. Set that value to an array with the key as the destination field.    │░
// │ 3. Send the value to be transformed.                                    │░
// │                                                                         │░
// │ Difficult bits solved:                                                  │░
// │                                                                         │░
// │ 1. The location of the source field is in a string like                 │░
// │ 'snippet->title' and needs to be processed to get the value from that   │░
// │ location. see source_value()                                            │░
// │                                                                         │░
// └─────────────────────────────────────────────────────────────────────────┘░
//  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░



class mapper_item
{
    public $all_transforms;
    
    public $mapping_group;

    public $wp_post_args;

    
    /**
     * $single_mapping
     * 
     * This is the single instance
     * of each mapping to perform.
     * Each one of these make up the
     * $mapping_group
     *
     * @var undefined
     */
    public $single_mapping;

    public $source_mapping;

    public $mapped_result;




    public function __construct()
    {
        return $this;
    }

    public function set_transforms($all_transforms)
    {
        $this->all_transforms = $all_transforms;
        return $this;
    }


    public function set_mappings($mapping_group)
    {
        $this->mapping_group = $mapping_group;
        return $this;
    }

    public function set_source_item($source_item)
    {
        $this->source_item = $source_item;
        return $this;
    }





    public function run()
    {
        $this->process_mappings();

        return $this->wp_post_args;
    }


    public function process_mappings()
    {
        foreach ($this->mapping_group as $this->single_mapping) {
            $this->process_single_mapping();
        }
        return;
    }


    public function process_single_mapping()
    {
        $destination_field = $this->destination_field();

        $source_value = $this->source_value();

        $transformed_value = $this->transform_value($source_value);
        

        // Set the result array to have a key of the
        // destination field and the value of the
        // source field, after its had any transforms
        // performed on it.
        $this->wp_post_args[$destination_field] = $transformed_value;
        
        return;
    }


    /**
     * source_value
     * 
     * Traverse the source_item to get the specific
     * value we want to map to the destination field.
     * 
     * The location is held in the array 'location_parts'
     * broken down by each stage of the object,
     * one level at a time.
     *
     * @return void
     */
    public function source_value()
    {
        (new e)->line('- source : '. $this->single_mapping['yt_mapper_source'],2);

        $location_parts = $this->explode_source_location();

        // location of the item within this object.
        $value = $this->source_item;

        // Loop over each location part until you get
        // to the correct location in the item object.
        foreach ($location_parts as $object_level) {
            $value = $value->$object_level;
        }

        (new e)->line('- value : '. $value, 3);

        return $value;
    }


    /**
     * explode_source_location
     * 
     * The source field is a string, but to traverse
     * the item, we need each part of the string
     * so we we break it into an array.
     *
     * @return void
     */
    public function explode_source_location()
    {
        return explode('->', $this->single_mapping['yt_mapper_source']);
    }
    

    /**
     * destination_field
     * 
     * Where the result should go.
     *
     * @return void
     */
    public function destination_field()
    {
        (new e)->line('- destination :'.$this->single_mapping['yt_mapper_destination'],2);
        return $this->single_mapping['yt_mapper_destination'];
    }


    /**
     * transform_value
     *
     * @param mixed $value
     * @return void
     */
    public function transform_value($source_value)
    {

        $transform_group = new transform_group;
        $transform_group->set_field($source_value);
        $transform_group->transform_group_to_run($this->single_mapping['yt_mapper_transform']);
        $source_value = $transform_group->run();

        (new e)->line('- transformed : '. $source_value, 3);

        return $source_value;
    }
}