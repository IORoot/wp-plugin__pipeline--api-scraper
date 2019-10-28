<?php

//  ┌─────────────────────────────────────────────────────────────────────────┐ 
//  │                                                                         │░
//  │       These are wrappers to use on a ITEM model from InstagramAPI       │░
//  │                                                                         │░
//  └─────────────────────────────────────────────────────────────────────────┘░
//   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

trait youtubePlaylists {

    public function getID($item){
        return $item->id;
    }

    /**
     * getUser
     *
     * @param mixed $item
     * @return void
     */
    public function getUser($item){
        return $item->snippet->channelTitle;
    }

    /**
     * getProfilePic
     *
     * @param mixed $item
     * @return void
     */
    public function getProfilePic($item){
        return 'n/a';
    }

    /**
     * getTitle
     *
     * @param mixed $item
     * @return void
     */
    public function getTitle($item){
        return $item->snippet->title;
    }

    /**
     * getDescription
     *
     * @param mixed $item
     * @return void
     */
    public function getDescription($item){

        $description = $this->filter_newlines_to_br($item->snippet->description);
        $description = $this->filter_pseudo_markdown($description);

        return $description;
    }

    /**
     * getURL
     *
     * @param mixed $item
     * @return void
     */
    public function getURL($item){
        return 'https://www.youtube.com/playlist?list='. $item->id;
    }

    /**
     * getDate
     *
     * @param mixed $item
     * @return void
     */
    public function getDate($item){
        return strtotime($item->snippet->publishedAt);
    }

    /**
     * getCommentCount
     *
     * @param mixed $item
     * @return void
     */
    public function getCommentCount($item){
        return 'n/a';
    }

    /**
     * getLikeCount
     *
     * @param mixed $item
     * @return void
     */
    public function getLikeCount($item){
        return 'n/a';
    }

    /**
     * getImage
     *
     * @param mixed $item
     * @return void
     */
    public function getImage($item){

        // 'high' first.
        $return_image = $item->snippet->thumbnails->high->url;

        // If 
        if (isset($item->snippet->thumbnails->standard->url)){
            $return_image = $item->snippet->thumbnails->standard->url;
        }

        return $return_image;

    }

    /**
     * getType
     *
     * @param mixed $item
     * @return void
     */
    public function getType($item){
         return 'playlist';
    }


}