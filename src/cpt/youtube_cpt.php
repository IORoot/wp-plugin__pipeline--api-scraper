<?php

// Register Custom Taxonomy
function scrapercategory() {

	$labels = array(
		'name'                       => _x( 'Scraper Category', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Scraper Category', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Scraper Category', 'text_domain' ),
		'all_items'                  => __( 'All Items', 'text_domain' ),
		'parent_item'                => __( 'Parent Item', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
		'new_item_name'              => __( 'New Item Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Item', 'text_domain' ),
		'edit_item'                  => __( 'Edit Item', 'text_domain' ),
		'update_item'                => __( 'Update Item', 'text_domain' ),
		'view_item'                  => __( 'View Item', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Items', 'text_domain' ),
		'search_items'               => __( 'Search Items', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No items', 'text_domain' ),
		'items_list'                 => __( 'Items list', 'text_domain' ),
		'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => array('slug' => 'youtube', 'with_front' => false)
	);
	register_taxonomy( 'scrapercategory', array( 'youtube' ), $args );
}
add_action( 'init', 'scrapercategory', 0 );


// Register Custom Post Type
function CPT_youtube() {

	$labels = array(
		'name'                  => 'Pulse',
		'singular_name'         => 'Pulse',
		'menu_name'             => 'Pulse',
		'name_admin_bar'        => 'Pulse',
		'archives'              => 'Pulse Archives',
		'attributes'            => 'Pulse Attributes',
		'parent_item_colon'     => 'Pulse :',
		'all_items'             => 'All Pulse',
		'add_new_item'          => 'Add New Pulse',
		'add_new'               => 'Add New',
		'new_item'              => 'New Pulse',
		'edit_item'             => 'Edit Pulse',
		'update_item'           => 'Update Pulse',
		'view_item'             => 'View Pulse',
		'view_items'            => 'View Pulses',
		'search_items'          => 'Search Pulse',
		'not_found'             => 'Not found',
		'not_found_in_trash'    => 'Not found in Trash',
		'featured_image'        => 'Featured Image',
		'set_featured_image'    => 'Set featured image',
		'remove_featured_image' => 'Remove featured image',
		'use_featured_image'    => 'Use as featured image',
		'insert_into_item'      => 'Insert into article',
		'uploaded_to_this_item' => 'Uploaded to this article',
		'items_list'            => 'Pulse list',
		'items_list_navigation' => 'Pulse list navigation',
		'filter_items_list'     => 'Filter Pulse list',
	);
	$args = array(
		'label'                 => 'Pulse',
		'description'           => 'Pulse videos for pulse.',
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', 'post-formats' ),
		'taxonomies'            => array( 'scrapercategory'),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 6,
		'menu_icon'             => 'dashicons-video-alt3',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive' 			=> 'video',
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'rewrite'               => array('slug' => 'video', 'with_front' => false),
	);
	register_post_type( 'YouTube', $args );

}
add_action( 'init', 'CPT_youtube', 0 );