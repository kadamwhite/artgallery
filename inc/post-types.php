<?php

namespace ArtGallery\Post_Types;

use Taxonomies;

function setup() {
	add_action( 'init', __NAMESPACE__ . '\\register_post_types' );
}

/**
 * Register the custom post type for artworks.
 */
function register_post_types() {
	register_post_type( 'ag_artwork_item', array(
		'label' => 'Artworks',
		'description' => '',
		'public' => true,
		'publicly_queryable' => true,
		'query_var' => 'artwork',
		'show_in_nav_menu' => true,
		'exclude_from_search' => false,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => array(
			'slug' => 'art'
		),
		'has_archive' => true,
		'supports' => array(
			'author',
			'custom-fields',
			'comments',
			'editor',
			'title',
			'thumbnail'
		),
		'taxonomies' => array (
			'ag_artwork_availability',
			'ag_artwork_categories',
			'ag_artwork_dimensions',
			'ag_artwork_media'
		),
		'labels' => array (
			'name' => 'Artwork',
			'singular_name' => 'Artwork',
			'all_items' => 'All Artworks',
			'add_new' => 'Add Artwork',
			'add_new_item' => 'Add New Artwork',
			'edit' => 'Edit',
			'edit_item' => 'Edit Artwork',
			'new_item' => 'New Artwork',
			'view' => 'View Artwork',
			'view_item' => 'View Artwork',
			'search_items' => 'Search Artworks',
			'not_found' => 'No Artworks Found',
			'not_found_in_trash' => 'No Artworks Found in Trash',
			'parent' => 'Parent Artwork'
		)
	));

}
