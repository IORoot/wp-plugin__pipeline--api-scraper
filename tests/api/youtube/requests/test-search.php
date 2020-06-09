<?php
/**
 * Class SearchTest
 *
 * @package Andyp_youtube_scraper_v2
 */


/**
 * @testdox Testing the \yt\api class
 */
class searchTest extends WP_UnitTestCase {

    /**
     * @before
     */
	public function setup()
    {
        parent::setUp();
	}

    
	/** 
	 * @test
     * 
	 */
	public function test_search_class_exists() {

        $got = new \yt\youtube\request\search;

		$this->assertIsObject($got);
    }
    
	/** 
	 * @test
     * 
	 */
	public function test_search_can_send_to_youtube() {

        $search = new \yt\youtube\request\search;
        $search->config(
            array(
                'api_key' => 'dummy',
                'query_string' => 'part=snippet&maxResults=50&type=video&order=viewCount&q=parkour%20%7C%20freerunning -Roblox -Fortnite -Fortnight -Minecraft -crossfire -tank -tanks -tanki -gameplay -GTA5 -GTA -Gaming -lego -streamlabs -playstation -xbox -nintendo -BlockStarPlanet -Warzone -ToyChamp -PUBG -PS3 -PS4 -Logitech -CFQQ -"Black Mesa" -speedrun -JKokki -anhdagia -Nastix -Đinh -Hambles -Usagii -TheBraxXter&publishedAfter=2020-06-07T14%3A56%3A40%2B01%3A00',
                'extra_parameters' => null,
            )
        );

        $expect = 'https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=50&type=video&order=viewCount&q=parkour%20%7C%20freerunning -Roblox -Fortnite -Fortnight -Minecraft -crossfire -tank -tanks -tanki -gameplay -GTA5 -GTA -Gaming -lego -streamlabs -playstation -xbox -nintendo -BlockStarPlanet -Warzone -ToyChamp -PUBG -PS3 -PS4 -Logitech -CFQQ -"Black Mesa" -speedrun -JKokki -anhdagia -Nastix -Đinh -Hambles -Usagii -TheBraxXter&publishedAfter=2020-06-07T14%3A56%3A40%2B01%3A00&key=dummy'; 
        
        $search->build_request_url();

        $got = $search->built_request_url;

		$this->assertEquals($expect, $got);
    }

    /** 
	 * @test
     * 
	 */
	public function test_search_can_filter_minecraft() {
        
        $filter = new \yt\filter;

        $scrape_filter = array(
            'yt_filter_id' => 'dummy filter',
            'yt_filter_layers' => array(
                array(
                        'yt_filter' => 'remove_item_if_regex',
                        'yt_filter_parameters' => '[\'item_field\' => \'snippet->title\', \'regex\' => \'/(minecraft)/i\' ]' 
                    )
            )
        );

        $filter->set_filter_group($scrape_filter);

        $json = file_get_contents(__DIR__."/youtube_response.json");

        $filter->set_item_collection(json_decode($json));

        $result_collection = $filter->run();

        $found_minecraft = false;

        foreach ($result_collection->items as $item)
        {
            $title = $item->snippet->title;

            $match = preg_match('/minecraft/i',$title);

            if ($match !== 0)
            {
                error_log($title);
                $found_minecraft = true;
            }
            
        }

        $this->assertEquals($found_minecraft, false);

    }
    
}
