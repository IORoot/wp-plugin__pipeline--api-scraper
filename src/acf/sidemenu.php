<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5f12b7d2985cf',
	'title' => 'Scraper Sidebar',
	'fields' => array(
		array(
			'key' => 'field_5f12b7da64925',
			'label' => 'Save only',
			'name' => 'yt_sidebar_saveonly',
			'type' => 'true_false',
			'instructions' => 'When this is ON you will NOT run the scrapers. Schedules WILL update. Housekeeping WILL run. Use to update fields while scrapers are enabled.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'hide_admin' => 0,
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'youtube_scraper',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'side',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
));

endif;