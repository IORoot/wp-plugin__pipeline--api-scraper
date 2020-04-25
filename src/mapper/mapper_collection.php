<?php

namespace yt;

use yt\mapper_item;

class mapper_collection
{
    public $filters;

    public $mappings;

    public $collection;

    public $item;

    public $mapped_result;

    public function __construct()
    {
        return $this;
    }


    public function set_mappings($mappings_array)
    {
        $this->mappings = $mappings_array;
        return $this;
    }


    public function set_collection($collection)
    {
        $this->collection = $collection;
        return $this;
    }


    /**
     * We need the filters because we need
     * the parameters associated with each filter.
     */
    public function set_filters($filters)
    {
        $this->filters = $filters;
        return $this;
    }


    public function run()
    {
        foreach ($this->collection as $this->item) {
            $this->map_item();
        }

        return $this->mapped_result;
    }


    public function map_item()
    {
        $mapper_item = new mapper_item;
        $mapper_item->set_filters($this->filters);
        $mapper_item->set_mappings($this->mappings);
        $mapper_item->set_source($this->item);

        $this->mapped_result[] = $mapper_item->run();

        return;
    }
}
