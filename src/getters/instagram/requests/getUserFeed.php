<?php


class getUserFeed {

    /**
     * Traits
     * 
     * Use the 'item' wrappers to help with images, media types, etc...
     */
    use helpers;
    use instagramItems;
    use textreplacement;

    /**
     * $returned_stream
     * 
     * This is the objects returned from the request.
     * 
     * @var undefined
     */
    public $returned_stream;


    /**
     * $output_array
     * 
     * This is the formatted output of the returned result.
     *
     * @var undefined
     */
    private $output_array = [];

    /**
     * $classname
     * 
     * Used to populate the ACF options.
     *
     * @var string
     */
    public $classname = 'getUserFeed';


    //  ┌─────────────────────────────────────────────────────────────────────────┐ 
    //  │                                                                         │░
    //  │                                 Request                                 │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
    public function request($api_object, $params = null){
        
        $this->returned_stream = $api_object->timeline->getUserFeed($params);

        return true;

    }

    //  ┌─────────────────────────────────────────────────────────────────────────┐ 
    //  │                                                                         │░
    //  │                                Response                                 │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
    public function response(){

         // Parse each item.
         foreach($this->returned_stream->getItems() as $key=>$item){

            $new_item = array (
                'index'             => ++$key,
                'id'                => $this->getID($item),
                'username'          => $this->getUser($item),
                'avatar'            => $this->getProfilePic($item),
                'title'             => $this->getTitle($item),
                'description'       => $this->getDescription($item),
                'url'               => $this->getURL($item),
                'date'              => $this->getDate($item),
                'timeago'           => $this->getTimeAgo($item),
                'comments'          => $this->getCommentCount($item),
                'likes'             => $this->getLikeCount($item),
                'image'             => $this->getImage($item),
                'type'              => $this->getType($item),
            );

            array_push($this->output_array, $new_item);

        }

        return $this->output_array; 

    }


}