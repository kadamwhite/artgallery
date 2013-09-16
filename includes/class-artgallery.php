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

    // Set up taxonomies and post types
    add_action( 'init', array( $this, 'artgallery_register_taxonomies' ) );
    add_action( 'init', array( $this, 'artgallery_register_post_types' ) );

    // Make sure taxonomy terms have been created for Available, NFS and Sold
    add_action( 'init', array( $this, 'artgallery_set_ag_artwork_availability_options' ) );

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

  /**
   * Register the custom post type for artworks
   *
   * @since    0.0.1
   */
  public function artgallery_register_post_types() {

    register_post_type( 'ag_artwork_item', array(
      'label' => 'Artworks',
      'description' => '',
      'public' => true,
      'publicly_queryable' => true,
      'query_var' => 'artwork',
      'show_in_nav_menu' => true,
      'exclude_from_search' => false,
      'show_ui' => true,
      'show_in_menu' => true,
      'menu_position' => 5,
      'capability_type' => 'post',
      'hierarchical' => false,
      'rewrite' => array(
        'slug' => 'art'
      ),
      'has_archive' => true,
      'supports' => array(
        'author',
        'custom-fields',
        'comments',
        'editor',
        'title',
        'thumbnail'
      ),
      'taxonomies' => array (
        'ag_artwork_availability',
        'ag_artwork_categories',
        'ag_artwork_dimensions',
        'ag_artwork_media'
      ),
      'labels' => array (
        'name' => 'Artwork',
        'singular_name' => 'Artwork',
        'all_items' => 'All Artworks',
        'add_new' => 'Add Artwork',
        'add_new_item' => 'Add New Artwork',
        'edit' => 'Edit',
        'edit_item' => 'Edit Artwork',
        'new_item' => 'New Artwork',
        'view' => 'View Artwork',
        'view_item' => 'View Artwork',
        'search_items' => 'Search Artworks',
        'not_found' => 'No Artworks Found',
        'not_found_in_trash' => 'No Artworks Found in Trash',
        'parent' => 'Parent Artwork'
      )
    ));

  }

  /**
   * Register the custom taxonomies that interface with artworks
   *
   * @since    0.0.1
   */
  public function artgallery_register_taxonomies() {

    register_taxonomy( 'ag_artwork_availability', null, array(
      'hierarchical' => false,
      'show_admin_column' => true,
      // This is pure metadata, user shouldn't edit options directly
      'show_in_nav_menus' => false,
      'show_tagcloud' => false,
      'show_ui' => false,
      // Queryable for searching
      'query_var' => true,
      'rewrite' => array(
        'slug' => 'art/availability'
      ),
      // Labels
      'label' => 'Availability'
    ));

    register_taxonomy( 'ag_artwork_media', null, array(
      'hierarchical' => true,
      'label' => 'Media',
      'show_ui' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'rewrite' => array(
        'slug' => 'art/media'
      ),
      'labels' => array (
        'add_new_item' => 'Add New Medium',
        'singular_name' => 'Medium'
      )
    ));

    register_taxonomy( 'ag_artwork_dimensions', null, array(
      'hierarchical' => true,
      'label' => 'Dimensions',
      'show_ui' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'rewrite' => array(
        'slug' => 'art/dimensions'
      ),
      'labels' => array (
        'add_new_item' => 'Add New Dimensions'
      )
    ));

    register_taxonomy( 'ag_artwork_categories', null, array(
      'hierarchical' => true,
      'label' => 'Categories',
      'singular_label' => 'Category',
      'show_ui' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'rewrite' => array(
        'slug' => 'art/category'
      ),
      'labels' => array (
        'add_new_item' => 'Add New Category',
        'singular_name' => 'Category'
      )
    ));

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
    // Persist artwork image field as the featured image
    add_action( 'acf/save_post', array( $this, 'acf_save_featured' ), 20 );

    // Default posts to "available" if saved without an availability state
    add_action( 'save_post', array( $this, 'artgallery_set_default_availability_state' ), 100, 2 );

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

  /**
   * Register a hook to synchronize the Featured Image with an Advanced Custom Fields image field
   *
   * Function adapted from a snippet in an Advanced Custom Fields support thread:
   * http://support.advancedcustomfields.com/discussion/1856/set-featured-image-thumbnail-with-an-image-field/p1#Comment_18599
   *
   * @since    0.0.2
   */
  public function acf_save_featured() {
    global $post;

    $the_field = 'artwork_image';
    $has_the_field = get_field( $the_field );

    if ( $has_the_field ) {
      $artwork_image = get_post_meta( $post->ID, $the_field, true );
      set_post_thumbnail( $post->ID, $artwork_image );
    } else {
      delete_post_thumbnail();
    }
  }

  /**
   * Populate the ag_artwork_availability terms with the three permitted options:
   * "Available", "Sold", or "Not For Sale"
   */
  public function artgallery_set_ag_artwork_availability_options() {

    wp_insert_term( 'Available', 'ag_artwork_availability', array(
      'description' => 'Artwork is available for purchase',
      'slug' => 'available'
    ) );

    wp_insert_term( 'Sold', 'ag_artwork_availability', array(
      'description' => 'Artwork has been sold',
      'slug' => 'sold'
    ) );

    wp_insert_term( 'Not For Sale', 'ag_artwork_availability', array(
      'description' => 'Artwork is not for sale',
      'slug' => 'nfs'
    ) );

  }

  /**
   * Define default terms for custom taxonomies in WordPress 3.0.1
   * (Works the same way that a post's Category defaults to Uncategorized)
   *
   * @author    Michael Fields     http://wordpress.mfields.org/
   * @props     John P. Bloch      http://www.johnpbloch.com/
   * @props     Evan Mulins        http://circlecube.com/
   *
   * @since     2010-09-13
   * @alter     2013-01-31
   */
  function artgallery_set_default_availability_state( $post_id, $post ) {

    /* Default terms by taxonomy:
     *
     * Availability: Available
     * Media: (none)
     * Dimensions: (none)
     * Category: (none)
     */
    $defaults = array(
      'ag_artwork_availability' => array( 'available' )
    );

    // Verify that we're publishing an artwork item, and not something else
    if ( 'publish' === $post->post_status && $post->post_type === 'ag_artwork_item' ) {
      // Get the taxonomies available for this post type
      $taxonomies = get_object_taxonomies( $post->post_type );

      foreach ( (array) $taxonomies as $taxonomy ) {

        $terms = wp_get_post_terms( $post_id, $taxonomy );
        if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
          wp_set_object_terms( $post_id, $defaults[ $taxonomy ], $taxonomy );
        }

      };
    }

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
