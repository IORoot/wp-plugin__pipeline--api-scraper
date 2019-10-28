<?php


class getPlaylists {

    /**
     * Traits
     * 
     * Use the 'item' wrappers to help with images, media types, etc...
     */
    use youtubePlaylists;
    use textreplacement;


    /**
     * $returned_stream
     * 
     * This is the objects returned from the request.
     * 
     * @var undefined
     */
    public $returned_stream = [];


    /**
     * $output_array
     *
     * @var array
     */
    private $output_array = [];

    /**
     * $classname
     * 
     * Used to populate the ACF options.
     *
     * @var string
     */
    public $classname = 'getPlaylists';

    //  ┌─────────────────────────────────────────────────────────────────────────┐ 
    //  │                                                                         │░
    //  │                                 Request                                 │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
    /**
     * request
     *
     * @param mixed $api_object
     * @param mixed $params
     * @return void
     */
    public function request($api_object, $params = null, $api_key){
        
        $url = "https://www.googleapis.com/youtube/v3/playlists?part=snippet&channelId=".$params."&maxResults=50&key=".$api_key;

        $this->returned_stream = json_decode(wp_remote_fopen($url));
        
        return true;

    }



    //  ┌─────────────────────────────────────────────────────────────────────────┐ 
    //  │                                                                         │░
    //  │                                Response                                 │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
    /**
     * response
     *
     * @return void
     */
    public function response(){

        if ($this->returned_stream->items){
            // Parse each item.
            foreach($this->returned_stream->items as $key=>$item){

                $new_item = array (
                    'index'             => ++$key,
                    'id'                => $this->getID($item),
                    'username'          => $this->getUser($item),
                    'avatar'            => $this->getProfilePic($item),
                    'title'             => $this->getTitle($item),
                    'description'       => $this->getDescription($item),
                    'url'               => $this->getURL($item),
                    'date'              => $this->getDate($item),
                    'comments'          => $this->getCommentCount($item),
                    'likes'             => $this->getLikeCount($item),
                    'image'             => $this->getImage($item),
                    'type'              => $this->getType($item),
                );

                array_push($this->output_array, $new_item);

            }
        
          return $this->output_array;

        }

        return FALSE;

    }
    

}