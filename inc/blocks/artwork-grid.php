<?php
/**
 * Server-rendered Artwork Grid block.
 */
namespace ArtGallery\Blocks\Artwork_Grid;

use ArtGallery\Markup;
use ArtGallery\Post_Types;
use WP_Query;

const BLOCK_NAME = 'artgallery/artwork-grid';

function setup() {
	add_action( 'init', __NAMESPACE__ . '\\register_block' );
}

/**
 * Register the Featured Items List dynamic block.
 */
function register_block() {
	register_block_type( BLOCK_NAME, [
		'attributes' => [
			'align' => [
				'type' => 'string',
			],
		],
		'render_callback' => __NAMESPACE__ . '\\render_artwork_grid',
	] );
}

/**
 * Filter the "sizes" attribute output as part of a responsive image tag.
 *
 * This method COULD use all of the specified attributes below; but it only
 * utilizes that first $attr property, because we're hard-coding the value
 * to return rather than deriving it from the post or sizes.
 *
 * @param array        $attr       Attributes for the image markup.
 * @param WP_Post      $attachment Image attachment post.
 * @param string|array $size       Requested size. Image size or array of width and height values
 *                                 (in that order). Default 'thumbnail'.
 *
 * @return string The filtered attributes object.
 */
function filter_image_attributes( array $attr ) : array {
	return array_merge( $attr, [
		// We hard-code the "sizes" attribute for our grid's responsive markup.
		// The dimensions are calculated assuming the largest possible block width;
		// the breakpoints driving the styles are determined by the breakpoints
		// specified in the render method.
		'sizes' => '(min-width: 480px) 320px, 160px',
	] );
}

/**
 * Render the HTML for the artwork thumbnail grid.
 *
 * @param array $attributes The block attributes.
 * @return string The rendered block markup, as an HTML string.
 */
function render_artwork_grid( array $attributes ) : string {
	$align = isset( $attributes['align'] ) ? (string) $attributes['align'] : '';
	if ( ! empty( $align ) ) {
		$align = "align$align";
	}

	$recent_artwork = new WP_Query( [
		'post_type'      => Post_Types\ARTWORK_POST_TYPE,
		'posts_per_page' => 9, // Only 8 will display on certain screen sizes.
	] );

	// Define the container dimensions at which the different breakpoints kick in.
	$breakpoints = [
		'two-up'   => 0,
		'three-up' => 420,
		'four-up'  => 640,
	];

	add_filter( 'wp_get_attachment_image_attributes', __NAMESPACE__ . '\\filter_image_attributes', 10, 1 );

	$block_output = Markup\artwork_thumbnail_grid( $recent_artwork->posts, $breakpoints, $align, 'artwork-grid' );

	remove_filter( 'wp_get_attachment_image_attributes', __NAMESPACE__ . '\\filter_image_attributes' );

	return $block_output;
}
