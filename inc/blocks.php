<?php
/**
 * Block auto-loader.
 */
namespace ArtGallery\Blocks;

use ArtGallery\Post_Types;
use WP_Post;

function setup() {
	// Auto-load all PHP-defined blocks.
	autoregister_blocks();

	// Register actions & filters.
	add_filter( 'block_categories', __NAMESPACE__ . '\\add_custom_block_category', 10, 2 );
}

/**
 * Register a custom block category for this plugin.
 */
function add_custom_block_category( array $categories, WP_Post $post ) {
	if ( $post->post_type !== Post_Types\ARTWORK_POST_TYPE ) {
		return $categories;
	}

	return array_merge( $categories, [
		[
			'slug'  => 'artgallery',
			'title' => __( 'Art Gallery', 'artgallery' ),
			'icon'  => 'art',
		],
	] );
}

/**
 * Extract the block name from a directory path
 *
 * @param string $directory_path Path to a block's php file.
 * @return string The name of the block, in Pascal case.
 */
function get_block_handle_from_path( $block_file_path ) {
	return str_replace(
		[ __DIR__ . '/blocks/', '.php' ],
		[ '', '' ],
		$block_file_path
	);
}

/**
 * Get the expected PHP namespace from the block name.
 *
 * @param string $block_name Block handle name, harpoon-case.
 * @return string Expected PHP namespace, in PascalCase.
 */
function get_namespace_from_block_handle( $block_handle ) {
	return sprintf(
		'ArtGallery\\Blocks\\%s',
		str_replace( ' ', '_', ucwords( implode( ' ', explode( '-', $block_handle ) ) ) )
	);
}

/**
 * Dynamically register blocks if a registration file exists.
 */
function autoregister_blocks() {
	// Each block registered must have an entrypoint in /blocks/{blockname}.php.
	foreach ( glob( __DIR__ . '/blocks/*.php' ) as $file ) {
		require_once( $file );
		$block_handle = get_block_handle_from_path( $file );
		$setup = get_namespace_from_block_handle( $block_handle ) . '\\setup';

		if ( function_exists( $setup ) ) {
			call_user_func( $setup );
		}
	}
}
