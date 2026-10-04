<?php
/**
 * Hooks for the Artwork Dimensions & Materials block.
 */
namespace ArtGallery\Blocks\Metadata;

const BLOCK_NAME = 'artgallery/metadata';

function setup() {
	add_filter( 'render_block', __NAMESPACE__ . '\\disable_wpautop', 10, 2 );
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
