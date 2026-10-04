<?php
/**
 * Enqueue the plugin-level editor script.
 *
 * Block scripts and styles load from each block.json. This script (editor
 * filters and plugins) is not a block, but is built by wp-scripts from the
 * dummy src/editor/block.json so that no custom webpack config is needed. That
 * block is never registered; the script is enqueued here from its .asset.php.
 */
namespace ArtGallery\Scripts;

function setup() {
	add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_block_editor_assets' );
}

/**
 * Enqueue editor assets.
 */
function enqueue_block_editor_assets() {
	$asset_file = ARTGALLERY_PATH . 'build/editor/index.asset.php';

	// Nothing to enqueue before the first build.
	if ( ! file_exists( $asset_file ) ) {
		return;
	}

	$asset = require $asset_file;

	wp_enqueue_script(
		'artgallery-editor',
		ARTGALLERY_URL . 'build/editor/index.js',
		$asset['dependencies'],
		$asset['version'],
		true
	);

	if ( file_exists( ARTGALLERY_PATH . 'build/editor/index.css' ) ) {
		wp_enqueue_style(
			'artgallery-editor',
			ARTGALLERY_URL . 'build/editor/index.css',
			[],
			$asset['version']
		);
	}
}
