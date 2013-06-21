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
 * Version:     0.0.1
 * Author:      K.Adam White <adam@kadamwhite.com>
 * Author URI:  http://kadamwhite.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'includes/class-artgallery.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'ArtGallery', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ArtGallery', 'deactivate' ) );

ArtGallery::get_instance();
