<?php

//  ┌─────────────────────────────────────────────────────────────────────────┐ 
//  │                                                                         │░
//  │            Only show results with the word 'Tutorial' in it.            │░
//  │                                                                         │░
//  └─────────────────────────────────────────────────────────────────────────┘░
//   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


class youtube_tutorials_title_only {

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

        if (!$this->input_stream){ return; }
        
        // Loop through each item.
        foreach ($this->input_stream as $item){

            // Is it in the title
            $result = stripos($item['title'], $this->filter_value);

            // If there is a hit, add it to the output array.
            if ($result !== false){
                array_push($this->output_stream, $item);
            }

        }
 
        // Return the output array.
        return $this->output_stream;
    }

}