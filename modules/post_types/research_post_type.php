<?php

// Register Custom Post Type
function research_post_type() {

	$labels = array(
		'name'                  => _x( 'Research', 'Post Type General Name', 'iav_sc' ),
		'singular_name'         => _x( 'Research', 'Post Type Singular Name', 'iav_sc' ),
		'menu_name'             => __( 'SC Research', 'iav_sc' ),
		'name_admin_bar'        => __( 'SC Research', 'iav_sc' ),
		'archives'              => __( 'Item Archives', 'iav_sc' ),
		'attributes'            => __( 'Item Attributes', 'iav_sc' ),
		'parent_item_colon'     => __( 'Parent Item:', 'iav_sc' ),
		'all_items'             => __( 'All Items', 'iav_sc' ),
		'add_new_item'          => __( 'Add New Item', 'iav_sc' ),
		'add_new'               => __( 'Add New', 'iav_sc' ),
		'new_item'              => __( 'New Item', 'iav_sc' ),
		'edit_item'             => __( 'Edit Item', 'iav_sc' ),
		'update_item'           => __( 'Update Item', 'iav_sc' ),
		'view_item'             => __( 'View Item', 'iav_sc' ),
		'view_items'            => __( 'View Items', 'iav_sc' ),
		'search_items'          => __( 'Search Item', 'iav_sc' ),
		'not_found'             => __( 'Not found', 'iav_sc' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'iav_sc' ),
		'featured_image'        => __( 'Featured Image', 'iav_sc' ),
		'set_featured_image'    => __( 'Set featured image', 'iav_sc' ),
		'remove_featured_image' => __( 'Remove featured image', 'iav_sc' ),
		'use_featured_image'    => __( 'Use as featured image', 'iav_sc' ),
		'insert_into_item'      => __( 'Insert into item', 'iav_sc' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'iav_sc' ),
		'items_list'            => __( 'Items list', 'iav_sc' ),
		'items_list_navigation' => __( 'Items list navigation', 'iav_sc' ),
		'filter_items_list'     => __( 'Filter items list', 'iav_sc' ),
    );
    $rewrite = array(
		'slug'                  => 'research',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => true,
	);
	$args = array(
		'label'                 => __( 'Research Type', 'iav_sc' ),
		'description'           => __( 'Research Articles, Webcasts, Podcasts', 'iav_sc' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'author', 'custom-fields', 'page-attributes', 'post-formats' ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-media-document',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'rewrite'               => $rewrite,
		'capability_type'       => 'post',
		'show_in_rest'          => true,
	);
	register_post_type( 'research_type', $args );

}
add_action( 'init', 'research_post_type', 0 );

//for custom meta box requires metabox.io
function research_fields_custom_meta( $meta_boxes ) {
	$prefix = 'iav_sc_';

	$meta_boxes[] = array(
		'id' => 'podcast-link',
        'title' => esc_html__( 'External Podcast/Video Links', 'iav_sc' ),
        'post_types' => array('research_type' ),
		'context' => 'normal',
		'priority' => 'default',
		'autosave' => 'false',
		'fields' => array(
			array(
				'id' => $prefix . 'podcast_source',
				'type' => 'url',
				'name' => esc_html__( 'Podcast Link', 'iav_sc' ),
			),
			array(
				'id' => $prefix . 'video_link',
				'type' => 'url',
				'name' => esc_html__( 'Webcast/Video URL', 'iav_sc' ),
			),
		),
	);

	return $meta_boxes;
}
add_filter( 'rwmb_meta_boxes', 'research_fields_custom_meta' );