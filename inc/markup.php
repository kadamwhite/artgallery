<?php
/**
 * Define functions returning reusable markup for specific objects.
 */
namespace ArtGallery\Markup;

use ArtGallery\Meta;
use ArtGallery\Taxonomies;
use WP_Post;

/**
 * Render the HTML markup for an artwork item thumbnail.
 *
 * @param WP_Post $artwork The artwork to render as a thumbnail.
 * @param string  $block   (optional) The BEM block class name to use when rendering this markup.
 *
 * @return string The thumbnail HTML markup string.
 */
function artwork_thumbnail( WP_Post $artwork, string $block = 'artwork-grid' ) : string {
	ob_start();

	/* phpcs:disable Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */
	/* phpcs:disable WordPress.Arrays.ArrayIndentation */
	?>
	<a class="<?php echo $block; ?>__link" href="<?php echo get_permalink( $artwork->ID ); ?>">
		<?php echo get_the_post_thumbnail( $artwork, 'ag_square_sm' ); ?>
		<div class="<?php echo $block; ?>__info">
			<p class="<?php echo $block; ?>__title"><?php echo $artwork->post_title; ?></p>
			<?php
			$dimensions = Meta\get_artwork_dimensions( $artwork->ID );
			$media = Taxonomies\get_media_list( $artwork->ID );
			if ( $dimensions || $media ) :
				?>
				<p class="<?php echo $block; ?>__meta">
					<?php
					if ( $dimensions ) { echo $dimensions; }
					if ( $dimensions && $media ) { echo '; '; }
					if ( $media ) { echo $media; }
					?>
				</p>
				<?php
			endif;
			?>
		</div><!-- .$block__info -->
	</a><!-- .$block__link -->
	<?php
	/* phpcs:enable WordPress.Arrays.ArrayIndentation */
	/* phpcs:enable Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */

	return clean_whitespace( ob_get_clean() );
}

/**
 * Render the markup for a thumbnail grid of artworks.
 *
 * @param array  $artworks    An array of artwork post items.
 * @param array  $breakpoints (optional) An array of classes to apply when the container is a certain size.
 * @param string $align       (optional) "full" or "wide", to control output width when called from a block.
 * @param string $block       (optional) The BEM block class name to use when rendering this markup.
 *
 * @return string The rendered thumbnail grid output markup.
 */
function artwork_thumbnail_grid( array $artworks, array $breakpoints, string $align = '', string $block = 'artwork-grid' ) : string {
	// Define the container dimensions at which the different breakpoints kick in,
	// and encode as JSON for output in an attribute.
	if ( empty( $breakpoints ) ) {
		$breakpoints = wp_json_encode( [
			'two-up'   => 0,
			'three-up' => 420,
			'four-up'  => 640,
		] );
	} else {
		$breakpoints = wp_json_encode( $breakpoints );
	}

	ob_start();

	?>
	<div
		class="<?php echo trim( $block . ' ' . $align ); ?>"
		<?php if ( ! empty( $breakpoints ) ) : ?>
		data-breakpoints="<?php echo esc_attr( $breakpoints ); ?>"
		data-responsive-container
		<?php endif; ?>
	>
		<div class="<?php echo $block; ?>__container">
			<?php
			foreach ( $artworks as $artwork ) {
				echo artwork_thumbnail( $artwork, $block );
			}
			?>
		</div><!-- .$block__container -->
	</div><!-- .$block -->
	<?php

	return clean_whitespace( ob_get_clean() );
}

/**
 * Strip comments & unnecessary whitespace from an HTML markup string.
 *
 * @param string $markup A markup string to clean.
 * @return string The cleaned markup string.
 */
function clean_whitespace( string $markup ) {
	return trim( array_reduce(
		// Define an array of patterns and their corresponding replacements.
		[
			// Strip out comments.
			[ '/<!--.*?-->/', '' ],
			// Remove newlines & tabs between adjacent tags.
			[ '/>[\n\t]+</', '><' ],
			// Collapse any other newlines to a single whitespace character.
			[ '/[\n\t]+/', ' ' ],
		],
		/**
		 * Apply a preg_replace action to a string.
		 *
		 * @param string $carry          The string being transformed.
		 * @param array  $transformation A `[ $pattern, $replacement ]` string pair, as an array.
		 *
		 * @return string The transformed markup.
		 */
		function( $carry, $replacement ) {
			return preg_replace( $replacement[0], $replacement[1], $carry );
		},
		$markup
	) );
}
