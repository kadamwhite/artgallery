/**
 * Define a Block Editor plugin to display a warning in the publish box
 * if a featured image is not set for an artwork item.
 */
import { Fragment } from '@wordpress/element';
import {
	PluginPostStatusInfo,
	PluginPrePublishPanel,
} from '@wordpress/edit-post';
import { __ } from '@wordpress/i18n';
import { Button, Icon } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import { withSelect, withDispatch } from '@wordpress/data';
import { MediaUpload, MediaUploadCheck } from '@wordpress/editor';

import { ARTWORK_POST_TYPE } from '../../constants';

export const name = 'artgallery-featured-item-alert';

const MissingImageWarning = () => (
	<p style={ {
		display: 'flex',
		alignItems: 'center',
	} }>
		<Icon icon="warning" />&nbsp;&nbsp;{ __( 'No Featured Image has been set!', 'artgallery' ) }
	</p>
);

const FeaturedImageWarning = ( {
	isArtwork,
	featuredImageId,
	featuredImageLabel,
	setFeaturedImageLabel,
	onUpdateImage,
} ) => {
	if ( ! isArtwork || featuredImageId ) {
		return null;
	}

	// Borrow some values from Gutenberg itself.
	// Used when labels from post type were not yet loaded or when they are not present.
	const DEFAULT_FEATURE_IMAGE_LABEL = __( 'Featured Image' );
	const DEFAULT_SET_FEATURE_IMAGE_LABEL = __( 'Set Featured Image' );
	const ALLOWED_MEDIA_TYPES = [ 'image' ];

	return (
		<Fragment>
			<PluginPostStatusInfo>
				<MissingImageWarning />
			</PluginPostStatusInfo>
			<PluginPrePublishPanel>
				<MissingImageWarning />
				<p>
					{ __( 'Please select a featured image below before publishing.', 'artgallery' ) }
				</p>
				<MediaUploadCheck fallback={ (
					<p>{ __( 'To edit the featured image, you need permission to upload media.' ) }</p>
				) }>
					<MediaUpload
						title={ featuredImageLabel || DEFAULT_FEATURE_IMAGE_LABEL }
						onSelect={ onUpdateImage }
						allowedTypes={ ALLOWED_MEDIA_TYPES }
						modalClass="editor-post-featured-image__media-modal"
						render={ ( { open } ) => (
							<Button
								className="editor-post-featured-image__toggle"
								onClick={ open }
							>
								{ setFeaturedImageLabel || DEFAULT_SET_FEATURE_IMAGE_LABEL }
							</Button>
						) }
						value={ null }
					/>
				</MediaUploadCheck>
			</PluginPrePublishPanel>
		</Fragment>
	)
};

export const options = {
	icon: 'warning',

	render: compose(
		withSelect( select => {
			const { getPostType } = select( 'core' );
			const { getEditedPostAttribute } = select( 'core/editor' );
			const postTypeSlug = getEditedPostAttribute( 'type' );
			const postType = getPostType( postTypeSlug );
			const labels = ( postType && postType.labels ) || {};

			return {
				isArtwork: postTypeSlug === ARTWORK_POST_TYPE,
				featuredImageId: getEditedPostAttribute( 'featured_media' ),
				featuredImageLabel: labels.featured_image,
				setFeaturedImageLabel: labels.set_featured_image,
			};
		} ),
		withDispatch( dispatch => ( {
			onUpdateImage( image ) {
				dispatch( 'core/editor' ).editPost( { featured_media: image.id } );
			},
		} ) ),
	)( FeaturedImageWarning ),
};
