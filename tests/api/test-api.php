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
    // │                            run() method                                 │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    /** 
	 * @test
	 */
	public function test_run_method_can_be_called() {

        $expect = '';

        $actual = $this->instance->run();

		$this->assertEquals($actual, $expect);
    }

    /** 
	 * @test
	 */
	public function test_run_method_cannot_be_called_without_config() {

        // Setup
        $search_config = [
            'yt_search_api' => "youtube",
            'yt_search_type' => "search",
        ];
        $this->instance->set_search_config($search_config);



        $expect = '';

        $actual = $this->instance->run();

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

    /** 
	 * @test
	 */
	public function test_configExtraParameters_handles_null() {

        $expect = false;
        
        // Define the extra search parameters
        $extra_parameter_string = null;

        // Set the parameters into the api object.
        $this->instance->set_search_config($extra_parameter_string);

        // Run the function
        $this->instance->config_extra_parameters();

        // get the result
        $actual = $this->instance->config['extra_parameters'];

		$this->assertEquals($actual, $expect);
    }

    // ┌─────────────────────────────────────────────────────────────────────────┐ 
    // │                                                                         │░
    // │                                                                         │░
    // │                        config_query() method                            │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    /** 
	 * @test
	 */
	public function test_configQuery_returns_object_instance() {

        $actual = $this->instance->config_query();

		$this->assertIsObject($actual);
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐ 
    // │                                                                         │░
    // │                                                                         │░
    // │                        string_to_array() method                         │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    /** 
	 * @test
	 */
	public function test_stringToArray_returns_array() {

        $expect = [
            'key1' => 'value1',
            'key2' => 'value2'
        ];

        $input = "[
            'key1' => 'value1', 
            'key2' => 'value2'
        ]";

        $actual = $this->instance->string_to_array($input);

		$this->assertEquals($actual, $expect);
    }

    //  ┌─────────────────────────────────────────────────────────────────────────┐
    //  │                                                                         │░
    //  │                                                                         │░
    //  │                     replace_any_substitutions()                         │░
    //  │                                                                         │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    /** 
	 * @test
	 */
	public function test_configQuery_replaces_substitutions() {

        //setup a substitution.
        $this->instance->set_substitutions(
            [
                0 => [ 
                    "yt_search_substitutions_word" => "test",
                    "yt_search_substitutions_replace" => "test_substitutions"
                ]
            ]
        );

        $expected = 'sub=test_substitutions';

        $this->instance->set_search_config([ 'yt_search_string' => "sub=[[test]]" ]);

        $this->instance->config_query();

        $actual = $this->instance->config['query_string'];

		$this->assertEquals($actual, $expected);
    }


    //  ┌─────────────────────────────────────────────────────────────────────────┐
    //  │                                                                         │░
    //  │                                                                         │░
    //  │                          replace_any_tokens()                           │░
    //  │                                                                         │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    /** 
	 * @test
	 */
	public function test_configQuery_replaces_date_tokens() {

        // URL_ENCODED ATOM TIME
        $expected = 'timedate=2008-08-07T00%3A00%3A00%2B01%3A00';

        $this->instance->set_search_config([ 'yt_search_string' => "timedate={{date=2008-08-07}}"]);

        $this->instance->config_query();

        $actual = $this->instance->config['query_string'];

		$this->assertEquals($actual, $expected);
    }

    //  ┌─────────────────────────────────────────────────────────────────────────┐
    //  │                                                                         │░
    //  │                                                                         │░
    //  │                                check_input()                            │░
    //  │                                                                         │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    /** 
	 * @test
	 */
	public function test_checkInput_for_good_data() {

        // URL_ENCODED ATOM TIME
        $expected = true;

        $actual = $this->instance->check_input('testinput');

		$this->assertEquals($actual, $expected);
    }

    /** 
	 * @test
	 */
	public function test_checkInput_for_empty_data() {

        // URL_ENCODED ATOM TIME
        $expected = false;

        $actual = $this->instance->check_input('');

		$this->assertEquals($actual, $expected);
    }

    /** 
	 * @test
	 */
	public function test_checkInput_for_null_data() {

        // URL_ENCODED ATOM TIME
        $expected = false;

        $actual = $this->instance->check_input(null);

		$this->assertEquals($actual, $expected);
    }

    /** 
	 * @test
	 */
	public function test_checkInput_for_no_data() {

        // URL_ENCODED ATOM TIME
        $expected = false;

        $actual = $this->instance->check_input();

		$this->assertEquals($actual, $expected);
    }

}
