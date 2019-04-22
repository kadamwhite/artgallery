<?php

namespace ArtGallery\Post_Types;

use ArtGallery\Taxonomies;

const ARTWORK_POST_TYPE = 'ag_artwork_item';

function setup() {
	add_action( 'init', __NAMESPACE__ . '\\register_post_types' );
}

/**
 * Register the custom post type for artworks.
 */
function register_post_types() {
	register_post_type( ARTWORK_POST_TYPE, [
		'label'               => 'Artworks',
		'description'         => '',
		'public'              => true,
		'publicly_queryable'  => true,
		'query_var'           => 'artwork',
		'show_in_nav_menu'    => true,
		'exclude_from_search' => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'capability_type'     => 'post',
		'hierarchical'        => false,
		'has_archive'         => true,
		'rewrite'  => [
			'slug' => 'art',
		],
		'supports' => [
			'author',
			'custom-fields',
			'comments',
			'editor',
			'title',
			'thumbnail',
		],
		'taxonomies' => [
			Taxonomies\ARTWORK_CATEGORIES_TAXONOMY,
			Taxonomies\AVAILABILITY_TAXONOMY,
			Taxonomies\DIMENSIONS_TAXONOMY,
			Taxonomies\MEDIA_TAXONOMY,
		],
		'labels' => [
			'name'               => 'Artwork',
			'singular_name'      => 'Artwork',
			'add_new_item'       => 'Add New Artwork',
			'add_new'            => 'Add Artwork',
			'all_items'          => 'All Artworks',
			'edit_item'          => 'Edit Artwork',
			'edit'               => 'Edit',
			'new_item'           => 'New Artwork',
			'not_found_in_trash' => 'No Artworks Found in Trash',
			'not_found'          => 'No Artworks Found',
			'parent'             => 'Parent Artwork',
			'search_items'       => 'Search Artworks',
			'view_item'          => 'View Artwork',
			'view'               => 'View Artwork',
		],
	] );

}
