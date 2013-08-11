<?php
/**
 * ArtGallery Plugin
 *
 * @package   artgallery
 * @author    K.Adam White <adam@kadamwhite.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 K.Adam White
 */

/**
 * Art Gallery Sidebar Widget
 *
 *
 * @package artgallery
 * @author  K.Adam White <adam@kadamwhite.com>
 */
class ArtGallery_Widget extends WP_Widget {

  public function __construct() {
    // widget actual processes
    parent::__construct(
      'ag_artgallery_widget', // Base ID
      'Artwork Thumbnails', // Name
      array(
        'classname' => 'ag_gallery_widget',
        'description' => __( 'A thumbnail gallery of your most recent Artwork items', 'artgallery' ),
      ) // Args
    );
  }

  /**
   * Front-end display of widget.
   *
   * @see WP_Widget::widget()
   *
   * @param array $args     Widget arguments.
   * @param array $instance Saved values from database.
   */
  public function widget( $args, $instance ) {
    // outputs the content of the widget
    $artwork_query = new WP_Query( array(
      'post_type' => 'ag_artwork_item',
      'posts_per_page' => intval( $instance['limit'] )
    ) );

    $title = apply_filters( 'widget_title', $instance['title'] );

    echo $args['before_widget'];
    if ( ! empty( $title ) ) {
      echo $args['before_title'] . $title . $args['after_title'];
    }

    if ( $artwork_query->have_posts() ) {
      while ( $artwork_query->have_posts() ) : $artwork_query->the_post();
      ?>
      <a href="<?php the_permalink(); ?>"
         title="<?php echo esc_attr( ag_artwork_title_attribute( get_the_ID() ) ); ?>"
         rel="bookmark">
          <?php the_post_thumbnail( array( 60, 60 ) ); ?>
      </a>
      <?php
      endwhile;
    } else {
      echo __( 'No artwork found', 'emilygarfield' );
    }
    echo $args['after_widget'];
  }

  /**
   * Back-end widget form.
   *
   * @see WP_Widget::form()
   *
   * @param array $instance Previously saved values from database.
   */
  public function form( $instance ) {
    // outputs the options form on admin
    // Get the widget's parameter values (and provide sensible defaults)
    $instance = wp_parse_args( (array) $instance, array(
      'title' => __( 'Recent Artwork', 'emilygarfield' ),
      'limit' => 9
    ) );
    // Define $limit (parsed as a number), and reiterate the default of 9 images
    if ( ! $limit = intval( $instance['limit'] ) ) {
      $limit = 9;
    }
    ?>
    <p>
      <label for="<?php echo $this->get_field_name('title'); ?>">
        <?php _e( 'Title:' ); ?>
      </label>
      <input class="widefat"
             id="<?php echo $this->get_field_id('title'); ?>"
             name="<?php echo $this->get_field_name('title'); ?>"
             type="text"
             value="<?php echo esc_attr( $instance['title'] ); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('limit'); ?>">
        <?php __( 'Number of images to show:', 'emilygarfield' ); ?>
      </label>
      <input id="<?php echo $this->get_field_id('limit'); ?>"
             name="<?php echo $this->get_field_name('limit'); ?>"
             size="3"
             type="text"
             value="<?php echo $limit == -1 ? '' : intval( $limit ); ?>" />
    </p>
    <?php 
  }

  /**
   * Sanitize widget form values as they are saved.
   *
   * @see WP_Widget::update()
   *
   * @param array $new_instance Values just sent to be saved.
   * @param array $old_instance Previously saved values from database.
   *
   * @return array Updated safe values to be saved.
   */
  public function update( $new_instance, $old_instance ) {
    // processes widget options to be saved
    $instance = array();
    $instance['title'] = ! empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
    $instance['limit'] = ! empty( $new_instance['limit'] ) ? intval( $new_instance['limit'] )     : -1;

    return $instance;
  }
}
