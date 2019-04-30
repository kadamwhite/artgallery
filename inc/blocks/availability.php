<?php
/**
 * Server-rendered Artwork Availability block.
 */
namespace ArtGallery\Blocks\Availability;

use ArtGallery\Taxonomies;

const BLOCK_NAME = 'artgallery/availability';

function setup() {
	add_action( 'init', __NAMESPACE__ . '\\register_block' );
}

/**
 * Render an availability message if the artwork is for sale.
 *
 * @param array $attributes The block attributes.
 * @return string The rendered block markup, as an HTML string.
 */
function render_availability_message( array $attributes = [] ) {
	global $post;

	// Check to see whether a status was passed in.
	$status = $attributes['status'] ?? null;

	// Retrieve the status from assigned terms if not rendering via ServerSideRender.
	if ( empty( $status ) ) {
		$assigned_terms = wp_get_post_terms( $post->ID, Taxonomies\AVAILABILITY_TAXONOMY );
		$availability = $assigned_terms[0] ?? null;
		$status = isset( $availability ) && $availability->slug;
	}

	if ( empty( $status ) || empty( $attributes['message'] ) ) {
		return null;
	}

	if ( $status !== 'available' ) {
		return null;
	}

	return '<p>' . wp_kses_post( $attributes['message'] ) . '</p>';
}

/**
 * Register the Featured Items List dynamic block.
 */
function register_block() {
	register_block_type( BLOCK_NAME, [
		'attributes' => [
			// Status is not registered on the frontend, and should not be saved in
			// post content, but it is necessary to register it here so that it may
			// be passed in to render an accurate block preview.
			'status' => [
				'type' => 'string',
			],
			'message' => [
				'type' => 'string',
				'default' => 'Contact artist for pricing.',
			],
		],
		'render_callback' => __NAMESPACE__ . '\\render_availability_message',
	] );
}
