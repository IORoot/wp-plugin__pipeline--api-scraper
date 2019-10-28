<?php

//  ┌─────────────────────────────────────────────────────────────────────────┐ 
//  │                                                                         │░
//  │                          Youtube Colour Hashtag                         │░
//  │                                                                         │░
//  └─────────────────────────────────────────────────────────────────────────┘░
//   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


class youtube_colour_hashtag {

    public $input_stream = [];

    public $filter_value = '';

    public $output_stream = [];

    public function set_stream($input_stream){ $this->input_stream = $input_stream; }

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

        if (!$this->input_stream) { return; }
        
        // Loop through each item.
        foreach ($this->input_stream as $item){

            preg_match('/\#COL-(.{6})/', $item['description'], $matches, PREG_OFFSET_CAPTURE);

            if (!empty($matches[1][0])){
                $item['colour'] = $matches[1][0];
            }

            array_push($this->output_stream, $item);
                
        }
 
        // Return the output array.
        return $this->output_stream;
    }

}