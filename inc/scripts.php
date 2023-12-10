<?php
/**
 * Register scripts in development and production.
 */
namespace ArtGallery\Scripts;

use Asset_Loader;

function setup() {
	add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_block_editor_assets' );
	add_action( 'enqueue_block_assets', __NAMESPACE__ . '\\enqueue_block_assets' );
}

/**
 * Enqueue editor assets based on the generated `asset-manifest.json` file.
 */
function enqueue_block_editor_assets() {
	$manifest_path = ARTGALLERY_PATH . 'build/asset-manifest.json';

	Asset_Loader\enqueue_asset( $manifest_path, 'editor.js', [
		'handle'  => 'artgallery-editor',
		'dependencies' => [
			'wp-blocks',
			'wp-components',
			'wp-compose',
			'wp-data',
			'wp-dom-ready',
			'wp-edit-post',
			'wp-element',
			'wp-hooks',
			'wp-i18n',
			'wp-plugins',
		],
	] );

	$screen = get_current_screen();
	if ( $screen ) {
		wp_localize_script( 'artgallery-editor', 'ARTGALLERY_CURRENT_SCREEN', (array) $screen );
	}
}

/**
 * Enqueue frontend assets based on the generated `asset-manifest.json` file.
 * (Runs on both frontend and backend.)
 */
function enqueue_block_assets() {
	$manifest_path = ARTGALLERY_PATH . 'build/asset-manifest.json';

	Asset_Loader\enqueue_asset( $manifest_path, 'frontend.js', [
		'handle' => 'artgallery-frontend',
	] );
}
