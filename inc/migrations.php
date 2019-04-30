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

/**
 * Populate the post content for an artwork based on the artwork's assigned
 * featured media, terms, and other metadata.
 *
 * @param WP_Post  $artwork   Artwork post object.
 * @param bool     [$dry_run] Whether this is a dry run of the action.
 * @param callable [$log]     A function to call to log changes.
 * @return bool False if failure or noop, True if successfully updated.
 */
function populate_artwork_post_content( WP_Post $artwork, bool $dry_run = true, callable $log = null ) {
	$existing_content = trim( $artwork->post_content );
	if ( ! empty( $existing_content ) ) {
		$log( '-- Existing content found! Check post after migration. -' );
		$maybe_video = preg_match( '/vimeo|youtube/', $existing_content );
	} else {
		$maybe_video = false;
	}

	$new_post_content = '';

	if ( $maybe_video ) {
		$new_post_content .= $existing_content . "\n\n";
	}

	$featured_image_id = get_post_thumbnail_id( $artwork->ID );
	$featured_image_url = wp_get_attachment_url( $featured_image_id );
	if ( ! $maybe_video && $featured_image_id && $featured_image_url ) {
		$new_post_content .= sprintf( '<!-- wp:image {"id":%s} -->', $featured_image_id );
		$new_post_content .= "\n";
		$new_post_content .= '<figure class="wp-block-image">';
		$new_post_content .= sprintf( '<img src="%s" alt="" class="wp-image-%s"/>', $featured_image_url, $featured_image_id );
		$new_post_content .= '</figure>';
		$new_post_content .= "\n";
		$new_post_content .= '<!-- /wp:image -->';
		$new_post_content .= "\n\n";
	}

	if ( ! empty( $existing_content ) && ! $maybe_video ) {
		$new_post_content .= $existing_content . "\n\n";
	}

	if ( ! $maybe_video ) {
		$new_post_content .= '<!-- wp:artgallery/metadata /-->';
		$new_post_content .= "\n\n";
		$new_post_content .= '<!-- wp:artgallery/availability /-->';
	}

	// Remove excessive, leading, and trailing newlines.
	$new_post_content = trim( preg_replace( "/(^\n+|\n+$)/", '', $new_post_content ) );

	$log( '-- Setting content to: ---------------------------------' );
	$log( $new_post_content );
	$log( '--------------------------------------------------------' );

	if ( ! $dry_run ) {
		wp_update_post( [
			'ID'           => $artwork->ID,
			'post_content' => $new_post_content,
		] );
		return true;
	}
	return false;
}
