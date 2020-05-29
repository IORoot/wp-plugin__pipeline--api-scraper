<?php

/**
 * andyp_scrape_date_callback function
 *
 * Returns an option from wordpress with the ID of a scrape.
 * The scrape_id is a SANITIZED title from the scraper with all
 * emojis removed.
 * So, the scrape_id of a title called 'YouTube - ❤️Daily Top'
 * Would become 'youtube-daily-top'.
 * The 'fmt' is the DateTime format of the output.
 * See https://www.php.net/manual/en/function.date.php
 * 
 * @param [type] $atts
 * @return void
 */
function andyp_scrape_date_callback($atts){

    $a = shortcode_atts( 
        array(
            'scrape_id'  => null,
            'fmt' => null
        ), $atts );



    if (!isset($a['scrape_id']))
    {
        echo "No scrape_id set.";
        return;
    }

    $no_emoji = preg_replace('/[[:^print:]]/', '', $a['scrape_id']);
        
    $slug = sanitize_title($no_emoji);

    $timestamp = get_option($slug, 'Scrape ID not found.');

    if (!$timestamp)
    {
        echo 'No Timestamp found';
        return;
    }

    $format = 'r';

    if ($a['fmt'] != null){
        $format = $a['fmt'];
    }

    if ($format == 'ago')
    {
        return timeago($timestamp);
    }

    $datetime = new \DateTime();
    $datetime->setTimestamp($timestamp);

    return $datetime->format($format);

}

function timeago($i){
    $m = time()-$i; $o='just now';
    $t = array('year'=>31556926,'month'=>2629744,'week'=>604800, 'day'=>86400,'hour'=>3600,'minute'=>60,'second'=>1);

    foreach($t as $u=>$s){
        if($s<=$m){$v=floor($m/$s); $o="$v $u".($v==1?'':'s').' ago'; break;}
    }
    return $o;
}

add_shortcode( 'andyp_scrape_date', 'andyp_scrape_date_callback' );