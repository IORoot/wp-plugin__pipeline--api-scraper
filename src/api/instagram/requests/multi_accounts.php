<?php

namespace yt\instagram\request;

use yt\interfaces\requestInterface;
use yt\instagram\response;
use yt\instagram\request\account_info;

# PostAddictMe
use Phpfastcache\Helper\Psr16Adapter;

class multi_accounts implements requestInterface
{
    public $nice_name = "IG Multi Account Info";

    public $description = "Performs a search on multiple instagram accounts. (CSV separated)";

    public $parameters = '(int) Number of posts per channel to retrieve.';

    public $cost = 0;

    public $config = [
        'api_key' => null,
        'api_username' => null,
        'query_string' => null,
        'extra_parameters' => null,
    ];

    public $response;




    public function config($config)
    {
        $this->config = $config;
        return;
    }

    public function response()
    {
        return $this->response;
    }

    public function get_cost()
    {
        return $this->cost;
    }

    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │░
    // │                                                                         │░
    // │                            INSTAMANCER                                  │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


    public function request()
    {
        // Declare an empty object stdClass.
        $this->response = (object) [];
        $this->response->items = [];

        foreach ($this->csv_explode() as $accountID) {

            if (!$this->can_run_node()) {
                break;
            }
            if (!$this->can_run_instamancer()) {
                break;
            }

            $this->run_instamancer($accountID);
            $this->read_response_json($accountID);

        }

        return true;
    }


    public function run_instamancer($accountID)
    {
        $temp_dir = WP_CONTENT_DIR . '/uploads/instamancer/'.$accountID.'/';
        
        if (!file_exists($temp_dir)) {
            mkdir($temp_dir , 0777, true);
        }
        
        $json_file = $temp_dir.'output_' . date('Ymd') . '.json';
        $downloads = $temp_dir.'downloads/';
        $count = $this->default_count();
        
        $instamancer = 'node';
        $instamancer .= ' /usr/local/lib/node_modules/instamancer/src/cli.js';
        $instamancer .= ' user '.$accountID;
        $instamancer .= ' --file '.$json_file;
        $instamancer .= ' --count '.$count;
        $instamancer .= ' --full';
        // $instamancer .= ' --graft';
        // $instamancer .= ' --sync';
        // $instamancer .= ' --threads 6';
        $instamancer .= ' --logging debug';
        $instamancer .= ' --logfile ../wp-content/instamancer.log';

        // No longer downloading here. do it in the main scraper. 
        // This is so that consistency of JSON structure can be relied upon.
        // $instamancer .= ' --download';
        // $instamancer .= ' --downdir '.$downloads;

        $command = escapeshellcmd($instamancer);
        $return = shell_exec($command);

        return;
    }


    public function read_response_json($accountID)
    {
        $account_dir = WP_CONTENT_DIR . '/uploads/instamancer/'.$accountID.'/';
        $output_json = $account_dir.'output_' . date('Ymd') . '.json';
        $download_dir = $account_dir.'downloads/';

        if (!file_exists($output_json)){
            (new \yt\r)->new('search', 'No output.json file found in '. $account_dir);
            return;
        }

        if (filesize($output_json) < 100){
            (new \yt\r)->new('search', 'Output.json file less than 100 bytes, Probably no results. ');
            return;
        }

        $json = file_get_contents($output_json);
        $obj = json_decode($json);

        foreach($obj as $key => $item)
        {
            // Add link
            $item->shortcode_media->link = 'https://instagram.com/p/'.$item->shortcode_media->shortcode;

            // Add username
            $item->shortcode_media->username = $accountID;  

            // remove the shortcode_media bit.
            $obj[$key] = $item->shortcode_media;
        }

        $this->response->items = array_merge($this->response->items, $obj);
        (new \yt\r)->new('search', 'RESPONSE OK: '. json_encode($obj, JSON_PRETTY_PRINT));
        
        
        return;
    }



    // public function remove_local_files($accountID)
    // {
    //     $temp_dir = get_temp_dir() . 'instamancer/'.$accountID.'/';

    //     global $wp_filesystem;
    //     $wp_filesystem->delete($temp_dir, true);

    //     return;
    // }

    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │░
    // │                                                                         │░
    // │                                PRIVATE                                  │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


    public function default_count()
    {
        $count = 3;
        if ($this->config['extra_parameters'] != null){ 
            $count = $this->config['extra_parameters']; 
        }

        return $count;
    }

    public function can_run_node()
    {
        $node = 'node -v';
        $output = shell_exec($node);

        if ($output == null) {
            (new \yt\r)->last('search', 'CANNOT RUN NODE');
            return false;
        }

        return true;
    }


    public function can_run_instamancer()
    {
        $node = 'node /usr/local/lib/node_modules/instamancer/src/cli.js --version';
        $output = shell_exec($node);

        if ($output == null) {
            (new \yt\r)->last('search', 'CANNOT RUN INSTAMANCER');
            return false;
        }

        return true;
    }

    public function csv_explode()
    {
        $this->config['query_string'] = str_replace(' ', '', $this->config['query_string']);
        return explode(',', $this->config['query_string']);
    }
}
