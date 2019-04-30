<?php
/**
 * Server-rendered Artwork Grid block.
 */
namespace ArtGallery\Blocks\Artwork_Grid;

use ArtGallery\Meta;
use ArtGallery\Post_Types;
use ArtGallery\Taxonomies;
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
		'render_callback' => __NAMESPACE__ . '\\render_artwork_grid',
	] );
}

/**
 * Render the HTML for the artwork thumbnail grid.
 *
 * @return string
 */
function render_artwork_grid() : string {
	$recent_artwork = new WP_Query( [
		'post_type'      => Post_Types\ARTWORK_POST_TYPE,
		'posts_per_page' => 8,
	] );

	$output = '<div class="artwork-grid">';
	foreach ( $recent_artwork->posts as $artwork ) {
		$dimensions = Meta\get_artwork_dimensions( $artwork->ID );
		$media = Taxonomies\get_media_list( $artwork->ID );
		$link = get_permalink( $artwork->ID );

		$output .= "\n<a class=\"artwork-grid__link\" href=\"$link\">\n";

		$output .= get_the_post_thumbnail( $artwork, 'ag_square_xs' ) . "\n";
		$output .= '<p class="artwork-grid__title">' . $artwork->post_title . "</p>\n";

		if ( ! empty( $dimensions ) || ! empty( $media ) ) {
			$output .= '<p class="artwork-grid__meta">';
			if ( $dimensions ) {
				$output .= $dimensions;
			}
			if ( $dimensions && $media ) {
				$output .= '; ';
			}
			if ( $media ) {
				$output .= $media;
			}
			$output .= "</p>\n";
		}

		$output .= '</a>';
	}
	$output .= "\n</div>";

	return $output;
}
