<?php

namespace yt\instagram\request;

use yt\interfaces\requestInterface;
use yt\instagram\response;
use yt\instagram\request\account_info;

# PostAddictMe
use Phpfastcache\Helper\Psr16Adapter;

class multi_accounts implements requestInterface
{
    public $nice_name = "IG Multi Account Info - instamancer";

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
        
        (new \yt\r)->last('search', 'RESPONSE:'. json_encode($this->response, JSON_PRETTY_PRINT));

        return true;
    }



    public function run_instamancer($accountID)
    {
        $temp_dir = get_temp_dir() . 'instamancer/'.$accountID.'/';
        $json_file = $temp_dir.'output.json';
        $downloads = $temp_dir.'downloads/';
        $count = $this->default_count();
        
        $instamancer = 'node';
        $instamancer .= ' /usr/local/lib/node_modules/instamancer/src/cli.js';
        $instamancer .= ' user '.$accountID;
        $instamancer .= ' --file '.$json_file;
        $instamancer .= ' --count '.$count;
        $instamancer .= ' --full';
        $instamancer .= ' --graft';
        $instamancer .= ' --sync';
        $instamancer .= ' --threads 6';
        $instamancer .= ' --logging debug';
        $instamancer .= ' --logfile ../wp-content/instamancer.log';
        $instamancer .= ' --download';
        $instamancer .= ' --downdir '.$downloads;

        $command = escapeshellcmd($instamancer);
        $return = exec($command);

        return;
    }


    public function read_response_json($accountID)
    {
        $account_dir = get_temp_dir() . 'instamancer/'.$accountID.'/';
        $output_json = $account_dir.'output.json';
        $download_dir = $account_dir.'downloads/';

        $json = file_get_contents($output_json);
        $obj = json_decode($json);

        foreach($obj as $key => $item)
        {
            // Add username
            $item->shortcode_media->username = $accountID;

            // Add LOCAL filename
            $local_file = $download_dir . $item->shortcode_media->shortcode . '.jpg';
            $item->shortcode_media->filename = $local_file;

            // remove the shortcode_media bit.
            $obj[$key] = $item->shortcode_media;
        }

        $this->response->items = array_merge($this->response->items, $obj);

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
            return false;
        }

        return true;
    }


    public function can_run_instamancer()
    {
        $node = 'node /usr/local/lib/node_modules/instamancer/src/cli.js --version';
        $output = shell_exec($node);

        if ($output == null) {
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
