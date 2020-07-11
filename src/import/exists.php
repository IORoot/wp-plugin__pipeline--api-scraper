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
        $post_id = $this->test_post_exists_by_title_in_cpt($title, $post_type);
        if ($post_id) {
            return $post_id;
        }

        $post_id = $this->test_post_santized_title_in_db_in_cpt($title, $post_type);
        if ($post_id) {
            return $post_id;
        }

        return false;
    }




    public function test_post_exists_by_title_in_cpt($title, $post_type = 'post')
    {
        $post_id = post_exists($title, null, null, $post_type);

        if ($post_id) {
            $this->error_report($title);
            return $post_id;
        }

        return false;
    }


    public function test_post_santized_title_in_db_in_cpt($title, $post_type = 'post')
    {
        $sanitized_title = sanitize_title($title);
        
        global $wpdb;

        $post_id = $wpdb->get_row("SELECT ID FROM wp_posts WHERE post_type = '".$post_type."' AND post_name = '" . $sanitized_title . "'", 'ARRAY_A');
        if ($post_id) {
            $this->error_report($title);
            return $post_id;
        }

        return false;
    }



    public function error_report($title)
    {
        (new \yt\e)->line('Post exists, skipping : ' . $title, 2);
        (new \yt\e)->line('import - Post exists, skipping : ' . $title);
    }
}
