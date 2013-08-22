<?php
/**
 * Artwork Dimensions widget class
 *
 * This is the native WP `WP_Widget_Categories` class, altered to refer to the
 * `ag_artwork_dimensions` taxonomy instead of categories. Dimensions are
 * sorted based on total area, which is determined by assuming that dimension
 * term names were entered in the format `15" x 20"`.
 *
 * Due to a lack of flexibility on the part of the `wp_dropdown_categories`
 * function and the native JS for this widget's rendering, we do not
 * currently support listing dimensions as a dropdown.
 */
class ArtGallery_Widget_Dimensions extends WP_Widget {

  function __construct() {
    // Construct the widget instance
    parent::__construct(
      'ag_artwork_widget_dimensions',
      __( 'Artwork Dimensions', 'artgallery' ),
      array(
        'classname' => 'widget_dimensions',
        'description' => __( 'A list of artwork dimensions', 'artgallery' )
      )
    );

    // Customize the ordering of the list
    add_filter('wp_list_categories', array( $this, 'reorder_dimensions_term_list' ), 10, 2 );
  }

  /**
   * Intercept the output of the widget and re-order terms based on area
   */
  public function reorder_dimensions_term_list( $output, $args ) {
    if ( array_key_exists('taxonomy', $args) && $args['taxonomy'] != 'ag_artwork_dimensions' ) {
      return $output;
    }
    // Break string apart into individual list items
    $terms = explode( '</li>', $output );
    // Sort the array by computing the area indicated by each term
    usort( $terms, array( $this, 'compare_dimensions' ) );
    // If we're sorting descending, reverse the array
    if ( $args['order'] == 'DESC' ) {
      $terms = array_reverse( $terms );
    }
    // Re-assemble the HTML
    return implode( $terms, '</li>' );
  }

  /**
   * Comparison function for use in sorting the terms array by artwork area
   */
  private function compare_dimensions( $a, $b ){
    $first = $this->get_dimensions( $a );
    $second = $this->get_dimensions( $b );
    if ( $first == $second ) {
      return 0;
    }
    return ( $first < $second ) ? -1 : 1;
  }

  /**
   * Parse the human-readable term name as a string, and return the computed area
   */
  private function get_dimensions( $term_markup ) {
    // Fairly inflexible string parsing starts here
    $pattern = '/>([0-9]+\.?[0-9]*)&quot;\s?x\s?([0-9]+\.?[0-9]*)&/';
    // Test the term, and continue if the pattern was matched
    if ( preg_match( $pattern, $term_markup, $matches ) ) {
      return floatval( $matches[1] ) * floatval( $matches[2] );
    }
    return 0;
  }

  function widget( $args, $instance ) {
    extract( $args );

    $title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Artwork Dimensions' ) : $instance['title'], $instance, $this->id_base);
    $c = ! empty( $instance['count'] ) ? '1' : '0';
    $h = ! empty( $instance['hierarchical'] ) ? '1' : '0';
    // $d = ! empty( $instance['dropdown'] ) ? '1' : '0';
    $dsc = ! empty( $instance['orderdsc'] ) ? '1' : '0';

    echo $before_widget;
    if ( $title )
      echo $before_title . $title . $after_title;

    $cat_args = array('orderby' => 'name', 'show_count' => $c, 'hierarchical' => $h);

    // Set the taxonomy to query
    $cat_args['taxonomy'] = 'ag_artwork_dimensions';
    // Order ascending or descending
    $cat_args['order'] = $dsc ? 'DESC' : 'ASC';

    /*
    if ( $d ) {
      $cat_args['show_option_none'] = __('Select Category');
      wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args));
    ?>
    <script type='text/javascript'>
      var dropdown = document.getElementById("cat");
      function onCatChange() {
        if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
          location.href = "<?php echo home_url(); ?>/?cat="+dropdown.options[dropdown.selectedIndex].value;
        }
      }
      dropdown.onchange = onCatChange;
    </script>
    <?php
    } else {
    */
    ?>
    <ul>
    <?php
    $cat_args['title_li'] = '';
    wp_list_categories(apply_filters('widget_categories_args', $cat_args));
    ?>
    </ul>
    <?php
    //}

    echo $after_widget;
  }

  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['count'] = !empty($new_instance['count']) ? 1 : 0;
    $instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
    // $instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;
    $instance['orderdsc'] = !empty($new_instance['orderdsc']) ? 1 : 0;

    return $instance;
  }

  function form( $instance ) {
    //Defaults
    $instance = wp_parse_args( (array) $instance, array( 'title' => '') );
    $title = esc_attr( $instance['title'] );
    $count = isset($instance['count']) ? (bool) $instance['count'] :false;
    $hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
    // $dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
    $orderdsc = isset( $instance['orderdsc'] ) ? (bool) $instance['orderdsc'] : false;
    ?>
    <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

    <?php /*
    <p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
    <label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Display as dropdown' ); ?></label><br />
    */ ?>
    <p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('orderdsc'); ?>" name="<?php echo $this->get_field_name('orderdsc'); ?>"<?php checked( $orderdsc ); ?> />
    <label for="<?php echo $this->get_field_id('orderdsc'); ?>"><?php _e( 'Order largest to smallest' ); ?></label><br />

    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
    <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts' ); ?></label><br />

    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked( $hierarchical ); ?> />
    <label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show hierarchy' ); ?></label></p>
    <?php
  }
} // ArtGallery_Dimensions_List_Widget
