<?php
/**
 * Define migrations and transformations to bring artwork item content & metadata up to date.
 *
 * Used by WP-CLI commands and DB upgrade routines.
 */
namespace ArtGallery\Migrations;

use ArtGallery\Image_Sizes;
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

	if ( empty( $dimensions ) ) {
		return false;
	}

	preg_match(
		'/([0-9]+(?:\.[0-9]*)?)" *x *([0-9]+(?:\.[0-9]*)?)"/',
		$dimensions[0]->name,
		$matches
	);

	if ( empty( $matches ) || ! ( isset( $matches[1] ) && isset( $matches[2] ) ) ) {
		return false;
	}

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

/**
 * Using a regex with a single capture group, apply the group to the provided
 * string and return the matched group, if found, or null.
 *
 * @param string $pattern A regex capture group string.
 * @param string $string  The string content to match with the $pattern.
 * @return string|null The captured group, or null if no match.
 */
function match_group( string $pattern, string $string ) : ?string {
	preg_match( $pattern, $string, $match );
	if ( empty( $match ) ) {
		return null;
	}
	return $match[1];
}

/**
 * Given a string of post content, identify and parse all image tags and return
 * an array of the tags' derived information.
 *
 * @param string $content A post_content string.
 * @return array An array of matched image tags.
 */
function locate_image_tags( string $content ) : array {
	preg_match_all( '/<img[^>]+>/', $content, $matches );

	// If the match array or its sub-array is empty, we have no valid images.
	if ( empty( $matches ) || empty( $matches[0] ) ) {
		return [];
	}
	$image_tags = $matches[0];

	return array_filter( array_map( function( $img_tag ) {
		$id  = (int) match_group( '/wp-image-([0-9]+)/', $img_tag );
		$src = match_group( '/src="([^"]+)"/', $img_tag );

		if ( ! $id || ! $src ) {
			// We need an src and an id to match the image.
			return null;
		}

		return [
			'tag'        => $img_tag,
			'src'        => $src,
			'id'         => $id,
			'width'      => (int) match_group( '/-([0-9]+)x[0-9]+\./', $src ),
			'height'     => (int) match_group( '/-[0-9]+x([0-9]+)\./', $src ),
			'tag_width'  => (int) match_group( '/width="([^"]+)"/', $img_tag ),
			'tag_height' => (int) match_group( '/height="([^"]+)"/', $img_tag ),
		];
	}, $image_tags ) );
}

/**
 * Check a post for defunct image sizes, then replace the image markup with a
 * supported image size.
 *
 * @param WP_Post  $artwork   Artwork post object.
 * @param bool     [$dry_run] Whether this is a dry run of the action.
 * @param callable [$log]     A function to call to log changes.
 * @return bool False if failure or noop, True if successfully updated.
 */
function update_image_sizes( WP_Post $post, bool $dry_run = true, callable $log = null ) {
	$content = trim( $post->post_content );

	$images = locate_image_tags( $content );
	if ( empty( $images ) ) {
		return false;
	}

	$log( "\nProcessing $post->ID, $post->post_title" );
	$log( '--------------------------------------------------------' );

	$updated = false;
	$updated_content = $content;

	foreach ( $images as $image ) {
		$id     = $image['id'];
		$src    = $image['src'];

		$matched_size = Image_Sizes\get_next_largest_image_size( [ $image['width'], $image['height'] ] );
		$new_size = wp_get_attachment_image_src( $id, $matched_size );
		$new_src = $new_size[0];

		if ( empty( $new_src ) || $src === $new_src ) {
			continue;
		}

		$new_tag = str_replace( $src, $new_src, $image['tag'] );
		if ( $image['tag_width'] && $new_size[1] ) {
			$new_tag = str_replace( "width=\"{$image['tag_width']}\"", "width=\"{$new_size[1]}\"", $new_tag );
		}
		if ( $image['tag_height'] && $new_size[2] ) {
			$new_tag = str_replace( "height=\"{$image['tag_height']}\"", "height=\"{$new_size[2]}\"", $new_tag );
		}

		$log( '-- Updating tag: ---------------------------------------' );
		$log( $image['tag'] );
		$log( $new_tag );
		$log( '--------------------------------------------------------' );

		$updated_content = str_replace( $image['tag'], $new_tag, $updated_content );
	}

	if ( ! $dry_run && $updated_content !== $content ) {
		wp_update_post( [
			'ID'           => $post->ID,
			'post_content' => $updated_content,
		] );
		$updated = true;
	} else {
		$log( '- No change' );
	}

	return $updated;
}
