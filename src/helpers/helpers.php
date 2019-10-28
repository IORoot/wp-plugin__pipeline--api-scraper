<?php

trait helpers {

    /**
     * $current_array
     * 
     * Used to store an temporary array for $this->replace_match method.
     *
     * @var undefined
     */
    private $current_array;

    
    /**
     * curlies_to_value
     * 
     * Convert any string with double-curlies to their value.
     *
     * @param mixed $input_string
     * @param mixed $options
     * @return void
     */
    public function curlies_to_value($input_string, $options){
        
        $array_of_values = explode(',' , $input_string);

        $output = [];

        foreach ($array_of_values as $parameter){

            // strip spaces.
            $parammeter = str_replace(' ', '', $parameter);

            if ( strpos($parameter, '{{') !== false){
                
                $parameter = preg_replace( "~\{\{\s*(.*?)\s*\}\}~" , '${1}', $parameter);
                
                array_push($output, $options[$parameter]);
    
            } else {
                array_push($output, $parameter );
            }

        }

        $input_string  = implode(',', $output);

        return $input_string;

    }


    /**
     * curlies_to_input_array
     * 
     * Check for value in the passed input_array and substitute.
     *
     * @param mixed $input_string
     * @param mixed $input_array
     * @return void
     */
    public function substitute_curlies($input_string, $input_array){
        
        $this->current_array = $input_array;

        $output = preg_replace_callback( "~\{\{\s*(.*?)\s*\}\}~" , 'self::replace_match', $input_string);

        return $output;
    }


    /**
     * replace_match
     * 
     * Replace a match with a value in the temporary array.
     *
     * @param mixed $matches
     * @return void
     */
    public function replace_match($matches){

        if (array_key_exists($matches[1], $this->current_array)){
            return $this->current_array[$matches[1]];
        }
        
        return;

    }


    /**
     * before_after_curlies
     *
     * @param mixed $input_string
     * @return void
     */
    public function before_after_curlies($input_string){

        return preg_split( "~\{\{\s*(.*?)\s*\}\}~" , $input_string);

    }

    
    //  ┌─────────────────────────────────────────────────────────────────────────┐
    //  │                 Convert datetime 'Y-m-d H:i:s' to 'time ago' format.    │
    //  └─────────────────────────────────────────────────────────────────────────┘
    public function timeago($time, $tense='ago') {
        // declaring periods as static function var for future use
        static $periods = array('year', 'month', 'day', 'hour', 'minute', 'second');
    
        // checking time format
        if(!(strtotime($time)>0)) {
            return trigger_error("Wrong time format: '$time'", E_USER_ERROR);
        }
    
        // getting diff between now and time
        $now  = new DateTime('now');
        $time = new DateTime($time);
        $diff = $now->diff($time)->format('%y %m %d %h %i %s');
        // combining diff with periods
        $diff = explode(' ', $diff);
        $diff = array_combine($periods, $diff);
        // filtering zero periods from diff
        $diff = array_filter($diff);
        // getting first period and value
        $period = key($diff);
        $value  = current($diff);
    
        // if input time was equal now, value will be 0, so checking it
        if(!$value) {
            $period = 'seconds';
            $value  = 0;
        } else {
            // converting days to weeks
            if($period=='day' && $value>=7) {
                $period = 'week';
                $value  = floor($value/7);
            }
            // adding 's' to period for human readability
            if($value>1) {
                $period .= 's';
            }
        }
    
        // returning timeago
        return "$value $period $tense";
    }

}