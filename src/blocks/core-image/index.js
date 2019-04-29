import { compose, createHigherOrderComponent } from '@wordpress/compose';
import { withDispatch, withSelect } from '@wordpress/data';

import {
	ARTWORK_POST_TYPE,
} from '../../constants';

export const name = 'core/image';

export const filters = [
	/**
	 * Automatically feature the first image added to an Artwork item.
	 */
	{
		hook: 'editor.BlockEdit',
		namespace: `artgallery/${ name }`,
		callback: createHigherOrderComponent( BlockEdit => compose(
			withSelect( select => {
				const editor = select( 'core/editor' );
				return {
					isArtworkItem: editor.getEditedPostAttribute( 'type' ) === ARTWORK_POST_TYPE,
					featuredMediaId: editor.getEditedPostAttribute( 'featured_media' ),
				};
			} ),
			withDispatch( dispatch => ( {
				setFeaturedMediaId: id => dispatch( 'core/editor' ).editPost( {
					featured_media: id,
				} ),
			} ) ),
		)( ( {
			isArtworkItem,
			featuredMediaId,
			setFeaturedMediaId,
			...props
		} ) => {
			// If this is an image block, if this post is an Artwork item, if there
			// is no existing featured image, AND if the image for this block has
			// been set, then automatically feature that image.
			if ( props.name === 'core/image' && isArtworkItem && ! featuredMediaId && props.attributes.id ) {
				setFeaturedMediaId( props.attributes.id );
			}
			// Return the BlockEdit component for use as normal.
			return (
				<BlockEdit { ...props } />
			);
		} ), 'autoFeatureFirstSelectedImage' ),
	},
];
