<?php

namespace yt;

class api
{

    /**
     * $substitutions
     * 
     * This contains ALL of the substitutions available to the scraper. Use  
     * these as a lookup to the one you wish to perform. 
     *
     * @var undefined
     */
    public $substitutions;

    /**
     * $config
     * 
     * An Associative Array that contains the configuration settings on the API 
     * scraper. Three settings are used  to control the scrape:
     * 
     * api_key 
     * At the moment, this is only used for the Youtube scrape and is appended 
     * to the query string.
     * 
     * query_string
     * This is the search query that is passed to the YouTube endpoint for 
     * processing.
     * 
     * extra_parameters
     * These are used to control various aspects of the particular scrape 
     * selected. For instance, if the multi-channel scrape is selected, this 
     * would be a list of all those channels to loop through.
     *
     * @var array
     */
    public $config = [
        'api_key' => '',
        'query_string' => '',
        'extra_parameters' => '',
    ];

    /**
     * $search_config
     * 
     * The search config holds the specific details of which scrape to perform 
     * on which particular API. The array will contain the following structure:
     * [
     *      'yt_search_id' => "YouTube - Daily Top 3",
     *      'yt_search_api' => "youtube",
     *      'yt_search_type' => "search",
     *      'yt_search_description' => "Top 3 Most viewed",
     *      'yt_search_string' => "part=snippet&q=parkour",
     *      'yt_search_parameters' => ""
     * ]
     *
     * @var array
     */
    public $search_config;
    



    /**
     * __construct
     * 
     * Thes constructor will clear the report log and return an instance of the 
     * object.
     *
     * @return \yt\api
     */
    public function __construct()
    {
        (new \yt\r)->clear('search');
        return $this;
    }


    /**
     * set_api_key
     * 
     * Primarily used for YouTube, the api_key is separate from the query string
     * to maintain security. Means that we can store it in a separate textfield 
     * that is not exported / imported and can be made a password type field.
     *
     * @param mixed $api_key
     * @return void
     */
    public function set_api_key($api_key = null)
    {
        if (!$this->check_input($api_key)) {
            return false;
        }
        $this->config['api_key'] = $api_key;
        return true;
    }

    /**
     * set_substitutions() function
     * 
     * Allow the scraper to pass through all of the substitutions that the user
     * has created in the options.
     *
     * TODO - Maybe it SHOULD be null? 
     * 
     * @param [type] $substitutions
     * @return void
     */
    public function set_substitutions($substitutions = null)
    {
        if (!$this->check_input($substitutions)) {
            return false;
        }
        $this->substitutions = $substitutions;
        return $this;
    }

    /**
     * set_search_config()
     * 
     * Allows the scraper to pass in the search configuration for the API.
     *
     * @param array $search_config
     * @return void
     */
    public function set_search_config($search_config = array())
    {
        if (!$this->check_input($search_config)) {
            return false;
        }
        $this->search_config = $search_config;
        return;
    }


    public function run()
    {
        $this->config_extra_parameters();
        $this->config_query();

        $request_type = '\\yt\\'.$this->search_config['yt_search_api'].'\\request\\'.$this->search_config['yt_search_type'];
        
        $request = new $request_type;

        $request->config($this->config);

        $request->request();

        return $request->response();
    }


    /**
     * config_extra_parameters()
     * 
     * This function will take the inputted textarea string that users can add 
     * in the 'extra parameters' field and parse it to become an associative
     * array of keys and values. 
     *
     * @return \yt\api 
     */
    public function config_extra_parameters()
    {
        $this->config['extra_parameters'] = $this->string_to_array($this->search_config['yt_search_parameters']);
        return $this;
    }



    public function config_query()
    {
        $string = $this->replace_any_substitutions($this->search_config['yt_search_string']);
        $string = $this->replace_any_tokens($string);
        (new e)->line('- Final Query string = '.$string, 1);
        $this->config['query_string'] = $string;
        return $this;
    }



    public function string_to_array($parameters)
    {
        $string = preg_replace("/\r|\n/", "", $parameters);
        return eval("return $string;");
    }


    public function replace_any_substitutions($string)
    {
        preg_match_all('/\[\[(.*?)\]\]/', $string, $matches, PREG_SET_ORDER);
        (new e)->line('- Found '.count($matches) . ' substitution matches in string '.$string, 1);

        foreach ($matches as $match) {
            $word = $match[1];

            foreach ($this->substitutions as $sub) {
                if ($word == $sub["yt_search_substitutions_word"]) {
                    $string = str_replace($match[0], $sub['yt_search_substitutions_replace'], $string);
                }
            }
        }

        return $string;
    }


    public function replace_any_tokens($string)
    {
        preg_match_all('/\{\{(.*?)\=(.*?)\}\}/', $string, $matches, PREG_SET_ORDER);
        (new e)->line('- Found '.count($matches) . ' token matches in string '.$string, 1);

        foreach ($matches as $match) {
            $token_name = '\\yt\\token\\'.$match[1];

            $token_object = new $token_name;
            $token_object->config($match[2]);
            $token_object->in($match[0]);

            $string = str_replace($match[0], $token_object->out(), $string);
        }

        return $string;
    }





    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │░
    // │                                                                         │░
    // │                                 CHECKS                                  │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


    public function check_input($input)
    {
        if (!$input) {
            (new e)->line('- Input has not been set.', 1);
            return false;
        }
        if ($input == '') {
            (new e)->line('- Input is empty.', 1);
            return false;
        }

        return true;
    }
}
