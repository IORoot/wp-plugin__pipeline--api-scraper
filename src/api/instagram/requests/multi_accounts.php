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


    public function request()
    {

        $instagram = \InstagramScraper\Instagram::withCredentials($this->config['api_username'], $this->config['api_key'], new Psr16Adapter('Files'));
        // $instagram = new \InstagramScraper\Instagram();

        try {
            $instagram->login();
            $instagram->saveSession();
        } catch (\Exception $e) {
            (new \yt\e)->line('- \Exception calling Instagram' . $e->getMessage(), 1);
            return;
        }

        $items = array();

        foreach ($this->csv_explode() as $accountID) {

            try {
                $medias = $instagram->getMedias($accountID, $this->config['extra_parameters']);
            } catch (\Exception $e) {
                (new \yt\e)->line('- \Exception calling Instagram' . $e->getMessage(), 1);
                continue;
            }
            
            if (!$medias){ continue; }
            
            foreach($medias as $media)
            {
                $item['caption'] = $media->getCaption();
                $item['createdTime'] = $media->getCreatedTime();
                $item['imageHighResolutionUrl'] = $media->getImageHighResolutionUrl();
                $item['shortCode'] = $media->getShortCode();
                $item['isAd'] = $media->isAd();
                $item['type'] = $media->getType();
                $item['commentsCount'] = $media->getCommentsCount();
                $item['likesCount'] = $media->getLikesCount();
                $item['link'] = $media->getLink();
                $item['locationName'] = $media->getLocationName();

                $account = $media->getOwner();
                $item['username'] = $account->getUsername();

                $this->response->items[] = $item;

                (new \yt\r)->last('search', 'RESPONSE:'. json_encode($item, JSON_PRETTY_PRINT));
            }

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

    public function csv_explode()
    {
        $this->config['query_string'] = str_replace(' ', '', $this->config['query_string']);
        return explode(',', $this->config['query_string']);
    }
}
