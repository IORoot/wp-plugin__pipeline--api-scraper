<?php

//  ┌─────────────────────────────────────────────────────────────────────────┐ 
//  │                                                                         │░
//  │       These are wrappers to use on a ITEM model from Youtube API        │░
//  │                                                                         │░
//  └─────────────────────────────────────────────────────────────────────────┘░
//   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

trait youtubeVideos {

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

        return 'https://www.youtube.com/watch?v='. $item->snippet->resourceId->videoId;
    }


    public function getVideoID($item){

        return $item->snippet->resourceId->videoId;
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

        $return_image = 'no image found.';

        if (isset($item->snippet->thumbnails->standard->url)){
            $return_image = $item->snippet->thumbnails->standard->url;
        }

        if (isset($item->snippet->thumbnails->high->url)){
            $return_image = $item->snippet->thumbnails->high->url;
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
         return 'video';
    }


    
    /**
     * getImage
     *
     * @param mixed $item
     * @return void
     */
    public function getImageArray($item){

        $return_thumbnail_array = $item->snippet->thumbnails;

        foreach ($return_thumbnail_array as $image_name=>$image_object){

            $this->new_item['image__' . $image_name] = $image_object->url;
            $this->new_item['image__'.$image_name.'-w'] = $image_object->width;
            $this->new_item['image__'.$image_name.'-h'] = $image_object->height;

        }

        // Set the LARGEST item to the last defined.
        $last_item = end($return_thumbnail_array);
        $this->new_item['image__largest'] = $last_item->url;
        $this->new_item['image__largest-w'] = $last_item->width;
        $this->new_item['image__largest-h'] = $last_item->height;

        return;

    }
}