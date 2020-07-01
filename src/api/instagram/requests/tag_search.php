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

    public function request()
    {
        $this->ig = \InstagramScraper\Instagram::withCredentials($this->config['api_username'], $this->config['api_key'], new Psr16Adapter('Files'));

        try {
            $this->ig->login();
        } catch (\Exception $e) {
            (new \yt\e)->line('- \Exception calling Instagram' . $e->getMessage(), 1);
            return;
        }

        $items = array();

        foreach ($this->csv_explode() as $hashtag) {

            $medias = $this->get_paged_tag($hashtag);
        
            if (!$medias){ continue; }

            $this->loop_medias($medias['medias']);

        }

        return true;
    }



    public function get_paged_tag($hashtag, $maxId = null)
    {

        try {
            $medias = $this->ig->getPaginateMediasByTag($hashtag);
        } catch (\Exception $e) {
            (new \yt\e)->line('- \Exception calling Instagram' . $e->getMessage(), 1);
            return $e;
        }

        return $medias;
    }


    public function loop_medias($medias)
    {
        if (!$medias){ return; }
        
        $count = 0;

        foreach($medias as $media)
        {

            if ($count > $this->config['extra_parameters'])
            {
                break;
            }

            $item['caption'] = $media->getCaption();
            $item['createdTime'] = $media->getCreatedTime();
            $item['imageHighResolutionUrl'] = $media->getImageHighResolutionUrl();
            $item['shortCode'] = $media->getShortCode();
            $item['isAd'] = $media->isAd();
            $item['type'] = $media->getType();
            $item['commentsCount'] = $media->getCommentsCount();
            $item['likesCount'] = $media->getLikesCount();
            $item['videoViews'] = $media->getvideoViews();
            $item['link'] = $media->getLink();
            $item['locationName'] = $media->getLocationName();

            $account = $media->getOwner();
            $item['username'] = $account->getUsername();
            $item['profilePicUrl'] = $account->getProfilePicUrl();

            $this->response->items[] = $item;

            (new \yt\r)->last('search', 'RESPONSE:'. json_encode($item, JSON_PRETTY_PRINT));

            $count++;
        }


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
