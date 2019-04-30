import { createHigherOrderComponent } from '@wordpress/compose';
import { dispatch, select, subscribe, withSelect } from '@wordpress/data';

import {
	ARTWORK_POST_TYPE,
} from '../../constants';

export const name = 'core/image';

// /**
//  * Listen for store updates, and detect artwork items which have an image block
//  * but lack an assigned featured image. For any matching block, automatically
//  * set the populated image block's image as the featured media.
//  */
// subscribe( () => {
// 	const { getEditedPostAttribute, getBlocks } = select( 'core/editor' );
// 	if ( getEditedPostAttribute( 'type' ) !== ARTWORK_POST_TYPE ) {
// 		return;
// 	}

// 	if ( getEditedPostAttribute( 'featured_media' ) ) {
// 		return;
// 	}

// 	const imageBlock = getBlocks().find( block => block.name === name );
// 	if ( ! imageBlock || ! imageBlock.attributes.id ) {
// 		return;
// 	}

// 	// If this is an image block, if this post is an Artwork item, if there
// 	// is no existing featured image, AND if the image for this block has
// 	// been set, then automatically feature that image.
// 	dispatch( 'core/editor' ).editPost( {
// 		featured_media: imageBlock.attributes.id,
// 	} );
// } );

export const filters = [
	/**
	 * Automatically feature the first image added to an Artwork item.
	 */
	{
		hook: 'editor.BlockEdit',
		namespace: `artgallery/${ name }`,
		callback: createHigherOrderComponent( BlockEdit => withSelect( ( select, ownProps ) => {
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
		} )( BlockEdit ), 'autoFeatureFirstSelectedImage' ),
	},
];
