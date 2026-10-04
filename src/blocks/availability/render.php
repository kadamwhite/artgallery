<?php
/**
 * Render an availability message if the artwork is for sale.
 *
 * @package ArtGallery
 */

use ArtGallery\Taxonomies;

global $post;

// Status is not saved in post content, but ServerSideRender passes it in so
// that the editor preview reflects a pending term assignment. Otherwise,
// retrieve the status from the assigned terms.
$status = ! empty( $attributes['status'] ) ?
	$attributes['status'] :
	Taxonomies\get_availability_slug( $post->ID );

if ( 'available' !== $status || empty( $attributes['message'] ) ) {
	return;
}
?>
<p <?php echo get_block_wrapper_attributes(); // phpcs:ignore HM.Security.EscapeOutput.OutputNotEscaped ?>><?php echo wp_kses_post( $attributes['message'] ); ?></p>
