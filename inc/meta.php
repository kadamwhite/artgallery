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
