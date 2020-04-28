<?php

namespace yt\transform;

use yt\interfaces\transformInterface;

class save_image implements transformInterface
{
    
    public $description = "
    Uploads an image into the Media Library and attach it to the post.
    (width, height, alt) are optional.
    ";

    public $parameters = '(array)
    [
        \'image_url\' => \'http://londonparkour.com/logo.jpg\',
        \'width\' => \'480\',  
        \'height\' => \'360\',
        \'alt\' => \'Logo for londonparkour.\'
    ]
    ';

    public $input;
    
    public function config($config)
    {
        return;
    }

    public function in($input)
    {
        $this->input = $input;
        return;
    }

    public function out()
    {
        return $this->input;
    }

}
