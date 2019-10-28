<?php


class getVideo {

    /**
     * Traits
     * 
     * Use the 'item' wrappers to help with images, media types, etc...
     */
    use youtubeVideo;
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
    public $classname = 'getVideo';


    /**
     * $current_item
     *
     * @var undefined
     */
    private $new_item;

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
        
        // Quota costs:
        // Snippet : 2.
        $url = "https://www.googleapis.com/youtube/v3/videos?part=snippet&maxResults=1&id=".$params."&key=".$api_key;

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
                
                $this->new_item = array (
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
                    'embed'             => $this->getEmbed($item),
                );

                // Push all image sizes onto the array.
                $this->getImageArray($item);

                array_push($this->output_array, $this->new_item);

            }

            return $this->output_array;

        }

        return false;

    }
    
}