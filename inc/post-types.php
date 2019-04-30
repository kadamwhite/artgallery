<?php

namespace ArtGallery\Post_Types;

use ArtGallery\Taxonomies;
use WP_Query;

const ARTWORK_POST_TYPE = 'ag_artwork_item';

function setup() {
	add_action( 'init', __NAMESPACE__ . '\\register_post_types' );

	// Default posts to "nfs" if saved without an availability state
	add_action( 'save_post', __NAMESPACE__ . '\\set_default_terms', 20, 2 );
}

/**
 * Register the custom post type for artworks.
 *
 * @return void
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
		'show_in_rest'        => true,
		'rest_base'           => 'artworks',
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
		'template' => [
			[ 'core/image' ],
			[ 'artgallery/metadata' ],
			[ 'artgallery/availability' ],
		],
	] );

}

/**
 * When an artwork item is saved, assign any default terms for unpopulated
 * taxonomies supported by this post type.
 *
 * Based on work by Michael Fields, John P. Bloch, and Evan Mulins.
 *
 * @param int     $post_id The ID of the post being saved.
 * @param WP_Post $post    The post object being saved.
 *
 * @return void
 */
function set_default_terms( int $post_id, WP_Post $post ) {
	// Verify that we're publishing an artwork item, and not something else
	if ( 'publish' === $post->post_status && $post->post_type === ARTWORK_POST_TYPE ) {
		/* Default terms by taxonomy:
		*
		* Availability: Not For Sale
		* Media: (none)
		* Dimensions: (none)
		* Category: (none)
		*/
		$defaults = [];
		$defaults[ Taxonomies\AVAILABILITY_TAXONOMY ] = [ 'nfs' ];

		// Get the taxonomies available for this post type
		$taxonomies = (array) get_object_taxonomies( $post->post_type );

		foreach ( $taxonomies as $taxonomy ) {
			$terms = wp_get_post_terms( $post_id, $taxonomy );
			if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
				wp_set_object_terms( $post_id, $defaults[ $taxonomy ], $taxonomy );
			}
		};
	}
}

/**
 * Utility method to retrieve all artwork items, then do something to/with each one.
 *
 * @param callable $callback Function that will be passed each artwork post object.
 *
 * @return void
 */
function for_all_artworks( callable $callback ) {
	$artworks = new WP_Query( [
		'post_type'   => [ ARTWORK_POST_TYPE ],
		'post_status' => [ 'any' ],
		'nopaging'    => true,
	] );

	if ( $artworks->have_posts() ) {
		while ( $artworks->have_posts() ) {
			$artworks->the_post();

			$callback( get_post() );
		}
	}
}
