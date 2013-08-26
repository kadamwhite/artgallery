<?php
/**
 * ArtGallery
 *
 * Custom post types and taxonomies for artists.
 *
 * @package   ArtGallery
 * @author    K.Adam White <adam@kadamwhite.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 K.Adam White
 *
 * @wordpress-plugin
 * Plugin Name: ArtGallery
 * Plugin URI:  TODO
 * Description: Custom post types and taxonomies for the working artist
 * Version:     0.0.2
 * Author:      K.Adam White <adam@kadamwhite.com>
 * Author URI:  http://kadamwhite.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Useful global constants
define( 'ARTGALLERY_URL',  plugin_dir_url( __FILE__ ) );
define( 'ARTGALLERY_PATH', dirname( __FILE__ ) . '/'  );

// Class Instantiation
// ===================

require_once( plugin_dir_path( __FILE__ ) . 'includes/class-artgallery.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/lib/acf/acf.php' );

if ( defined( 'ACF_LITE' ) && ACF_LITE == true ) {
  // If we're in "lite mode," load the exported config file to register fields
  require_once( plugin_dir_path( __FILE__ ) . 'includes/config/acf-config.php' );
}

// Register hooks that are fired when the plugin is activated and deactivated, respectively.
register_activation_hook( __FILE__, array( 'ArtGallery', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ArtGallery', 'deactivate' ) );

ArtGallery::get_instance();

// Template Tags
// =============

function ag_artwork_dimensions_list( $post_id, $plain_text = false ) {
  return ArtGallery::get_instance()->get_taxonomy_list( $post_id, 'ag_artwork_dimensions', $plain_text );
}

function ag_artwork_media_list( $post_id, $plain_text = false ) {
  return ArtGallery::get_instance()->get_taxonomy_list( $post_id, 'ag_artwork_media', $plain_text );
}

function ag_artwork_title_attribute( $post_id ) {
  return sprintf(
    __( '%s, %s (%s)', 'artgallery' ),
    the_title_attribute( 'echo=0' ),
    ag_artwork_dimensions_list( get_the_ID(), true ),
    ag_artwork_media_list( get_the_ID(), true )
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
