<?php
/**
 * Hooks for the Artwork Grid block.
 */
namespace ArtGallery\Blocks\Artwork_Grid;

/**
 * Filter the "sizes" attribute output as part of a responsive image tag.
 *
 * This method COULD use all of the specified attributes below; but it only
 * utilizes that first $attr property, because we're hard-coding the value
 * to return rather than deriving it from the post or sizes.
 *
 * @param array        $attr       Attributes for the image markup.
 * @param WP_Post      $attachment Image attachment post.
 * @param string|array $size       Requested size. Image size or array of width and height values
 *                                 (in that order). Default 'thumbnail'.
 *
 * @return string The filtered attributes object.
 */
function filter_image_attributes( array $attr ): array {
	return array_merge( $attr, [
		// We hard-code the "sizes" attribute for our grid's responsive markup.
		// The dimensions are calculated assuming the largest possible block width;
		// the breakpoints driving the styles are determined by the breakpoints
		// specified in the render method.
		'sizes' => '(min-width: 480px) 320px, 160px',
	] );
}
