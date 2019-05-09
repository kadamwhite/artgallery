<?php

namespace ArtGallery\Image_Sizes;

function setup() {
	add_action( 'after_setup_theme', __NAMESPACE__ . '\\register_image_sizes' );
}

/**
 * Register a variety of 1:1-aspect ratio image sizes for use in blocks & widgets.
 *
 * @return void
 */
function register_image_sizes() {
	foreach ( [
		'xs' => 160,
		'sm' => 320,
		'md' => 480,
		'lg' => 720,
	] as $name => $size ) {
		add_image_size( "ag_square_$name", $size, $size, true );
	}
}

/**
 * Helper method to assemble a complete object of all registered image sizes.
 *
 * @return array
 */
function get_registered_image_sizes() : array {
	global $_wp_additional_image_sizes;

	$image_sizes = [];

	foreach ( get_intermediate_image_sizes() as $size ) {
		if ( in_array( $size, [ 'thumbnail', 'medium', 'medium_large', 'large' ] ) ) {
			$image_sizes[] = [
				'name'   => $size,
				'width'  => (int) get_option( "{$size}_size_w" ),
				'height' => (int) get_option( "{$size}_size_h" ),
				'crop'   => (bool) get_option( "{$size}_crop" ),
			];
		} elseif ( isset( $_wp_additional_image_sizes[ $size ] ) ) {
			$image_sizes[] = [
				'name'   => $size,
				'width'  => $_wp_additional_image_sizes[ $size ]['width'],
				'height' => $_wp_additional_image_sizes[ $size ]['height'],
				'crop'   => $_wp_additional_image_sizes[ $size ]['crop'],
			];
		}
	}

	return $image_sizes;
}

/**
 * Given a [ width, height ] array, find the next-largest registered image size.
 *
 * @param array $size A [ width, height ] array of an image size.
 * @return string|bool The name of the matched image size, or false if no match.
 */
function get_next_largest_image_size( array $size ) {
	$width  = $size[0];
	$height = $size[1];
	if ( empty( $width ) || empty( $height ) ) {
		return false;
	}

	$is_square = $width === $height;
	$sizes     = get_registered_image_sizes();

	usort( $sizes, function( $a, $b ) {
		$area_a = $a['width'] * $a['height'];
		$area_b = $b['width'] * $b['height'];
		return $area_a - $area_b;
	} );

	// First, check to see if the provided size is bigger than any size in our
	// array. If so, default to the largest _specified_ size.
	$largest_size = end( $sizes );
	if ( $largest_size['width'] > $width ) {
		return $largest_size['name'];
	}

	foreach ( $sizes as $size ) {
		if ( $size['width'] < $width && $size['height'] < $height ) {
			// Simple case: too small. Move on.
			continue;
		}
		if ( $size['crop'] && ! $is_square && ( $size['width'] === $size['height'] ) ) {
			// Do not match a not-square image to a square crop.
			continue;
		}
		if ( $width * $height > $size['width'] * $size['height'] ) {
			// Not sure how we'd hit this, but if the aspect's quite different we'd
			// likely want to move on. So, we do.
			continue;
		}
		// Provisional match.
		return $size['name'];
	}
	return false;
}
