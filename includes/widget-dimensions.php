<?php
/**
 * Categories widget class
 *
 * @since 2.8.0
 */
class WP_Widget_Categories extends WP_Widget {

  function __construct() {
    $widget_ops = array( 'classname' => 'widget_categories', 'description' => __( "A list or dropdown of categories" ) );
    parent::__construct('categories', __('Categories'), $widget_ops);
  }

  function widget( $args, $instance ) {
    extract( $args );

    $title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Categories' ) : $instance['title'], $instance, $this->id_base);
    $c = ! empty( $instance['count'] ) ? '1' : '0';
    $h = ! empty( $instance['hierarchical'] ) ? '1' : '0';
    $d = ! empty( $instance['dropdown'] ) ? '1' : '0';

    echo $before_widget;
    if ( $title )
      echo $before_title . $title . $after_title;

    $cat_args = array('orderby' => 'name', 'show_count' => $c, 'hierarchical' => $h);

    if ( $d ) {
      $cat_args['show_option_none'] = __('Select Category');
      wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args));
      ?>
      <script type='text/javascript'>
      /* <![CDATA[ */
        var dropdown = document.getElementById("cat");
        function onCatChange() {
          if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
            location.href = "<?php echo home_url(); ?>/?cat="+dropdown.options[dropdown.selectedIndex].value;
          }
        }
        dropdown.onchange = onCatChange;
      /* ]]> */
      </script>
      <?php
    } else {
    ?>
    <ul>
    <?php
    $cat_args['title_li'] = '';
    wp_list_categories(apply_filters('widget_categories_args', $cat_args));
    ?>
    </ul>
    <?php
    }

    echo $after_widget;
  }

  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['count'] = !empty($new_instance['count']) ? 1 : 0;
    $instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
    $instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;

    return $instance;
  }

  function form( $instance ) {
    //Defaults
    $instance = wp_parse_args( (array) $instance, array( 'title' => '') );
    $title = esc_attr( $instance['title'] );
    $count = isset($instance['count']) ? (bool) $instance['count'] :false;
    $hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
    $dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
    ?>
    <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

    <p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
    <label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Display as dropdown' ); ?></label><br />

    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
    <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts' ); ?></label><br />

    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked( $hierarchical ); ?> />
    <label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show hierarchy' ); ?></label></p>
    <?php
  }
}
