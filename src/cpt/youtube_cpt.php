<?php

// Register Custom Post Type
function CPT_youtube() {

	$labels = array(
		'name'                  => 'YouTube',
		'singular_name'         => 'YouTube',
		'menu_name'             => 'YouTube',
		'name_admin_bar'        => 'YouTube',
		'archives'              => 'YouTube Archives',
		'attributes'            => 'YouTube Attributes',
		'parent_item_colon'     => 'YouTube :',
		'all_items'             => 'All YouTube',
		'add_new_item'          => 'Add New YouTube',
		'add_new'               => 'Add New',
		'new_item'              => 'New YouTube',
		'edit_item'             => 'Edit YouTube',
		'update_item'           => 'Update YouTube',
		'view_item'             => 'View YouTube',
		'view_items'            => 'View YouTubes',
		'search_items'          => 'Search YouTube',
		'not_found'             => 'Not found',
		'not_found_in_trash'    => 'Not found in Trash',
		'featured_image'        => 'Featured Image',
		'set_featured_image'    => 'Set featured image',
		'remove_featured_image' => 'Remove featured image',
		'use_featured_image'    => 'Use as featured image',
		'insert_into_item'      => 'Insert into article',
		'uploaded_to_this_item' => 'Uploaded to this article',
		'items_list'            => 'YouTube list',
		'items_list_navigation' => 'YouTube list navigation',
		'filter_items_list'     => 'Filter YouTube list',
	);
	$args = array(
		'label'                 => 'YouTube',
		'description'           => 'YouTube videos for pulse.',
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', 'post-formats' ),
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