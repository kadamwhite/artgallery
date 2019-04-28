<?php
/**
 * Define migrations and transformations to bring artwork item content & metadata up to date.
 *
 * Used by WP-CLI commands and DB upgrade routines.
 */
namespace ArtGallery\Migrations;

use ArtGallery\Meta;
use ArtGallery\Taxonomies;
use WP_Post;

/**
 * Convert a dimensions taxonomy term into metadata for a specific artwork.
 *
 * @param WP_Post  $artwork   Artwork post object.
 * @param bool     [$dry_run] Whether this is a dry run of the action.
 * @param callable [$log]     A function to call to log changes.
 * @return bool False if failure or noop, True if successfully updated.
 */
function convert_dimensions_taxonomy_to_meta( WP_Post $artwork, bool $dry_run = true, callable $log = null ) {
	if ( ! $log ) {
		// Stub as noop if no log method provided.
		$log = function() {};
	}
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

			$log( "- Converting dimensions {$width}\" x {$height}\" to meta values" );
			if ( ! $dry_run ) {
				update_post_meta( $artwork_id, Meta\ARTWORK_WIDTH, $width );
				update_post_meta( $artwork_id, Meta\ARTWORK_HEIGHT, $height );
			}
			$log( '- Removing legacy dimensions taxonomy term from artwork' );
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
 * @param WP_Post  $artwork   Artwork post object.
 * @param bool     [$dry_run] Whether this is a dry run of the action.
 * @param callable [$log]     A function to call to log changes.
 * @return bool False if failure or noop, True if successfully updated.
 */
function update_meta_keys( WP_Post $artwork, bool $dry_run = true, callable $log = null ) {
	if ( ! $log ) {
		// Stub as noop if no log method provided.
		$log = function() {};
	}
	$artwork_id = $artwork->ID;
	$success = false;

	$legacy_date = (string) get_post_meta( $artwork_id, 'artwork_date_created', true );
	if ( $legacy_date ) {
		$log( '- Migrating legacy meta key "artwork_date_created"' );
		if ( ! $dry_run ) {
			update_post_meta( $artwork_id, Meta\ARTWORK_DATE, $legacy_date );
			delete_post_meta( $artwork_id, 'artwork_date_created' );
		}
		$success = true;
	}

	return $success;
}
