<?php
/**
 * Render an availability message if the artwork is for sale.
 *
 * @package ArtGallery
 */

use ArtGallery\Taxonomies;

$post_id = $block->context['postId'] ?? get_the_ID();

if ( ! $post_id ) {
	return;
}

// Status is not saved in post content, but ServerSideRender passes it in so
// that the editor preview reflects a pending term assignment. Otherwise,
// retrieve the status from the assigned terms.
$status = ! empty( $attributes['status'] ) ?
	$attributes['status'] :
	Taxonomies\get_availability_slug( $post_id );

if ( 'available' !== $status || empty( $attributes['message'] ) ) {
	return;
}
?>
<p <?php echo get_block_wrapper_attributes(); ?>><?php echo wp_kses_post( $attributes['message'] ); ?></p>
