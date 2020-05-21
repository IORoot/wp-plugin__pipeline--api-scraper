<?php
/**
 * Class SampleTest
 *
 * @package Andyp_youtube_scraper_v2
 */



/**
 * @testdox Testing the \yt\api class
 */
class apiTest extends WP_UnitTestCase {

    /**
     * @before
     */
	public function setup()
    {
        parent::setUp();
	}
    
    // ┌─────────────────────────────────────────────────────────────────────────┐ 
    // │                                                                         │░
    // │                                                                         │░
    // │                              \yt\api class                              │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
    
	/** 
	 * @test
     * 
	 */
	public function test_api_class_exists() {

        $got = new \yt\api;

		$this->assertIsObject($got);
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐ 
    // │                                                                         │░
    // │                                                                         │░
    // │                             Setup for methods                           │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


    /**
     * @before
     * 
     * setup with an instance of the class.
     */
	public function setup_methods()
    {
        parent::setUp();
        $this->instance = new \yt\api;
    }
    
    // ┌─────────────────────────────────────────────────────────────────────────┐ 
    // │                                                                         │░
    // │                                                                         │░
    // │                          set_api_key() method                           │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
    
	/** 
	 * @test
     * 
	 */
	public function test_setApiKey_no_value() {

        $expect = false;
        $actual = $this->instance->set_api_key();

		$this->assertEquals($expect, $actual);
    }
    
	/** 
	 * @test
	 */
	public function test_setApiKey_cannot_be_blank() {

        $expect = false;
        $actual = $this->instance->set_api_key('');

		$this->assertEquals($expect, $actual);
    }
    
	/** 
	 * @test
	 */
	public function test_setApiKey_can_be_set() {

        $expect = true;
        $actual = $this->instance->set_api_key('abc123');

		$this->assertEquals($expect, $actual);
    }
    
	/** 
	 * @test
	 */
	public function test_setApiKey_set_value() {

        $expect = 'abc123';
        $this->instance->set_api_key('abc123');
        $actual = $this->instance->config['api_key'];

		$this->assertEquals($expect, $actual);
    }

    // ┌─────────────────────────────────────────────────────────────────────────┐ 
    // │                                                                         │░
    // │                                                                         │░
    // │                       set_substitutions() method                        │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

	/** 
	 * @test
	 */
	public function test_setSubstitutions_no_value() {

        $expect = false;
        $actual = $this->instance->set_substitutions();

		$this->assertEquals($expect, $actual);
    }

    /** 
	 * @test
	 */
	public function test_setSubstitutions_cannot_be_blank() {

        $expect = false;
        $actual = $this->instance->set_substitutions('');

		$this->assertEquals($expect, $actual);
    }
    
	/** 
	 * @test
	 */
	public function test_setSubstitutions_can_be_set() {

        $actual = $this->instance->set_substitutions('abc123');
		$this->assertIsObject($actual);
    }
    
	/** 
	 * @test
	 */
	public function test_setSubstitutions_set_value() {

        $expect = 'abc123';
        $this->instance->set_substitutions('abc123');
        $actual = $this->instance->substitutions;

		$this->assertEquals($expect, $actual);
    }

	/** 
	 * @test
	 */
	public function test_setSubstitutions_set_as_array() {

        $expect = [
            0 => [
                "yt_search_substitutions_word" => "last24hours",
                "yt_search_substitutions_replace" => "&publishedAfter={{date=-24 hours}}",
            ]
        ];

        $this->instance->set_substitutions($expect);
        $actual = $this->instance->substitutions;

		$this->assertEquals($expect, $actual);
    }

	/** 
	 * @test
	 */
	public function test_setSubstitutions_set_as_multi_array() {

        $expect = [
            0 => [
                "yt_search_substitutions_word" => "last24hours",
                "yt_search_substitutions_replace" => "&publishedAfter={{date=-24 hours}}",
            ],
            1 => [
                "yt_search_substitutions_word" => "last10mins",
                "yt_search_substitutions_replace" => "&publishedAfter={{date=-10 minutes}}",
            ]
        ];

        $this->instance->set_substitutions($expect);
        $actual = $this->instance->substitutions;

		$this->assertEquals($expect, $actual);
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐ 
    // │                                                                         │░
    // │                                                                         │░
    // │                       set_search_config() method                        │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


	/** 
	 * @test
	 */
	public function test_setSearchConfig_no_value() {

        $expect = false;
        $actual = $this->instance->set_search_config();

		$this->assertEquals($expect, $actual);
    }

    /** 
	 * @test
	 */
	public function test_setSearchConfig_cannot_be_blank() {

        $expect = false;
        $actual = $this->instance->set_search_config('');

		$this->assertEquals($expect, $actual);
    }
    
	/** 
	 * @test
	 */
	public function test_setSearchConfig_will_not_accept_string() {

        $expect = false;
        $actual = $this->instance->set_search_config('abc123');
		$this->assertEquals($actual, $expect);
    }

	/** 
	 * @test
	 */
	public function test_setSearchConfig_must_be_array() {

        $expect = null;
        $input = array('abc', '123');
        $actual = $this->instance->set_search_config($input);
		$this->assertEquals($actual, $expect);
    }

	/** 
	 * @test
	 */
	public function test_setSearchConfig_value_is_set() {

        $expect = array('abc', '123');
        $this->instance->set_search_config($expect);
        $actual = $this->instance->search_config;

		$this->assertEquals($actual, $expect);
    }

	/** 
	 * @test
	 */
	public function test_setSearchConfig_value_is_set_to_a_real_structure() {

        $expect = [
            'yt_search_id' => "YouTube - Daily Top 3",
            'yt_search_api' => "youtube",
            'yt_search_type' => "search",
            'yt_search_description' => "Top 3 Most viewed parkour videos in last 24 Hours",
            'yt_search_string' => "part=snippet&maxResults=50&type=video&order=viewCount&q=parkour%20%7C%20freerunning[[blacklistwords]][[gameraccounts]][[last24hours]]",
            'yt_search_parameters' => ""
        ];

        $this->instance->set_search_config($expect);
        $actual = $this->instance->search_config;

		$this->assertEquals($actual, $expect);
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐ 
    // │                                                                         │░
    // │                                                                         │░
    // │                   config_extra_parameters() method                      │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    /** 
	 * @test
	 */
	public function test_configExtraParameters_returns_object_instance() {

        $actual = $this->instance->config_extra_parameters();

		$this->assertIsObject($actual);
    }

    /** 
	 * @test
	 */
	public function test_configExtraParameters_converts_extraParameters_string_to_array() {

        $expect = [
            'key1' => 'value1'
        ];
        
        // Define the extra search parameters
        $extra_parameter_string = ['yt_search_parameters' => "['key1' => 'value1']"];

        // Set the parameters into the api object.
        $this->instance->set_search_config($extra_parameter_string);

        // Run the function
        $this->instance->config_extra_parameters();

        // get the result
        $actual = $this->instance->config['extra_parameters'];

		$this->assertEquals($actual, $expect);
    }
}
