<?php

//  ┌─────────────────────────────────────────────────────────────────────────┐ 
//  │                                                                         │░
//  │                        Youtube ID to video Page.                        │░
//  │                                                                         │░
//  │   Change the URL from the youtube one to the internal playlist page.    │░
//  │                                                                         │░
//  └─────────────────────────────────────────────────────────────────────────┘░
//   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

class youtube_id_to_playlist_page {


    public $input_stream;

    public $output_stream = [];

    public $filter_value;

    public function set_stream($input_stream){ $this->input_stream = $input_stream; }

    public function set_value($filter_value){ $this->filter_value = $filter_value; }



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

            // Add the new URL
            $item['url'] = 'http://' . $_SERVER['HTTP_HOST'] . '/tutorial-playlist?msi=tutorial_playlists&msp='.$item['id'];

            // Push to the output array.
            array_push($this->output_stream, $item);
        }

        // Return the output array.
        return $this->output_stream;
    }


}