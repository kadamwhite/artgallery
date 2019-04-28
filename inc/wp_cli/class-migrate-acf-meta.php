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

/**
 * Class Migrate_Metadata
 * Copy ACF meta values into their new, proper metadata fields.
 */
class Migrate_ACF_Meta {
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
	 *     wp artgallery-migrate-acf-meta
	 *
	 *     wp artgallery-migrate-acf-meta --dry-run
	 *
	 * @param array $args       List of arguments passed to the commands.
	 * @param array $assoc_args Arguments passed to the command parsed into key/value pairs.
	 */
	public function __invoke( $args, $assoc_args ) {
		// Grab associative args.
		$dry_run = isset( $assoc_args['dry-run'] )
			? (bool) $assoc_args['dry-run']
			: false;

		WP_CLI::line( 'Converting ACF Metadata' . ( $dry_run ? ' -- dry run ' : '' ) );
		WP_CLI::line( "-------------------------------------\n" );

		$log = function( $message ) {
			WP_CLI::line( $message );
		};

		Post_Types\for_all_artworks( function( $artwork ) use ( $dry_run, $log ) {
			WP_CLI::line( "Processing $artwork->ID, $artwork->post_title" );

			if ( Migrations\convert_dimensions_taxonomy_to_meta( $artwork, $dry_run, $log ) ) {
				WP_CLI::success( 'Converted dimensions taxonomy term to meta values' );
			}

			if ( Migrations\update_meta_keys( $artwork, $dry_run, $log ) ) {
				WP_CLI::success( 'Updated meta keys' );
			}
		} );
	}
}
