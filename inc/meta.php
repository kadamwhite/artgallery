<?php

namespace ArtGallery\Meta;

use ArtGallery\Post_Types;

const ARTWORK_WIDTH = 'width';
const ARTWORK_HEIGHT = 'height';
const ARTWORK_DEPTH = 'depth';
const ARTWORK_DATE = 'creation_date';

function setup() {
	add_action( 'init', __NAMESPACE__ . '\\register_meta' );
}

function register_meta() {
	// Register width, height, and depth meta values.
	foreach ( [
		[ ARTWORK_WIDTH, __( 'The width of the artwork, in inches', 'artgallery' ) ],
		[ ARTWORK_HEIGHT, __( 'The height of the artwork, in inches', 'artgallery' ) ],
		[ ARTWORK_DEPTH, __( 'The depth of the artwork, in inches', 'artgallery' ) ],
	] as $meta ) {
		$key = $meta[0];
		$description = $meta[1];
		register_post_meta( Post_Types\ARTWORK_POST_TYPE, $key, [
			'description'  => $description,
			'type'         => 'number',
			'single'       => true,
			'show_in_rest' => true,
		] );
	}

	register_post_meta( Post_Types\ARTWORK_POST_TYPE, ARTWORK_DATE, [
		'description'  => __( 'The date the artwork was finished, YYYYMMDD', 'artgallery' ),
		'type'         => 'string',
		'single'       => true,
		'show_in_rest' => true,
	] );
}

/**
 * Given an artwork ID and optional attributes array, return the date value from
 * either those attributes or the fallback meta value saved to the post itself.
 *
 * @param int         $artwork_id The ID of an artwork post object.
 * @param string|null [$override] (optional) A string value to use instead of a meta lookup.
 *
 * @return string The artwork date string.
 */
function get_artwork_date( int $artwork_id, ?string $override = null ) : string {
	if ( ! empty( $override ) ) {
		$date = $override;
	} else {
		$date = get_post_meta( $artwork_id, ARTWORK_DATE, true );
	}

	// Convert legacy YYYYMMDD dates into "Month, YYYY" strings.
	if ( ! empty( $date ) && preg_match( '/[0-9]{4}-?[0-9]{2}-?[0-9]{2}/', $date ) ) {
		return date_format( date_create( $date ), 'F Y' );
	}

	// Return all other dates as-is.
	return $date;
}

/**
 * Given an artwork ID and optional array with numeric 'width', 'height' and
 * 'depth' values, return a formatted string specifying artwork dimensions.
 *
 * @param int     $artwork_id  The ID of an artwork post object.
 * @param array   [$overrides] (optional) An array with width, height, and possibly depth properties.
 *
 * @return string The rendered artwork dimensions string.
 */
function get_artwork_dimensions( int $artwork_id, array $overrides = [] ) : string {
	$width = ! empty( $overrides['width'] ) ? $overrides['width'] : null;
	$height = ! empty( $overrides['height'] ) ? $overrides['height'] : null;
	$depth = ! empty( $overrides['depth'] ) ? $overrides['depth'] : null;

	$dimensions = array_map(
		function( $inches ) {
			return $inches . '"';
		},
		array_filter(
			[
				$width ?? get_post_meta( $artwork_id, ARTWORK_WIDTH, true ),
				$height ?? get_post_meta( $artwork_id, ARTWORK_HEIGHT, true ),
				$depth ?? get_post_meta( $artwork_id, ARTWORK_DEPTH, true ),
			],
			function( $inches ) {
				return ! empty( $inches );
			}
		)
	);

	return implode( ' x ', $dimensions );
}
