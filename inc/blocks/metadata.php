<?php
/**
 * Server-rendered Artwork Dimensions & Materials block.
 */
namespace ArtGallery\Blocks\Metadata;

use ArtGallery\Meta;
use ArtGallery\Taxonomies;

const BLOCK_NAME = 'artgallery/metadata';

function setup() {
	add_action( 'init', __NAMESPACE__ . '\\register_block' );
	add_filter( 'render_block', __NAMESPACE__ . '\\disable_wpautop', 10, 2 );
}

/**
 * Render an availability message if the artwork is for sale.
 *
 * @param array $attributes The block attributes.
 * @return string The rendered block markup, as an HTML string.
 */
function render_artwork_metadata( array $attributes = [] ) {
	global $post;
	$artwork_id = $post->ID;

	$date = Meta\get_artwork_date( $artwork_id, $attributes['date'] ?? null );

	$term_links = Taxonomies\get_media_list( $artwork_id, true );

	$dimensions = Meta\get_artwork_dimensions( $artwork_id, $attributes );

	$block_output = '';

	if ( ! empty( $date ) ) {
		$block_output .= $date . '. ';
	}
	if ( ! empty( $dimensions ) ) {
		$block_output .= $dimensions;
		if ( ! empty( $term_links ) ) {
			$block_output .= '; ';
		}
	}
	if ( ! empty( $term_links ) ) {
		$block_output .= $term_links . '.';
	}

	return ! empty( $block_output ) ?
		'<p class="artwork-meta">' . $block_output . '</p>' :
		null;
}

/**
 * Register the Featured Items List dynamic block.
 */
function register_block() {
	register_block_type( BLOCK_NAME, [
		'attributes' => [
			'width' => [
				'type'    => 'number',
				'default' => 0,
			],
			'height' => [
				'type'    => 'number',
				'default' => 0,
			],
			'depth' => [
				'type'    => 'number',
				'default' => 0,
			],
			'date' => [
				'type'    => 'string',
				'default' => null,
			],
		],
		'render_callback' => __NAMESPACE__ . '\\render_artwork_metadata',
	] );
}

/**
 * Turn off wpautop and wptexturize filters when rendering this block.
 *
 * @link https://wordpress.stackexchange.com/q/321662/26317
 *
 * @param string $block_content The HTML generated for the block.
 * @param array  $block         The block.
 */
function disable_wpautop( string $block_content, array $block ) {
	if ( BLOCK_NAME === $block['blockName'] ) {
		remove_filter( 'the_content', 'wpautop' );
		remove_filter( 'the_content', 'wptexturize' );
	}

	return $block_content;
}
