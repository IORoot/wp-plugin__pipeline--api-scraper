<?php


namespace yt\import;

class exists
{
    

    public function __construct()
    {
        return $this;
    }


    public function post_by_title($title, $post_type = 'post')
    {

        if ($this->test_post_exists_by_title_in_cpt($title, $post_type)){ return true; }
        if ($this->test_post_santized_title_in_db_in_cpt($title, $post_type)){ return true; }

        return false;
    }


    public function test_post_exists_by_title_in_cpt($title, $post_type = 'post')
    {
        $does_it_exist = post_exists($title, null, null, $post_type);

        if ($does_it_exist) {
            $this->error_report($title);
            return true;
        } 

        return false;
    }



    public function test_post_santized_title_in_db_in_cpt($title, $post_type = 'post')
    {
        $sanitized_title = sanitize_title($title);
        
        global $wpdb;
        if($wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_type = '".$post_type."' AND post_name = '" . $sanitized_title . "'", 'ARRAY_A')) {
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