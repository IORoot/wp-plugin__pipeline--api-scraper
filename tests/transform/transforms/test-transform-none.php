<?php
/**
 * Class SampleTest
 *
 * @package Andyp_youtube_scraper_v2
 */

/**
 * Sample test case.
 */
class transformNoneTest extends WP_UnitTestCase {

	public function setUp()
    {
        parent::setUp();

        $this->class_instance = new \yt\transform\none;
	}
	

	/**
	 * Is there a description?
	 */
	public function test_description() {

		$expected = "Does nothing.";
		$got = $this->class_instance->description;

		$this->assertEquals($expected, $got);
	}
	
	/**
	 * Are there parameters?
	 */
	public function test_parameters() {

		$expected = "None";
		$got = $this->class_instance->parameters;

		$this->assertEquals($expected, $got);
	}
	
	/**
	 * Can we input a value?
	 */
	public function test_input() {

		$this->class_instance->in("This is test input");

		$expected = "This is test input";
		$got = $this->class_instance->input;

		$this->assertEquals($expected, $got);
	}
	
	/**
	 * Can we input a value and get an output?
	 */
	public function test_output() {

		$this->class_instance->in("This is test for the output");

		$expected = "This is test for the output";
		$got = $this->class_instance->out();

		$this->assertEquals($expected, $got);
	}

}
