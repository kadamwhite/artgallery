<?php
/**
 * Server-rendered Artwork Grid block.
 */
namespace ArtGallery\Blocks\Artwork_Grid;

use ArtGallery\Meta;
use ArtGallery\Post_Types;
use ArtGallery\Taxonomies;
use WP_Query;

const BLOCK_NAME = 'artgallery/artwork-grid';

function setup() {
	add_action( 'init', __NAMESPACE__ . '\\register_block' );
}

/**
 * Register the Featured Items List dynamic block.
 */
function register_block() {
	register_block_type( BLOCK_NAME, [
		'attributes' => [
			'align' => [
				'type' => 'string',
			],
		],
		'render_callback' => __NAMESPACE__ . '\\render_artwork_grid',
	] );
}

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
function filter_image_attributes( array $attr ) : array {
	return array_merge( $attr, [
		// We hard-code the "sizes" attribute for our grid's responsive markup.
		// The dimensions are calculated assuming the largest possible block width;
		// the breakpoints driving the styles are determiend by the breakpoints
		// specified in the render method.
		'sizes' => '(min-width: 480px) 320px, 160px',
	] );
}

/**
 * Render the HTML for the artwork thumbnail grid.
 *
 * @param array $attributes The block attributes.
 * @return string The rendered block markup, as an HTML string.
 */
function render_artwork_grid( array $attributes ) : string {
	$align = isset( $attributes['align'] ) ? (string) $attributes['align'] : '';
	if ( ! empty( $align ) ) {
		$align = "align$align";
	}

	$recent_artwork = new WP_Query( [
		'post_type'      => Post_Types\ARTWORK_POST_TYPE,
		'posts_per_page' => 9, // Only 8 will display on certain screen sizes.
	] );

	// Define the container dimensions at which the different breakpoints kick in.
	$breakpoints = wp_json_encode( [
		'two-up'   => 0,
		'three-up' => 420,
		'four-up'  => 640,
	] );

	add_filter( 'wp_get_attachment_image_attributes', __NAMESPACE__ . '\\filter_image_attributes', 10, 1 );
	ob_start();
	/* phpcs:disable Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */
	/* phpcs:disable WordPress.Arrays.ArrayIndentation */
	?>
	<div
		class="artwork-grid <?php echo $align; ?>"
		data-breakpoints="<?php echo esc_attr( $breakpoints ); ?>"
		data-responsive-container
	>
		<div class="artwork-grid__container">
			<?php foreach ( $recent_artwork->posts as $artwork ) : ?>
			<a class="artwork-grid__link" href="<?php echo get_permalink( $artwork->ID ); ?>">
				<?php echo get_the_post_thumbnail( $artwork, 'ag_square_sm' ); ?>
				<div class="artwork-grid__info">
					<p class="artwork-grid__title"><?php echo $artwork->post_title; ?></p>
					<?php
					$dimensions = Meta\get_artwork_dimensions( $artwork->ID );
					$media = Taxonomies\get_media_list( $artwork->ID );
					if ( $dimensions || $media ) :
						?>
						<p class="artwork-grid__meta">
							<?php if ( $dimensions ) { echo $dimensions; } ?>
							<?php if ( $dimensions && $media ) { echo '; '; } ?>
							<?php if ( $media ) { echo $media; } ?>
						</p>
						<?php
					endif;
					?>
				</div><!-- .artwork-grid__info -->
			</a><!-- .artwork-grid__link -->
			<?php endforeach; ?>
		</div><!-- .artwork-grid__container -->
	</div><!-- .artwork-grid -->
	<?php
	/* phpcs:enable WordPress.Arrays.ArrayIndentation */
	/* phpcs:enable Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */

	$block_output = ob_get_contents();
	ob_end_clean();
	remove_filter( 'wp_get_attachment_image_attributes', __NAMESPACE__ . '\\filter_image_attributes' );

	// Strip comments, and collapse newline and tab whitespace in this output buffer.
	return preg_replace( '/<!--.*?-->/', '', preg_replace( '/\n\t+/', ' ', $block_output ) );
}
