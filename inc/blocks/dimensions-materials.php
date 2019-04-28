<?php
/**
 * Server-rendered Artwork Dimensions & Materials block.
 */
namespace ArtGallery\Blocks\Dimensions_Materials;

use ArtGallery\Meta;
use ArtGallery\Taxonomies;
use ArtGallery\Utilities;

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

	// Helper method to get an attribute from the attributes array or meta.
	$get_attribute = function( $property, $fallback_meta_key ) use ( $attributes, $post ) {
		if ( isset( $attributes[ $property ] ) && ! empty( $attributes[ $property ] ) ) {
			return $attributes[ $property ];
		}
		return get_post_meta( $post->ID, $fallback_meta_key, true );
	};

	$date = $get_attribute( 'date', Meta\ARTWORK_DATE );
	if ( ! empty( $date ) && preg_match( '/[0-9]{4}-?[0-9]{2}-?[0-9]{2}/', $date ) ) {
		$date = date_format( date_create( $date ), 'F Y' );
	}

	$media = wp_get_post_terms( $post->ID, Taxonomies\MEDIA_TAXONOMY );
	$term_links = array_map( function( $medium ) {
		$href = get_term_link( $medium );
		return "<a href=\"$href\">$medium->name</a>";
	}, $media );

	$dimensions = array_map(
		function( $inches ) {
			return $inches . '"';
		},
		array_filter(
			[
				$get_attribute( 'width', Meta\ARTWORK_WIDTH ),
				$get_attribute( 'height', Meta\ARTWORK_HEIGHT ),
				$get_attribute( 'depth', Meta\ARTWORK_DEPTH ),
			],
			function( $inches ) {
				return ! empty( $inches );
			}
		)
	);

	$block_output = '';

	if ( ! empty( $date ) ) {
		$block_output .= $date . '. ';
	}
	if ( ! empty( $dimensions ) ) {
		$block_output .= implode( ' x ', $dimensions );
		if ( ! empty( $term_links ) ) {
			$block_output .= '; ';
		}
	}
	if ( ! empty( $term_links ) ) {
		$block_output .= implode( ', ', $term_links ) . '.';
	}

	return ! empty( $block_output ) ?
		'<p class="artwork-meta">' . $block_output . '</p>' :
		null;
}

/**
 * Register the Featured Items List dynamic block.
 */
function register_block() {
	register_block_type( Utilities\namespaced_block( 'dimensions-materials' ), [
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
	if ( Utilities\namespaced_block( 'dimensions-materials' ) === $block['blockName'] ) {
		remove_filter( 'the_content', 'wpautop' );
		remove_filter( 'the_content', 'wptexturize' );
	}

	return $block_content;
}
