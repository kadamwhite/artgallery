<?php

namespace ArtGallery\Taxonomies;

function setup() {
	add_action( 'init', __NAMESPACE__ . '\\register_taxonomies' );
}

const ARTWORK_CATEGORIES_TAXONOMY = 'ag_artwork_categories';
const AVAILABILITY_TAXONOMY       = 'ag_artwork_availability';
const DIMENSIONS_TAXONOMY         = 'ag_artwork_dimensions';
const MEDIA_TAXONOMY              = 'ag_artwork_media';

/**
 * Register the custom taxonomies that interface with artworks.
 */
function register_taxonomies() {

	register_taxonomy( AVAILABILITY_TAXONOMY, null, [
		'hierarchical'      => false,
		'label'             => 'Availability',
		'show_admin_column' => true,
		// This is pure metadata, user shouldn't edit options directly
		'show_in_nav_menus' => false,
		'show_tagcloud'     => false,
		'show_ui'           => false,
		// Queryable for searching
		'query_var'         => true,
		'rewrite'           => [ 'slug' => 'art/availability' ],
	] );

	register_taxonomy( ARTWORK_CATEGORIES_TAXONOMY, null, [
		'label'             => 'Categories',
		'singular_label'    => 'Category',
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => [ 'slug' => 'art/category' ],
		'labels'            => [
			'add_new_item'  => 'Add New Category',
			'singular_name' => 'Category',
		],
	] );

	register_taxonomy( DIMENSIONS_TAXONOMY, null, [
		'hierarchical'      => true,
		'label'             => 'Dimensions',
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => [ 'slug' => 'art/dimensions' ],
		'labels'            => [
			'add_new_item' => 'Add New Dimensions',
		],
	] );

	register_taxonomy( MEDIA_TAXONOMY, null, [
		'hierarchical'      => true,
		'label'             => 'Media',
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => [ 'slug' => 'art/media' ],
		'labels'            => [
			'add_new_item'  => 'Add New Medium',
			'singular_name' => 'Medium',
		],
	] );
}
