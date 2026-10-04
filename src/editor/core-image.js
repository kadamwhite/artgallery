import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { dispatch, withSelect } from '@wordpress/data';

import { ARTWORK_POST_TYPE } from '../constants';

/**
 * Automatically feature the first image added to an Artwork item.
 */
addFilter(
	'editor.BlockEdit',
	'artgallery/core-image',
	createHigherOrderComponent( BlockEdit => withSelect( ( select, ownProps ) => {
		// Only proceed for image blocks with an assigned ID.
		if ( ownProps.name === 'core/image' && ownProps.attributes.id ) {
			const { getEditedPostAttribute } = select( 'core/editor' );

			if ( getEditedPostAttribute( 'type' ) === ARTWORK_POST_TYPE ) {

				if ( ! getEditedPostAttribute( 'featured_media' ) ) {
					// If this is an image block, if this post is an Artwork item, if there
					// is no existing featured image, AND if the image for this block has
					// been set, then automatically feature that image.
					dispatch( 'core/editor' ).editPost( {
						featured_media: ownProps.attributes.id,
					} );
				}
			}
		}
		return null;
	} )( BlockEdit ), 'autoFeatureFirstSelectedImage' )
);
