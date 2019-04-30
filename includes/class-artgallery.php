<?php
/**
 * ArtGallery
 *
 * @package   artgallery
 * @author    K.Adam White <adam@kadamwhite.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 K.Adam White
 */

/**
 * Plugin class.
 *
 *
 * @package artgallery
 * @author  K.Adam White <adam@kadamwhite.com>
 */
class ArtGallery {

  /**
   * Plugin version, used for cache-busting of style and script file references.
   *
   * @since   0.0.1
   *
   * @var     string
   */
  protected $version = '0.0.2';

  /**
   * Unique identifier for your plugin.
   *
   * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
   * match the Text Domain file header in the main plugin file.
   *
   * @since    0.0.1
   *
   * @var      string
   */
  protected $plugin_slug = 'artgallery';

  /**
   * Instance of this class.
   *
   * @since    0.0.1
   *
   * @var      object
   */
  protected static $instance = null;

  /**
   * Slug of the plugin screen.
   *
   * @since    0.0.1
   *
   * @var      string
   */
  protected $plugin_screen_hook_suffix = null;

  /**
   * Initialize the plugin by registering custom post types and taxonomies
   *
   * @since     0.0.1
   */
  private function __construct() {

    // Anything that has to happen inside Admin
    add_action( 'admin_init', array( $this, 'artgallery_admin_init' ) );
  }

  /**
   * Return an instance of this class.
   *
   * @since     0.0.1
   *
   * @return    object    A single instance of this class.
   */
  public static function get_instance() {

    // If the single instance hasn't been set, set it now.
    if ( null == self::$instance ) {
      self::$instance = new self;
    }

    return self::$instance;
  }

  /**
   * Fired when the plugin is activated.
   *
   * @since    0.0.1
   *
   * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
   */
  public static function activate( $network_wide ) {
    // TODO: Define activation functionality here
  }

  /**
   * Fired when the plugin is deactivated.
   *
   * @since    0.0.1
   *
   * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
   */
  public static function deactivate( $network_wide ) {
    // TODO: Define deactivation functionality here
  }





  /*****************************************
   WELCOME TO THE WP-ADMIN SIDE OF THE WORLD
   *****************************************/

  /**
   * Enqueue styles for use in the edit artwork screen
   *
   * @since 0.0.2
   */
  public function artgallery_admin_init() {
    // Add stylesheet to make our custom ACF stuff display correctly
    wp_register_style( 'artgallery-admin', ARTGALLERY_URL . 'assets/css/artgallery-admin.css' );
    wp_register_script(
      'artgallery-edit-artwork',
      ARTGALLERY_URL . 'assets/js/src/edit-ag_artwork_item.js',
      array( 'jquery' ),
      '0.1.0',
      true
    );

    add_action( 'admin_enqueue_scripts', array( $this, 'artgallery_admin_enqueue_scripts' ) );
  }

  public function artgallery_admin_enqueue_scripts() {
    wp_enqueue_style( 'artgallery-admin' );

    // Conditionally load extra scripts when editing an artwork item
    $screen = get_current_screen();

    if ( isset( $screen->post_type ) && 'ag_artwork_item' === $screen->post_type ) {
      wp_enqueue_script( 'artgallery-edit-artwork' );
    }
  }

}
