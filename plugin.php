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
 * Version:     0.1.0
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
define( 'ARTGALLERY_VERSION', '0.1.0' );
define( 'ARTGALLERY_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'ARTGALLERY_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
// phpcs:enable PSR1.Files.SideEffects

require_once( ARTGALLERY_PATH . 'inc/blocks.php' );
require_once( ARTGALLERY_PATH . 'inc/meta.php' );
require_once( ARTGALLERY_PATH . 'inc/namespace.php' );
require_once( ARTGALLERY_PATH . 'inc/post-types.php' );
require_once( ARTGALLERY_PATH . 'inc/scripts.php' );
require_once( ARTGALLERY_PATH . 'inc/taxonomies.php' );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once( ARTGALLERY_PATH . 'inc/migrations.php' );

	require_once( ARTGALLERY_PATH . 'inc/wp_cli/class-migrate-acf-meta.php' );
	WP_CLI::add_command( 'artgallery-migrate-acf-meta', 'ArtGallery\\WP_CLI\\Migrate_ACF_Meta' );

	require_once( ARTGALLERY_PATH . 'inc/wp_cli/class-populate-artwork-post-content.php' );
	WP_CLI::add_command( 'artgallery-populate-artwork-post-content', 'ArtGallery\\WP_CLI\\Populate_Artwork_Post_Content' );
}

// Conditionally include bundled asset-loader, then initialize plugin.
add_action( 'plugins_loaded', function() {
	if ( ! function_exists( 'Asset_Loader\\autoenqueue' ) ) {
		require_once( ARTGALLERY_PATH . 'vendor/asset-loader/asset-loader.php' );
	}

	ArtGallery\setup();
} );

// phpcs:disable
// Everything below this line is legacy code.

// Template Tags
// =============

/**
 * Retrieve a list of taxonomy terms for the provided post ID
 *
 * @param int $post_id The ID of the post for which to fetch those taxonomy terms.
 * @param string $taxonomy_name The name of the taxonomy.
 * @return string Comma-separated, plain-text list of term names.
 */
function ag_plain_term_list( $post_id, $taxonomy_name, $before = '', $sep = ', ', $after = '' ) {
  $terms = wp_get_object_terms( $post_id, $taxonomy_name, array( 'fields' => 'names' ) );
  return $before . join( $sep, $terms ) . $after;
}
/**
 * Print out in plain text the title attribute used in artwork permalinks
 *
 * @param int $post_id The ID of the post for which to fetch the link title text.
 * @return string Plain-text string containing the title of an artork item, its size, and the media used.
 */
function ag_artwork_title_attribute( $post_id ) {
  return sprintf(
    __( '%s, %s (%s)', 'artgallery' ),
    the_title_attribute( array(
      'echo' => 0,
      'post' => $post_id,
    ) ),
    ag_plain_term_list( $post_id, 'ag_artwork_dimensions' ),
    ag_plain_term_list( $post_id, 'ag_artwork_media' )
  );
}

// Widget Stuff
// ============

require_once( plugin_dir_path( __FILE__ ) . 'includes/widget-sidebar-gallery.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/widget-media.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/widget-categories.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/widget-dimensions.php' );

function ag_register_widgets() {
  register_widget( 'ArtGallery_Sidebar_Gallery_Widget' );
  register_widget( 'ArtGallery_Widget_Media' );
  register_widget( 'ArtGallery_Widget_Categories' );
  register_widget( 'ArtGallery_Widget_Dimensions' );
}
add_action( 'widgets_init', 'ag_register_widgets' );
