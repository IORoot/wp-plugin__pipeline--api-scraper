<?php

//  ┌─────────────────────────────────────────────────────────────────────────┐ 
//  │                                                                         │░
//  │       These are wrappers to use on a ITEM model from InstagramAPI       │░
//  │                                                                         │░
//  └─────────────────────────────────────────────────────────────────────────┘░
//   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

trait instagramItems {

    public function getID($item){
        return $item->getPk();
    }

    /**
     * getUser
     *
     * @param mixed $item
     * @return void
     */
    public function getUser($item){
        return $item->getUser()->getUsername();
    }

    /**
     * getProfilePic
     *
     * @param mixed $item
     * @return void
     */
    public function getProfilePic($item){
        return $item->getUser()->getProfilePicUrl();
    }

    /**
     * getCaption
     *
     * @param mixed $item
     * @return void
     */
    public function getTitle($item){

        $title = $this->filter_first_sentence_in_paragraph($item->getCaption()->getText());

        return $title;
    }

    /**
     * getCaption
     *
     * @param mixed $item
     * @return void
     */
    public function getDescription($item){

        $description = $this->filter_instagram_newline_to_br($item->getCaption()->getText());
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
        return 'https://instagram.com/p/'. $item->getCode();
    }

    /**
     * getDate
     *
     * @param mixed $item
     * @return void
     */
    public function getDate($item){
        return $item->getTakenAt();
    }


    /**
     * getTimeAgo
     * 
     * Uses the 'helpers' trait to caclulate the time ago.
     *
     * @param mixed $item
     * @return void
     */
    public function getTimeAgo($item){
        return $this->timeago( date('Y-m-d H:i:s', $item->getTakenAt()));
    }

    /**
     * getCommentCount
     *
     * @param mixed $item
     * @return void
     */
    public function getCommentCount($item){
        return $item->getCommentCount();
    }

    /**
     * getLikeCount
     *
     * @param mixed $item
     * @return void
     */
    public function getLikeCount($item){
        return $item->getLikeCount();
    }

    /**
     * getImage
     *
     * @param mixed $item
     * @return void
     */
    public function getImage($item){
        
        // Check Media Type.
        switch( $item->getMediaType() ){

            // Photos
            case 1:
                return $item->getImageVersions2()->getCandidates()[0]->getUrl();
            
            // Video
            case 2:
            return $item->getImageVersions2()->getCandidates()[0]->getUrl();

            // Carousel of photos / videos
            case 8:
                return $item->getCarouselMedia()[0]->getImageVersions2()->getCandidates()[0]->getUrl();

            // Anything else
            default:
                return '';

        }
    }

    /**
     * getSmallImage
     *
     * @param mixed $item
     * @return void
     */
    public function getSmallImage($item){

        // Check Media Type.
        switch( $item->getMediaType() ){

            // Photos
            case 1:
                return $item->getImageVersions2()->getCandidates()[1]->getUrl();
            
            // Video
            case 2:
            return $item->getImageVersions2()->getCandidates()[1]->getUrl();

            // Carousel of photos / videos
            case 8:
                return $item->getCarouselMedia()[0]->getImageVersions2()->getCandidates()[1]->getUrl();

            // Anything else
            default:
                return '';

        }
    }

    /**
     * getType
     *
     * @param mixed $item
     * @return void
     */
    public function getType($item){

        switch( $item->getMediaType() ){
            case 1:
                return 'photos';
            case 2:
                return 'video';
            case 8:
                return 'carousel';
            default:
                return 'unclassified';
        }
    }


}