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

        if (!$this->can_run_node()) {
            return;
        }
        if (!$this->can_run_instamancer()) {
            return;
        }

        $this->config['query_string'] = str_replace(' ', '', $this->config['query_string']);

        $this->run_instamancer($this->config['query_string']);
        $this->read_response_json();
        // $this->delete_json_file();

        return true;
    }


    public function run_instamancer($accountCSV)
    {
        $temp_dir = WP_CONTENT_DIR . '/uploads/instamancer/';
        
        if (!file_exists($temp_dir)) {
            mkdir($temp_dir , 0777, true);
        }
        
        $logfile =   $temp_dir. 'debug/instamancer_multiAccounts_'.date('Ymdh').'.json';
        $json_file = $temp_dir.'output_' . date('Ymd') . '.json';
        $downloads = $temp_dir.'downloads/';
        $count = $this->default_count();
        
        $instamancer = 'node';
        $instamancer .= ' /usr/local/lib/node_modules/instamancer/src/cli.js';
        $instamancer .= ' users '.$accountCSV;
        $instamancer .= ' --file '.$json_file;
        $instamancer .= ' --count '.$count;
        $instamancer .= ' --full';
        $instamancer .= ' --screenshots';
        $instamancer .= ' --user '. $this->config['api_username'];
        $instamancer .= ' --pass '. $this->config['api_key'];
        $instamancer .= ' --logging error';
        $instamancer .= ' --logfile ' . $logfile;

        // delete all screenshots / JSON older than 2 days
        shell_exec('find '.$temp_dir.' -type f -mmin +2880 -delete');

        $command = escapeshellcmd($instamancer);

        (new \yt\e)->line('Instamancer command:'. $command);

        shell_exec($command . ' 2>&1');

        // (new \yt\e)->line('Instamancer returned:'. $return);
        (new \yt\e)->line('Instamancer logfile:' . $logfile);

        return;
    }


    public function read_response_json()
    {
        $account_dir = WP_CONTENT_DIR . '/uploads/instamancer/';
        $output_json = $account_dir.'output_' . date('Ymd') . '.json';
        $download_dir = $account_dir.'downloads/';

        if (!file_exists($output_json)){
            (new \yt\e)->line('[search] No output.json file found in '. $account_dir);
            return;
        }

        if (filesize($output_json) < 100){
            (new \yt\e)->line('[search] Output.json file less than 100 bytes, Probably no results. ');
            return;
        }

        $json = file_get_contents($output_json);
        $obj = json_decode($json);

        foreach($obj as $key => $item)
        {
            // Add link
            $item->shortcode_media->link = 'https://instagram.com/p/'.$item->shortcode_media->shortcode;

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
            (new \yt\e)->line('[search] CANNOT RUN NODE');
            return false;
        }

        return true;
    }


    public function can_run_instamancer()
    {
        $node = 'node /usr/local/lib/node_modules/instamancer/src/cli.js --version';
        $output = shell_exec($node);

        if ($output == null) {
            (new \yt\e)->line('[search] CANNOT RUN INSTAMANCER');
            return false;
        }

        return true;
    }

    
    public function csv_explode()
    {
        $this->config['query_string'] = str_replace(' ', '', $this->config['query_string']);
        return explode(',', $this->config['query_string']);
    }


    public function delete_json_file()
    {

        $account_dir = WP_CONTENT_DIR . '/uploads/instamancer/';
        $output_json = $account_dir.'output_' . date('Ymd') . '.json';

        if (file_exists($output_json)){
            (new \yt\e)->line('search - FILE DELETED:'. $output_json);
            @unlink($output_json);   // clean up
            return;
        }

        return;
    }
}
