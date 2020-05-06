<?php
/**
 * Class SampleTest
 *
 * @package Andyp_youtube_scraper_v2
 */

/**
 * Sample test case.
 */
class transformBestImageTest extends WP_UnitTestCase {

	public function setUp()
    {
        parent::setUp();

        $this->class_instance = new \yt\transform\best_image;
	}
	

	/**
	 * A single example test.
	 */
	public function test_sample() {

		$expected = "Take the YouTube thumbnails array and return the highest quality URL";
		$got = $this->class_instance->description;

		$this->assertEquals($expected, $got);
	}
	
}
