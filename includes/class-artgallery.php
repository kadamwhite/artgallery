<?php
/**
 * Plugin Name.
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

    add_action( 'init', array( $this, 'artgallery_register_post_types' ) );
    add_action( 'init', array( $this, 'artgallery_register_taxonomies' ) );

    add_action( 'admin_init', array( $this, 'artgallery_admin_init' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'artgallery_admin_enqueue_scripts' ) );

    add_filter( 'the_content', array( $this, 'artgallery_content' ) );

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
      'labels' => array (
        'name' => 'Artworks',
        'singular_name' => 'Artwork',
        'menu_name' => 'Artworks',
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

    register_taxonomy( 'ag_artwork_media', array ( 0 => 'ag_artwork_item', ), array(
      'hierarchical' => true,
      'label' => 'Media',
      'singular_label' => 'Medium',
      'show_ui' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'rewrite' => array(
        'slug' => 'media'
      ),
      'labels' => array (
        'add_new_item' => 'Add New Medium'
      )
    ));

    register_taxonomy( 'ag_artwork_dimensions', array ( 0 => 'ag_artwork_item', ), array(
      'hierarchical' => true,
      'label' => 'Dimensions',
      'show_ui' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'rewrite' => array(
        'slug' => 'dimensions'
      ),
      'labels' => array (
        'name' => 'Dimensions',
        'menu_name' => 'Dimensions',
        'add_new_item' => 'Add New Dimensions'
      )
    ));

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
   * Enqueue styles for use in the edit artwork screen
   *
   * @since 0.0.2
   */
  public function artgallery_admin_init() {
    add_action( 'acf/save_post', array( $this, 'acf_save_featured' ), 20 );

    wp_register_style( 'artgallery-admin', ARTGALLERY_URL . 'assets/css/artgallery-admin.css' );
  }
  public function artgallery_admin_enqueue_scripts() {
    wp_enqueue_style( 'artgallery-admin' );
  }


  /**
   * Render Art Gallery content
   *
   * @since 0.0.2
   */
  public function artgallery_content( $content ) {
    global $post;

    // Do nothing if the content isn't an artwork item
    if ( $post->post_type != 'ag_artwork_item' ) {
      return $content;
    }

    $dimensions = self::get_taxonomy_list( $post->id, 'ag_artwork_dimensions' );

    $media = self::get_taxonomy_list( $post->id, 'ag_artwork_media' );

    // Render the image
    if ( is_archive() ) {
      the_post_thumbnail( 'thumbnail' );
    } else {
      the_post_thumbnail( 'large' );
    }

    // Append taxonomy lists
    if ( $dimensions ) {
      $content = '<p>' . $dimensions . '</p>' . $content;
    }
    if ( $media ) {
      $content = '<p>' . $media . '</p>' . $content;
    }

    return $content;
  }

  /**
   * Retrieve a list of taxonomy terms for the provided post ID
   *
   * @param string $taxonomy_name The name of the taxonomy.
   * @param int $post_id The ID of the post for which to fetch those taxonomy terms.
   * @param bool $plain_text Optional. Return as plain text or as HTML with links (default).
   * @return string Comma-separated list of media.
   */
  public function get_taxonomy_list( $post_id, $taxonomy_name, $plain_text = false ) {
    if ( $plain_text ) {
      return implode(
        wp_get_object_terms( $post_id, $taxonomy_name, array( 'fields' => 'names' ) ),
        ', '
      );
    }

    return get_the_term_list( $post_id, $taxonomy_name, 'Media: ', ', ', '' );
  }
}
