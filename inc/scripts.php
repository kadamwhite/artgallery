<?php
/**
 * Register scripts in development and production.
 */
namespace ArtGallery\Scripts;

use ArtGallery\Asset_Loader;

function setup() {
	add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_block_editor_assets' );
}

/**
 * Enqueue editor assets based on the generated `asset-manifest.json` file.
 */
function enqueue_block_editor_assets() {
	$plugin_path  = ARTGALLERY_PATH;
	$plugin_url   = ARTGALLERY_URL;
	$dev_manifest = $plugin_path . 'build/asset-manifest.json';

	$opts = [
		'handle' => 'featured-item-blocks-editor',
		'scripts' => [
			'wp-blocks',
			'wp-components',
			'wp-compose',
			'wp-data',
			'wp-edit-post',
			'wp-element',
			'wp-i18n',
			'wp-plugins',
		],
	];

	$loaded_dev_assets = Asset_Loader\enqueue_assets( $dev_manifest, $opts );

	if ( ! $loaded_dev_assets ) {
		// Production mode. Manually enqueue script bundles.
		if ( file_exists( $plugin_path . 'build/editor.js' ) ) {
			wp_enqueue_script(
				$opts['handle'],
				$plugin_url . 'build/editor.js',
				$opts['scripts'],
				filemtime( $plugin_path . 'build/editor.js' ),
				true
			);
		}
		// TODO: Error if file is not found.

		if ( file_exists( $plugin_path . 'build/editor.css' ) ) {
			wp_enqueue_style(
				$opts['handle'],
				$plugin_url . 'build/editor.css',
				null,
				filemtime( $plugin_path . 'build/editor.css' )
			);
		}
	}
}
