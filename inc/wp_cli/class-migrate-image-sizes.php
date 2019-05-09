<?php
/**
 * WP CLI script to convert ACF metadata into normal WordPress metadata.
 *
 * @package techcrunch-2017
 */

namespace ArtGallery\WP_CLI;

use ArtGallery\Migrations;
use ArtGallery\Post_Types;
use WP_CLI;
use WP_Query;

/**
 * Class Migrate_Image_Sizes
 * Replace defunct image sizes in post content with a supported size.
 */
class Migrate_Image_Sizes {
	/**
	 * Copy legacy ACF metadata values to new, properly-registered artwork meta keys.
	 *
	 * ## OPTIONS
	 *
	 * [--dry-run]
	 * : Run the command without committing changes.
	 *
	 * ## EXAMPLES
	 *
	 *     wp artgallery-migrate-image-sizes
	 *
	 *     wp artgallery-migrate-image-sizes --dry-run
	 *
	 * @param array $args       List of arguments passed to the commands.
	 * @param array $assoc_args Arguments passed to the command parsed into key/value pairs.
	 */
	public function __invoke( $args, $assoc_args ) {
		// Grab associative args.
		$dry_run = isset( $assoc_args['dry-run'] )
			? (bool) $assoc_args['dry-run']
			: false;

		WP_CLI::line( 'Updating Image Tags' . ( $dry_run ? ' -- dry run ' : '' ) );
		WP_CLI::line( "-------------------------------------\n" );

		$log = function( $message ) {
			WP_CLI::line( $message );
		};

		$query = new WP_Query( [
			'post_type'   => [ 'post', Post_Types\ARTWORK_POST_TYPE ],
			'post_status' => [ 'any' ],
			'nopaging'    => true,
		] );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				if ( Migrations\update_image_sizes( get_post(), $dry_run, $log ) ) {
					WP_CLI::success( 'Image tags updated.' );
				}
			}
		}
	}
}
