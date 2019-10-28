<?php

//  ┌─────────────────────────────────────────────────────────────────────────┐ 
//  │                                                                         │░
//  │                          Youtube Colour Hashtag                         │░
//  │                                                                         │░
//  └─────────────────────────────────────────────────────────────────────────┘░
//   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


class date_to_timeago {

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

            $item['timeago'] = $this->time_elapsed_string('@'.$item['date']);

            array_push($this->output_stream, $item);
                
        }
 
        // Return the output array.
        return $this->output_stream;
    }



    //  ┌──────────────────────────────────────────────────────────┐
    //  │                   Convert to 'timeago'                   │
    //  └──────────────────────────────────────────────────────────┘
    /**
     * time_elapsed_string
     *
     * @param mixed $datetime
     * @param mixed $full
     * @return void
     */
    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }



}