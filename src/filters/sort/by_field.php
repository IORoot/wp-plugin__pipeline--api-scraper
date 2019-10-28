<?php

//https://www.the-art-of-web.com/php/sortarray/

class by_field {

    public $input_stream;

    public $output_stream;

    public $field = '';

    public function set_stream($input_stream){ $this->input_stream = $input_stream; }
    public function set_field($field){ $this->field = $field; }
    
    /**
     * sort
     *  
     * @return void
     */
    public function sort(){

        if (!$this->input_stream){ return; }
        
        // Check if this a single array (video) - doesn't need sorting.
        if ( empty($this->input_stream[0]) ){ return $this->input_stream; }

        // Search the first entry for the index to make sure it exists!
        if (!array_key_exists($this->field, $this->input_stream[0]) ){ 
            echo 'Field does not exist.'; 
            return $this->input_stream; 
        }

        // Sort the array. returns TRUE / FALSE
        usort($this->input_stream, array($this, 'compare'));

        $this->output_stream = $this->input_stream;

        return $this->output_stream;

    }

    public function compare($a, $b){

        $return = strnatcmp($a[$this->field], $b[$this->field]);

        return $return;
    }

}