<?php



function filtering_menu($atts, $content = null){

    //  ┌──────────────────────────────────────┐
    //  │         Shortcode parameters         │
    //  └──────────────────────────────────────┘
    extract(
        shortcode_atts(
            array(
                // Menu Name
                'mobile' => null,
            ),
            $atts
        )
    );

    $output  = '<ul class="menu tutorial-filters">';

        $output .= '<li class="menu-item item-alpha'.rotate_icon('title').' "><a href="'. currentUrl('mss', 'title') . '">A - Z</a></li>';
        $output .= '<li class="menu-item item-newest'.rotate_icon('date').'"><a href="'. currentUrl('mss', 'date') . '">Date</a></li>';
        $output .= '<li class="menu-item item-popularity'.rotate_icon('likes').'"><a href="'. currentUrl('mss', 'likes') . '">Popularity</a></li>';

    $output .= '</ul>';

    // Mobile instead of desktop
    if ($mobile) {

        $output  = '<select class="menu tutorial-filters" onChange="window.location.href=this.value">';
            $output .= '<option>Filters</option>';
            $output .= '<option value="'. currentUrl('mss', 'title') . '" >Alphabetically</option>';
            $output .= '<option value="'. currentUrl('mss', 'date') . '" >Date</option>';
            $output .= '<option value="'. currentUrl('mss', 'likes') . '" >Popularity</option>';
        $output .= '</select>';       
    }


    return $output;
}



function currentUrl($what_to_reset = null, $what_to_change_it_to = null) {

    //  ┌──────────────────────────────────────────────────────────┐
    //  │                    Get all parameters                    │
    //  └──────────────────────────────────────────────────────────┘
    $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http://' : 'https://';
    $host     = $_SERVER['HTTP_HOST'];
    $script = preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']); // Remove everything after the question mark.
    $params   = $_GET;

    unset($params[$what_to_reset]);

    $params[$what_to_reset] = $what_to_change_it_to;

    //  ┌──────────────────────────────────────────────────────────┐
    //  │               Flip-Flop the reverse order                │
    //  └──────────────────────────────────────────────────────────┘
    if ( !isset($params['mso']) ) { $params['mso'] = ''; } else { unset($params['mso']); }

    $new_query_string = http_build_query($params);
    
    $url =  $protocol . $host . $script . '?' . $new_query_string;

    return $url;
}


function rotate_icon($item){

    $output = '';

    if ( isset($_GET['mso']) ){  $output = ' icon-flip'; }

    if (isset($_GET['mss']) && $item == $_GET['mss']){
        return $output;
    }


    return;
}



add_shortcode( 'filtering_menu', 'filtering_menu' );