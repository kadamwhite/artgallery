<?php
/**
 * ArtGallery
 *
 * Custom post types and taxonomies for artists.
 *
 * @package   ArtGallery
 * @author    K Adam White
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013-2018 K Adam White and Contributors
 *
 * @wordpress-plugin
 * Plugin Name: ArtGallery
 * Plugin URI:  https://github.com/kadamwhite/artgallery
 * Description: Custom post types, taxonomies and editor blocks for the working artist.
 * Version:     0.3.3
 * Author:      K Adam White
 * Author URI:  http://kadamwhite.com
 * License:     GPL-2.0+ or Artistic License 2.0
 * License URI: https//github.com/kadamwhite/artgallery/tree/master/LICENSE.md
 */

// phpcs:disable PSR1.Files.SideEffects
if ( ! defined( 'WPINC' ) ) {
	// If this file is called directly, abort.
	die;
}

// Useful global constants.
define( 'ARTGALLERY_VERSION', '0.3.3' );
define( 'ARTGALLERY_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'ARTGALLERY_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
// phpcs:enable PSR1.Files.SideEffects

require_once ARTGALLERY_PATH . 'inc/blocks.php';
require_once ARTGALLERY_PATH . 'inc/image-sizes.php';
require_once ARTGALLERY_PATH . 'inc/markup.php';
require_once ARTGALLERY_PATH . 'inc/meta.php';
require_once ARTGALLERY_PATH . 'inc/namespace.php';
require_once ARTGALLERY_PATH . 'inc/post-types.php';
require_once ARTGALLERY_PATH . 'inc/taxonomies.php';

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once ARTGALLERY_PATH . 'inc/migrations.php';

	require_once ARTGALLERY_PATH . 'inc/wp_cli/class-migrate-acf-meta.php';
	WP_CLI::add_command( 'artgallery-migrate-acf-meta', 'ArtGallery\\WP_CLI\\Migrate_ACF_Meta' );

	require_once ARTGALLERY_PATH . 'inc/wp_cli/class-populate-artwork-post-content.php';
	WP_CLI::add_command( 'artgallery-populate-artwork-post-content', 'ArtGallery\\WP_CLI\\Populate_Artwork_Post_Content' );

	require_once ARTGALLERY_PATH . 'inc/wp_cli/class-migrate-image-sizes.php';
	WP_CLI::add_command( 'artgallery-migrate-image-sizes', 'ArtGallery\\WP_CLI\\Migrate_Image_Sizes' );
}

// Conditionally enqueue editor UI scripts & styles.
add_action( 'plugins_loaded', function() {
	if ( function_exists( 'Asset_Loader\\autoenqueue' ) ) {
		require_once ARTGALLERY_PATH . 'inc/scripts.php';
		ArtGallery\setup();
	} else {
		add_action( 'admin_notices', function() {
			// Deliberately omit .is-dismissible from these classes.
			echo '<div class="notice notice-error">';
			echo '<p>';
			echo 'The ArtGallery plugin will not work properly unless the ';
			echo '<a href="https://github.com/humanmade/asset-loader">Asset Loader plugin</a>';
			echo ' is installed &amp; active!';
			echo '</p>';
			echo '</div>';
		} );
	}
} );
