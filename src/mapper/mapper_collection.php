<?php

namespace yt;

use yt\mapper_item;

// ┌─────────────────────────────────────────────────────────────────────────┐
// │                                                                         │░
// │                            MAPPER_COLLECTION                            │░
// │                                                                         │░
// │                                                                         │░
// │ Primary Purpose:                                                        │░
// │                                                                         │░
// │ 1. For a mapper_collection, split it up into separate mapper items.     │░
// │                                                                         │░
// │ 2. Pass each item to mapper_item to handle each instance.               │░
// │                                                                         │░
// └─────────────────────────────────────────────────────────────────────────┘░
//  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


class mapper_collection
{
    public $transforms;

    public $mappings;

    public $collection;

    public $mapped_result;

    public function __construct()
    {
        return $this;
    }


    public function set_mappings($mappings)
    {
        $this->mappings = $mappings;
        return $this;
    }


    public function set_collection($collection)
    {
        $this->collection = $collection;
        return $this;
    }


    /**
     * We need the transforms because we need
     * the parameters associated with each transform.
     */
    public function set_transforms($transforms)
    {
        $this->transforms = $transforms;
        return $this;
    }


    public function run()
    {
        $this->mapped_result = '';
        
        foreach ($this->collection as $key => $item) {
            (new e)->line('- item : '.$key, 1);
            $this->map_item($item);
        }

        return $this->mapped_result;
    }


    public function map_item($item)
    {
        $mapper_item = new mapper_item;
        $mapper_item->set_transforms($this->transforms);
        $mapper_item->set_mappings($this->mappings);
        $mapper_item->set_source_item($item);

        $result = $mapper_item->run();

        if (!is_array($result)) {
            return;
        }

        array_push($this->mapped_result, $result);
       // $this->mapped_result[] = $result;

        return;
    }
}
