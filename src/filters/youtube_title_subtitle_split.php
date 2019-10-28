<?php

//  ┌─────────────────────────────────────────────────────────────────────────┐ 
//  │                                                                         │░
//  │                          Youtube Colour Hashtag                         │░
//  │                                                                         │░
//  └─────────────────────────────────────────────────────────────────────────┘░
//   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


class youtube_title_subtitle_split {

    public $input_stream = [];

    public $filter_value = '';

    public $output_stream = [];


    // Set the input array (all the items in the reuslt array)
    public function set_stream($input_stream){ $this->input_stream = $input_stream; }

    // Set the filter value (set in ACF)
    public function set_value($filter_value){  $this->filter_value = $filter_value; }


    /**
     * filter
     * 
     * Will remove any entry that doesn't have the Hashtag.
     *
     * @param mixed $output_stream
     * @return void
     */
    public function filter(){

        if (!$this->input_stream){ return; }
        
        // Loop through each item.
        foreach ($this->input_stream as $item){

            $matches = explode($this->filter_value, $item['title']);

            if (!empty($matches[0])){
                $item['header'] = $matches[0];
            }
            if (!empty($matches[1])){
                $item['subheader'] = $matches[1];
            }

            array_push($this->output_stream, $item);
                
        }
 
        // Return the output array.
        return $this->output_stream;
    }

}