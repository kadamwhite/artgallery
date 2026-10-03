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
 * Path to the asset manifest written by the build.
 *
 * @return string
 */
function manifest_path(): string {
	return ARTGALLERY_PATH . 'build/production-asset-manifest.json';
}

/**
 * Read the dependencies and version from a wp-scripts generated .asset.php file.
 *
 * @param string $entry Entry name, e.g. "editor".
 * @return array{dependencies: string[], version: string}
 */
function asset_meta( string $entry ): array {
	$file = ARTGALLERY_PATH . "build/{$entry}.asset.php";
	$meta = file_exists( $file ) ? require $file : [];
	return [
		'dependencies' => $meta['dependencies'] ?? [],
		'version'      => $meta['version'] ?? ARTGALLERY_VERSION,
	];
}

/**
 * Enqueue an asset from the manifest, using whichever Asset Loader API is available.
 *
 * Asset Loader 1.x renamed enqueue_asset() to enqueue_manifest_asset(); the old
 * name still exists but triggers a deprecation notice.
 *
 * @param string $asset   Asset key within the manifest, e.g. "editor.js".
 * @param array  $options Asset Loader options (handle, dependencies).
 */
function enqueue_manifest_asset( string $asset, array $options = [] ): void {
	if ( function_exists( 'Asset_Loader\\enqueue_manifest_asset' ) ) {
		Asset_Loader\enqueue_manifest_asset( manifest_path(), $asset, $options );
		return;
	}
	Asset_Loader\enqueue_asset( manifest_path(), $asset, $options );
}

/**
 * Enqueue editor assets.
 */
function enqueue_block_editor_assets() {
	$meta = asset_meta( 'editor' );

	enqueue_manifest_asset( 'editor.js', [
		'handle'       => 'artgallery-editor',
		'dependencies' => $meta['dependencies'],
	] );

	enqueue_manifest_asset( 'editor.css', [
		'handle' => 'artgallery-editor',
	] );

	$screen = get_current_screen();
	if ( $screen ) {
		wp_localize_script( 'artgallery-editor', 'ARTGALLERY_CURRENT_SCREEN', (array) $screen );
	}
}

/**
 * Enqueue frontend assets. (Runs on both frontend and backend.)
 */
function enqueue_block_assets() {
	$meta = asset_meta( 'frontend' );

	enqueue_manifest_asset( 'frontend.js', [
		'handle'       => 'artgallery-frontend',
		'dependencies' => $meta['dependencies'],
	] );

	enqueue_manifest_asset( 'frontend.css', [
		'handle' => 'artgallery-frontend',
	] );
}
