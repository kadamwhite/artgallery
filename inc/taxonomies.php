<?php

namespace ArtGallery\Taxonomies;

const ARTWORK_CATEGORIES_TAXONOMY = 'ag_artwork_categories';
const AVAILABILITY_TAXONOMY       = 'ag_artwork_availability';
const DIMENSIONS_TAXONOMY         = 'ag_artwork_dimensions';
const MEDIA_TAXONOMY              = 'ag_artwork_media';

/**
 * Hook namespace functions into their corresponding actions or filters.
 *
 * @return void
 */
function setup() {
	add_action( 'init', __NAMESPACE__ . '\\register_taxonomies' );
	add_action( 'artgallery_upgrade', __NAMESPACE__ . '\\populate_default_taxonomy_terms' );
}

/**
 * Register the custom taxonomies that interface with artworks.
 *
 * @return void
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
		'show_in_rest'      => true,
		'rest_base'         => 'artwork_availability',
		'rewrite'           => [ 'slug' => 'art/availability' ],
	] );

	register_taxonomy( ARTWORK_CATEGORIES_TAXONOMY, null, [
		'label'             => 'Categories',
		'singular_label'    => 'Category',
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'show_in_rest'      => true,
		'rewrite'           => [ 'slug' => 'art/category' ],
		'labels'            => [
			'add_new_item'  => 'Add New Category',
			'singular_name' => 'Category',
		],
	] );

	register_taxonomy( DIMENSIONS_TAXONOMY, null, [
		'hierarchical'      => true,
		'label'             => 'Dimensions',
		'show_ui'           => false,
		'show_admin_column' => false,
		'query_var'         => true,
		'show_in_rest'      => true,
		'rewrite'           => [ 'slug' => 'art/dimensions' ],
		'labels'            => [
			'add_new_item' => 'Add New Dimensions',
		],
	] );

	register_taxonomy( MEDIA_TAXONOMY, null, [
		'hierarchical'      => false,
		'label'             => 'Media',
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'show_in_rest'      => true,
		'rest_base'         => 'artwork_media',
		'rewrite'           => [ 'slug' => 'art/media' ],
		'labels'            => [
			'add_new_item'  => 'Add New Medium',
			'singular_name' => 'Medium',
		],
	] );
}

/**
 * Populate the Artwork Availability terms with the three permitted options:
 * "Available", "Sold", or "Not For Sale".
 *
 * @return void
 */
function populate_default_taxonomy_terms() {
	wp_insert_term( __( 'Available', 'artgallery' ), AVAILABILITY_TAXONOMY, [
		'description' => __( 'Artwork is available for purchase', 'artgallery' ),
		'slug' => 'available',
	] );

	wp_insert_term( __( 'Sold', 'artgallery' ), AVAILABILITY_TAXONOMY, [
		'description' => __( 'Artwork has been sold', 'artgallery' ),
		'slug' => 'sold',
	] );

	wp_insert_term( __( 'Not For Sale', 'artgallery' ), AVAILABILITY_TAXONOMY, [
		'description' => __( 'Artwork is not for sale', 'artgallery' ),
		'slug' => 'nfs',
	] );
}

/**
 * Get the assigned availability term slug for an artwork item.
 *
 * @param int $artwork_id The ID of an artwork item.
 *
 * @return string|null The slug of the assigned availability term, or null if no term assigned.
 */
function get_availability_slug( int $artwork_id ) : ?string {
	$assigned_terms = wp_get_post_terms( $artwork_id, AVAILABILITY_TAXONOMY );
	$availability = $assigned_terms[0] ?? null;
	if ( isset( $availability ) && $availability->slug ) {
		return $availability->slug;
	}
	return null;
}

/**
 * Return the list of media for a given post, optionally formatted as links
 * to the archive pages for those terms.
 *
 * @param int     $artwork_id An artwork post object ID.
 * @param boolean $links      (optional) Whether to render media terms as archive links.
 *
 * @return string
 */
function get_media_list( int $artwork_id, bool $links = false ) : string {
	$media = wp_get_post_terms( $artwork_id, MEDIA_TAXONOMY );
	$term_links = array_map( function( $medium ) use ( $links ) {
		if ( $links ) {
			$href = get_term_link( $medium );
			return "<a href=\"$href\">$medium->name</a>";
		}
		return $medium->name;
	}, $media );
	return implode( ', ', $term_links );
}
