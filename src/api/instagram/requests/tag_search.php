<?php

namespace yt\instagram\request;

use yt\interfaces\requestInterface;
use yt\instagram\response;

# PostAddictMe
use Phpfastcache\Helper\Psr16Adapter;
class tag_search implements requestInterface
{
    public $nice_name = "IG Hashtag Search";

    public $description = "Performs a search on a single hashtag";

    public $parameters = 'none';

    public $cost = 0;

    public $config = [
        'api_key' => null,
        'api_username' => null,
        'query_string' => null,
        'extra_parameters' => null,
    ];

    public $response;

    public $limit = 0;

    public $ig;


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

        foreach ($this->csv_explode() as $hashtag) {

            if (!$this->can_run_node()) {
                break;
            }
            if (!$this->can_run_instamancer()) {
                break;
            }

            $this->run_instamancer($hashtag);
            $this->read_response_json($hashtag);
            // $this->delete_json_file($hashtag);

        }

        return true;
    }


    public function run_instamancer($hashtag)
    {
        $temp_dir = WP_CONTENT_DIR . '/uploads/instamancer/'.$hashtag.'/';
        
        if (!file_exists($temp_dir)) {
            mkdir($temp_dir , 0777, true);
        }
        
        $logfile = WP_CONTENT_DIR . '/instamancer.json';

        $screenshot_dir = '/tmp/instamancer';

        $json_file = $temp_dir.'output_' . date('Ymd') . '.json';
        $count = $this->default_count();
        
        $instamancer = 'node';
        $instamancer .= ' /usr/local/lib/node_modules/instamancer/src/cli.js';
        $instamancer .= ' hashtag '.$hashtag;
        $instamancer .= ' --file '.$json_file;
        $instamancer .= ' --count '.$count;
        $instamancer .= ' --full';
        $instamancer .= ' --screenshots';
        $instamancer .= ' --screenshotPath ' . $screenshot_dir;
        $instamancer .= ' --user '. $this->config['api_username'];
        $instamancer .= ' --pass '. $this->config['api_key'];
        $instamancer .= ' --logging error';
        $instamancer .= ' --logfile ' . $logfile;

        if (isset($this->config['extra_parameters']['proxy'])) {
            $instamancer .= ' --proxyURL ' . $this->config['extra_parameters']['proxy'];
        }

        // delete all Uploads older than 2 days
        shell_exec('find '.$temp_dir.' -type f -mmin +2880 -delete');

        // delete all old screenshots
        shell_exec('find '.$screenshot_dir.' -mmin +2880 -delete');

        $command = escapeshellcmd($instamancer);

        (new \yt\e)->line('Instamancer command:'. $command);

        shell_exec($command . ' 2>&1');

        (new \yt\e)->line('Instamancer logfile:' . $logfile);

        return;
    }


    public function read_response_json($hashtag)
    {
        $account_dir = WP_CONTENT_DIR . '/uploads/instamancer/'.$hashtag.'/';
        $output_json = $account_dir.'output_' . date('Ymd') . '.json';

        if (!file_exists($output_json)){
            (new \yt\e)->line('search - No output.json file found in '. $account_dir);
            return;
        }

        if (filesize($output_json) < 100){
            (new \yt\e)->line('search - Output.json file less than 100 bytes, Probably no results. ');
            return;
        }

        $json = file_get_contents($output_json);
        $obj = json_decode($json);

        foreach($obj as $key => $item)
        {
            // Add link
            $item->shortcode_media->link = 'https://instagram.com/p/'.$item->shortcode_media->shortcode;

            // Add username
            $item->shortcode_media->username = $hashtag;  

            // remove the shortcode_media bit.
            $obj[$key] = $item->shortcode_media;
        }

        $this->response->items = array_merge($this->response->items, $obj);
        
        return;
    }


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
        if ($this->config['extra_parameters']['count'] != null){ 
            $count = $this->config['extra_parameters']['count']; 
        }

        return $count;
    }

    public function can_run_node()
    {
        $node = 'node -v';
        $output = shell_exec($node);

        if ($output == null) {
            (new \yt\e)->line('search - CANNOT RUN NODE');
            return false;
        }

        return true;
    }


    public function can_run_instamancer()
    {
        $node = 'node /usr/local/lib/node_modules/instamancer/src/cli.js --version';
        $output = shell_exec($node);

        if ($output == null) {
            (new \yt\e)->line('search - CANNOT RUN INSTAMANCER');
            return false;
        }

        return true;
    }

    public function csv_explode()
    {
        $this->config['query_string'] = str_replace(' ', '', $this->config['query_string']);
        return explode(',', $this->config['query_string']);
    }

    public function delete_json_file($hashtag)
    {

        $account_dir = WP_CONTENT_DIR . '/uploads/instamancer/'.$hashtag.'/';
        $output_json = $account_dir.'output_' . date('Ymd') . '.json';

        if (!file_exists($output_json)){
            return;
        }

        (new \yt\e)->line('search - FILE DELETED:'. $output_json);
        @unlink($output_json);   // clean up
        return;
        

        return;
    }
}
