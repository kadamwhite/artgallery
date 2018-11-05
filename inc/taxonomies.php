<?php

namespace ArtGallery\Taxonomies;

function setup() {
	add_action( 'init', __NAMESPACE__ . '\\register_taxonomies' );
}

/**
 * Register the custom taxonomies that interface with artworks.
 */
function register_taxonomies() {

	register_taxonomy( 'ag_artwork_availability', null, array(
		'hierarchical' => false,
		'show_admin_column' => true,
		// This is pure metadata, user shouldn't edit options directly
		'show_in_nav_menus' => false,
		'show_tagcloud' => false,
		'show_ui' => false,
		// Queryable for searching
		'query_var' => true,
		'rewrite' => array(
			'slug' => 'art/availability'
		),
		// Labels
		'label' => 'Availability'
	));

	register_taxonomy( 'ag_artwork_categories', null, array(
		'hierarchical' => true,
		'label' => 'Categories',
		'singular_label' => 'Category',
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array(
			'slug' => 'art/category'
		),
		'labels' => array (
			'add_new_item' => 'Add New Category',
			'singular_name' => 'Category'
		)
	));

	register_taxonomy( 'ag_artwork_dimensions', null, array(
		'hierarchical' => true,
		'label' => 'Dimensions',
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array(
			'slug' => 'art/dimensions'
		),
		'labels' => array (
			'add_new_item' => 'Add New Dimensions'
		)
	));

	register_taxonomy( 'ag_artwork_media', null, array(
		'hierarchical' => true,
		'label' => 'Media',
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array(
			'slug' => 'art/media'
		),
		'labels' => array (
			'add_new_item' => 'Add New Medium',
			'singular_name' => 'Medium'
		)
	));

}
