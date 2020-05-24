<?php

namespace yt;

// logging last run date
class l
{

    public function go($scrape_id)
    {
        if (!$scrape_id){
            return;
        }

        $no_emoji = preg_replace('/[[:^print:]]/', '', $scrape_id);
        
        $slug = sanitize_title($no_emoji);

        update_option($slug, time());
        
        return;
    }


}