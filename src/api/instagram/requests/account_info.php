<?php

namespace yt\instagram\request;

use yt\interfaces\requestInterface;
use yt\instagram\response;


class account_info implements requestInterface
{

    public $nice_name = "IG Account Info";

    public $description = "Performs a search on a single instagram account using Instamancer.";

    public $parameters = 'none';

    public $cost = 0;

    public $domain = 'https://www.instagram.com/';

    public $config = [
        'api_key' => null,
        'query_string' => null,
        'extra_parameters' => null,
    ];  

    public $built_request_url;

    public $response;

    public function config($config)
    {
        $this->config = $config;
        return;
    }

    public function response()
    {
        if (isset($this->response)){
            return json_decode($this->response);
        }

        return;
        
    }

    public function get_cost()
    {
        return $this->cost;
    }


    public function request()
    {

        $dir = WP_PLUGIN_DIR . '/andyp_youtube_scraper_v2/files/instagram_json/london_parkour';
        $json_file = $dir . '/london_parkour.json';
        $down_dir = $dir . '/downloads';

        $cmd = 'instamancer user london_parkour -c 10 -f -o '.$json_file.' -d --downdir '.$down_dir;
        $cmd = 'pwd';
        $result = shell_exec( $cmd );

        $cmd = 'cat /var/www/vhosts/dev.londonparkour.com/wp-content/plugins/andyp_youtube_scraper_v2/vendor/instamancer/instamancer';
        $result = shell_exec( $cmd );

        // ┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
        // ┃                                                                         ┃
        // ┃             https://github.com/pgrimaud/instagram-user-feed             ┃
        // ┃                                                                         ┃
        // ┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛

        // (new \yt\r)->last('search', 'RESPONSE:'. json_encode($this->response, JSON_PRETTY_PRINT));
        

        if (!(new response)->is_errored($this->response)) {
            return false;
        }

        return true;
    }



    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │░
    // │                                                                         │░
    // │                                PRIVATE                                  │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    private function build_request_url()
    {
        if(!$this->check_url()){return false;}  

        $this->built_request_url = $this->domain . $this->config['query_string'] . "/?__a=1";

        (new \yt\r)->last('search', 'QUERSTRING = '. $this->built_request_url); 
    }

    
    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │░
    // │                                                                         │░
    // │                                 CHECKS                                  │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    public function check_url()
    {
        if ($this->domain == '') {
            (new \yt\e)->line('- $this->domain is blank. Please set.', 1);
            return false;
        }
        if ($this->config['query_string'] == '') {
            (new \yt\e)->line('- $this->config[query_string] is blank. Please set.', 1);
            return false;
        }

        return true;
    }
}
