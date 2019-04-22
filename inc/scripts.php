<?php
/**
 * Register scripts in development and production.
 */
namespace ArtGallery\Scripts;

use Asset_Loader;

function setup() {
	add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_block_editor_assets' );
}

/**
 * Enqueue editor assets based on the generated `asset-manifest.json` file.
 */
function enqueue_block_editor_assets() {
	$manifest_path = ARTGALLERY_PATH . 'build/asset-manifest.json';

	Asset_Loader\autoenqueue( $manifest_path, 'editor.js', [
		'handle'  => 'artgallery-editor',
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
	] );
}
