<?php

namespace ArtGallery;

use ArtGallery\Blocks;
use ArtGallery\Meta;
use ArtGallery\Post_Types;
use ArtGallery\Scripts;
use ArtGallery\Taxonomies;

/**
 * Initialize plugin behavior. Runs on `plugins_loaded` action.
 *
 * @return void
 */
function setup() {
	// Set up plugin namespaces and register all action callbacks.
	Taxonomies\setup();
	Post_Types\setup();
	Meta\setup();
	Blocks\setup();
	Scripts\setup();

	add_action( 'after_setup_theme', __NAMESPACE__ . '\\register_image_sizes' );

	add_action( 'wp_loaded', __NAMESPACE__ . '\\maybe_run_upgrade_routine' );
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
		'md' => 640,
		'lg' => 960,
		'xl' => 1280,
	] as $name => $size ) {
		add_image_size( "ag_square_$name", $size, $size, true );
	}
}

/**
 * Check to see if the installed version of this plugin matches the current code-
 * specified version number, and fire the hook to run plugin upgrade procedures if
 * the current version does not match the version in the database.
 *
 * @return void
 */
function maybe_run_upgrade_routine() {
	$option_key = 'artgallery_plugin_version';
	$installed_version = get_option( $option_key );

	if ( $installed_version !== ARTGALLERY_VERSION ) {
		update_option( $option_key, ARTGALLERY_VERSION );
		do_action( 'artgallery_upgrade' );
	}
}
