<?php
/**
 * Server-rendered three-column Featured Posts block.
 */
// phpcs:disable WordPress.VIP.SlowDBQuery
// phpcs:disable WordPress.DB.SlowDBQuery
// phpcs:disable HM.Files.NamespaceDirectoryName.NameMismatch
namespace ArtGallery\Blocks\Availability;

use ArtGallery\Utilities;

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
	return '<p>' . esc_html( $attributes['message'] ) . '</p>';
}

/**
 * Register the Featured Items List dynamic block.
 */
function register_block() {
	register_block_type( Utilities\namespaced_block( 'availability' ), [
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
