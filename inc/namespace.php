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

	// Check the installed version of the plugin and run upgrade procedures if
	// the current version does not match the installed version.
	$option_key = 'artgallery_plugin_version';
	$installed_version = get_option( $option_key );
	if ( $installed_version !== ARTGALLERY_VERSION ) {
		update_option( $option_key, ARTGALLERY_VERSION );
		do_action( 'artgallery_upgrade' );
	}
}
