<?php

namespace yt\transform;

use yt\interfaces\transformInterface;

class regex_match implements transformInterface
{

    public $description = "Perform a REGEX on the string and return the matches. preg_match(). Returns an array of matches.";

    public $parameters = '(string) /(foo)/';

    public $input;

    public $config;

    public function config($config)
    {
        $this->config = $config;
        return;
    }

    public function in($input)
    {
        $this->input = $input;
        return;
    }

    public function out()
    {
        $return=[];

        preg_match_all($this->config, $this->input, $matched);

        foreach ($matched[1] as $match)
        {
            if (is_string($match)){
                $return[] = $match;
                continue;
            }
            
            $return[] = $match[0];
        }

        return $return;
    }
}
