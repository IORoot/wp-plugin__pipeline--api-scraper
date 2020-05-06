<?php

namespace yt;

class api
{
    public $substitutions;

    public $request_type;

    public $config = [
        'api_key' => '',
        'query_string' => '',
        'extra_parameters' => '',
    ];
    

    public function __construct()
    {
        (new \yt\r)->clear('search');
        return $this;
    }


    public function set_request_type($request_type)
    {
        if (!$this->check_request_type($request_type)) {
            return false;
        }
        $this->request_type = $request_type;
        return $this;
    }
    

    public function set_api_key($api_key)
    {
        if (!$this->check_key($api_key)) {
            return false;
        }
        $this->config['api_key'] = $api_key;
        return true;
    }


    public function set_query($string)
    {
        if (!$this->check_query($string)) {
            return false;
        }

        $string = $this->replace_any_substitutions($string);
        $string = $this->replace_any_tokens($string);
        (new e)->line('- Final Query string = '.$string, 1);
        $this->config['query_string'] = $string;
        return $this;
    }

    
    public function set_extra_parameters($extra_parameters)
    {
        $this->config['extra_parameters'] = $this->string_to_array($extra_parameters);
        return $this;
    }

    public function set_substitutions($substitutions)
    {
        $this->substitutions = $substitutions;
        return $this;
    }





    public function run()
    {
        $request_type = '\\yt\\request\\'.$this->request_type;
        $request = new $request_type;

        $request->config($this->config);

        $request->request();

        return $request->response();
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



    public function check_query($string)
    {
        if (!$string) {
            (new e)->line('- No search string has been supplied. Please supply query_string().', 1);
            return false;
        }
        if ($string == '') {
            (new e)->line('- Search string is blank. Please supply query_string.().', 1);
        }

        return true;
    }

    public function check_request_type($string)
    {
        if (!$string) {
            (new e)->line('- No request_type has been supplied.', 1);
            return false;
        }
        if ($string == '') {
            (new e)->line('- request_type is blank.', 1);
        }

        return true;
    }


    public function check_key($api_key)
    {
        if (!$api_key) {
            (new e)->line('- No API_KEY has been set.', 1);
            return false;
        }
        if ($api_key == '') {
            (new e)->line('- No API_KEY is empty.', 1);
            return false;
        }

        return true;
    }
}
