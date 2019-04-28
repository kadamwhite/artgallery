<?php
/**
 * Server-rendered Artwork Availability block.
 */
namespace ArtGallery\Blocks\Availability;

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
	if ( empty( $attributes['status'] ) || empty( $attributes['message'] ) ) {
		return null;
	}
	if ( $attributes['status'] !== 'Available' ) {
		return null;
	}
	return wp_kses_post( '<p>' . $attributes['message'] . '</p>' );
}

/**
 * Register the Featured Items List dynamic block.
 */
function register_block() {
	register_block_type( 'artgallery/availability', [
		'attributes' => [
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
