<?php


namespace yt\import;

class exists
{
    

    public function __construct()
    {
        return $this;
    }



    public function post_by_title($title)
    {

        if ($this->test_post_exists_by_title($title)){ return true; }
        if ($this->test_post_santized_title_in_db($title)){ return true; }

        return false;
    }


    public function test_post_exists_by_title($title)
    {
        $does_it_exist = post_exists($title);

        if ($does_it_exist) {
            $this->error_report($title);
            return true;
        } 

        return false;
    }


    public function test_post_santized_title_in_db($title)
    {
        $sanitized_title = sanitize_title($title);
        
        global $wpdb;
        if($wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = '" . $sanitized_title . "'", 'ARRAY_A')) {
            $this->error_report($title);
            return true;
        }

        return false;
    }



    public function error_report($title)
    {
        (new \yt\e)->line('Post exists, skipping : ' . $title, 2);
        (new \yt\r)->last('import','Post exists, skipping : ' . $title); 
    }

}