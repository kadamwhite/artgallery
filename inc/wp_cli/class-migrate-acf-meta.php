<?php
/**
 * WP CLI script to convert ACF metadata into normal WordPress metadata.
 *
 * @package techcrunch-2017
 */

namespace ArtGallery\WP_CLI;

use ArtGallery\Meta;
use ArtGallery\Post_Types;
use ArtGallery\Taxonomies;
use WP_CLI;
use WP_Post;
use WP_Query;

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

		$artworks = new WP_Query( [
			'post_type'   => [ Post_Types\ARTWORK_POST_TYPE ],
			'post_status' => [ 'any' ],
			'nopaging'    => true,
		] );

		if ( $artworks->have_posts() ) {
			while ( $artworks->have_posts() ) {
				$artworks->the_post();

				$artwork = get_post();

				WP_CLI::line( sprintf( "\nProcessing %s, %s", $artwork->ID, $artwork->post_title ) );

				if ( $this->migrate_dimensions( $artwork, $dry_run ) ) {
					WP_CLI::success( 'Converted dimensions taxonomy term to meta values' );
				}

				if ( $this->migrate_meta_keys( $artwork, $dry_run ) ) {
					WP_CLI::success( 'Updated meta keys' );
				}
			}
		}
	}

	/**
	 * Convert a dimensions taxonomy term into metadata for a specific artwork.
	 *
	 * @param WP_Post $artwork Artwork post object.
	 * @return bool False if failure or noop, True if successfully updated.
	 */
	private function migrate_dimensions( WP_Post $artwork, bool $dry_run = true ) {
		$artwork_id = $artwork->ID;

		$dimensions = wp_get_post_terms( $artwork_id, Taxonomies\DIMENSIONS_TAXONOMY );

		if ( ! empty( $dimensions ) ) {
			preg_match(
				'/([0-9]+(?:\.[0-9]*)?)" *x *([0-9]+(?:\.[0-9]*)?)"/',
				$dimensions[0]->name,
				$matches
			);

			if ( ! empty( $matches ) && isset( $matches[1] ) && isset( $matches[2] ) ) {
				$width = (float) $matches[1];
				$height = (float) $matches[2];

				WP_CLI::line( "- Converting dimensions {$width}\" x {$height}\" to meta values" );
				if ( ! $dry_run ) {
					update_post_meta( $artwork_id, Meta\ARTWORK_WIDTH, $width );
					update_post_meta( $artwork_id, Meta\ARTWORK_HEIGHT, $height );
				}
				WP_CLI::line( '- Removing legacy dimensions taxonomy term from artwork' );
				if ( ! $dry_run ) {
					wp_delete_object_term_relationships( $artwork_id, Taxonomies\DIMENSIONS_TAXONOMY );
				}
				return true;
			}
		}

		return false;
	}

	/**
	 * Convert old meta keys to new values for a specific artwork.
	 *
	 * @param WP_Post $artwork Artwork post object.
	 * @return bool False if failure or noop, True if successfully updated.
	 */
	private function migrate_meta_keys( WP_Post $artwork, bool $dry_run = true ) {
		$artwork_id = $artwork->ID;
		$success = false;

		$legacy_date = (string) get_post_meta( $artwork_id, 'artwork_date_created', true );
		if ( $legacy_date ) {
			WP_CLI::line( '- Migrating legacy meta key "artwork_date_created"' );
			if ( ! $dry_run ) {
				update_post_meta( $artwork_id, Meta\ARTWORK_DATE, $legacy_date );
				delete_post_meta( $artwork_id, 'artwork_date_created' );
			}
			$success = true;
		}

		return $success;
	}
}
