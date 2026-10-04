<?php
/**
 * Render the date, dimensions and media of an artwork.
 *
 * @package ArtGallery
 */

use ArtGallery\Meta;
use ArtGallery\Taxonomies;

$artwork_id = $block->context['postId'] ?? get_the_ID();

if ( ! $artwork_id ) {
	return;
}

$date = Meta\get_artwork_date( $artwork_id, $attributes['date'] ?? null );

$term_links = Taxonomies\get_media_list( $artwork_id, true );

$dimensions = Meta\get_artwork_dimensions( $artwork_id, $attributes );

$block_output = '';

if ( ! empty( $date ) ) {
	$block_output .= esc_html( $date ) . '. ';
}
if ( ! empty( $dimensions ) ) {
	$block_output .= esc_html( $dimensions );
	if ( ! empty( $term_links ) ) {
		$block_output .= '; ';
	}
}
if ( ! empty( $term_links ) ) {
	$block_output .= $term_links . '.';
}

if ( empty( $block_output ) ) {
	return;
}
?>
<p <?php echo get_block_wrapper_attributes( [ 'class' => 'artwork-meta' ] ); // phpcs:ignore HM.Security.EscapeOutput.OutputNotEscaped ?>><?php echo wp_kses_post( $block_output ); ?></p>
