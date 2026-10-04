<?php
/**
 * Block auto-loader.
 */
namespace ArtGallery\Blocks;

use ArtGallery\Post_Types;
use WP_Block_Type_Registry;

/**
 * Blocks which are only available when editing artwork items.
 */
const ARTWORK_ONLY_BLOCKS = [
	'artgallery/availability',
	'artgallery/metadata',
];

function setup() {
	// Load block-specific hooks.
	autoregister_blocks();

	// Register actions & filters.
	add_action( 'init', __NAMESPACE__ . '\\register_blocks' );
	add_filter( 'block_categories_all', __NAMESPACE__ . '\\add_artgallery_block_category', 10, 1 );
	add_filter( 'allowed_block_types_all', __NAMESPACE__ . '\\limit_artwork_only_blocks', 10, 2 );
}

/**
 * Register every block built from a src/blocks/{name}/block.json file.
 */
function register_blocks() {
	foreach ( glob( ARTGALLERY_PATH . 'build/blocks/*/block.json' ) as $file ) {
		register_block_type_from_metadata( dirname( $file ) );
	}
}

/**
 * Remove the artwork-specific blocks outside the artwork post type.
 *
 * @param bool|string[]            $allowed_block_types Allowed block types, or true for all.
 * @param \WP_Block_Editor_Context $context             The current block editor context.
 * @return bool|string[] The filtered allowed block types.
 */
function limit_artwork_only_blocks( $allowed_block_types, $context ) {
	// Leave the site editor and other post-less contexts alone.
	if ( empty( $context->post ) || Post_Types\ARTWORK_POST_TYPE === $context->post->post_type ) {
		return $allowed_block_types;
	}

	if ( true === $allowed_block_types ) {
		$allowed_block_types = array_keys( WP_Block_Type_Registry::get_instance()->get_all_registered() );
	}

	if ( ! is_array( $allowed_block_types ) ) {
		return $allowed_block_types;
	}

	return array_values( array_diff( $allowed_block_types, ARTWORK_ONLY_BLOCKS ) );
}

/**
 * Register a custom block category for this plugin.
 *
 * @param array $categories The list of available block categories.
 * @return array The filtered categories list.
 */
function add_artgallery_block_category( array $categories ) {
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
 * Load block-specific hooks if a setup file exists.
 */
function autoregister_blocks() {
	// Each block with PHP hooks must have an entrypoint in /blocks/{blockname}.php.
	foreach ( glob( __DIR__ . '/blocks/*.php' ) as $file ) {
		require_once $file;
		$block_handle = get_block_handle_from_path( $file );
		$setup = get_namespace_from_block_handle( $block_handle ) . '\\setup';

		if ( function_exists( $setup ) ) {
			call_user_func( $setup );
		}
	}
}
