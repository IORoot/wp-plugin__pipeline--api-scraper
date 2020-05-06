<?php

namespace yt;

class quota
{


    
    public function update_all_quotas($cost)
    {
        while (have_rows('yt_auth_instance', 'option')): $row = the_row(true);

        $new_quota = $row['yt_api_quota'] - $cost;
        update_sub_field('yt_api_quota', $new_quota, 'option')  ;

        endwhile;

        return;
    }



    public function update_quota_by_api_key($cost, $api_key)
    {
        while (have_rows('yt_auth_instance', 'option')): $row = the_row(true);

        if ($api_key != $row['yt_api_key']){ continue; }
        $new_quota = $row['yt_api_quota'] - $cost;
        update_sub_field('yt_api_quota', $new_quota, 'option');

        endwhile;

        return;
    }



}
