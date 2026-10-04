<?php
/**
 * Render the Artwork Grid block.
 *
 * @package ArtGallery
 */

use ArtGallery\Markup;
use ArtGallery\Post_Types;

$recent_artwork = new WP_Query( [
	'post_type'      => Post_Types\ARTWORK_POST_TYPE,
	'posts_per_page' => 9, // Only 8 will display on certain screen sizes.
] );

$filter_image_attributes = 'ArtGallery\\Blocks\\Artwork_Grid\\filter_image_attributes';

add_filter( 'wp_get_attachment_image_attributes', $filter_image_attributes, 10, 1 );
?>
<div <?php echo get_block_wrapper_attributes( [ 'class' => 'artwork-grid' ] ); ?>>
	<div class="artwork-grid__container">
		<?php
		foreach ( $recent_artwork->posts as $artwork ) {
			echo Markup\artwork_thumbnail( $artwork, 'artwork-grid' ); // phpcs:ignore HM.Security.EscapeOutput.OutputNotEscaped
		}
		?>
	</div>
</div>
<?php
remove_filter( 'wp_get_attachment_image_attributes', $filter_image_attributes );
