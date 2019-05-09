import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { ServerSideRender } from '@wordpress/editor';
import { TextControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import { withDispatch, withSelect } from '@wordpress/data';

import {
	ARTWORK_WIDTH,
	ARTWORK_HEIGHT,
	ARTWORK_DEPTH,
	ARTWORK_DATE,
	ARTWORK_POST_TYPE,
} from '../../constants';
import { bemBlock } from '../../utils';

import Icon from './icon';

import './style.scss';

export const name = 'artgallery/metadata';

const block = bemBlock( 'artwork-metadata' );

const EditDimensionsBlock = ( { attributes, isSelected, setAttributes, openSidebar } ) => {
	return isSelected ? (
		<Fragment>
			<h2 className={ block.element( 'title' ) }>
				{ __( 'Edit Artwork Metadata', 'artgallery' ) }
			</h2>
			<TextControl
				className={ block.element( 'date' ) }
				label={ __( 'When was this artwork completed?', 'artgallery' ) }
				value={ attributes.date }
				onChange={ date => setAttributes( { date } ) }
			/>
			<p className={ block.element( 'message' ) }>
				{ __( 'Specify artwork dimensions:', 'artgallery' ) }
			</p>
			<div className={ block.element( 'container' ) }>
				<TextControl
					className={ block.element( 'input' ) }
					label={ __( 'inches width', 'artgallery' ) }
					value={ attributes.width }
					type="number"
					onChange={ width => setAttributes( { width } ) }
				/>
				<span>x</span>
				<TextControl
					className={ block.element( 'input' ) }
					label={ __( 'inches tall', 'artgallery' ) }
					value={ attributes.height }
					type="number"
					onChange={ height => setAttributes( { height } ) }
				/>
				<span>x</span>
				<TextControl
					className={ block.element( 'input' ) }
					label={ __( 'inches deep (optional)', 'artgallery' ) }
					value={ attributes.depth }
					type="number"
					onChange={ depth => setAttributes( { depth } ) }
				/>
			</div>
			<p className={ block.element( 'message' ) }>
				{ __( 'To modify artwork media information, add or remove terms in the Document sidebar.', 'artgallery' ) }
				<button
					className={ block.element( 'button' ) }
					onClick={ openSidebar }
				>
					{ __( 'Open Document Sidebar', 'artgallery' ) }
				</button>
			</p>
		</Fragment>
	) : (
		<ServerSideRender block={ name } attributes={ attributes } />
	);
};

export const settings = {
	title: __( 'Artwork Metadata', 'artgallery' ),

	description: __( 'List the date, size & materials for a given artwork.', 'artgallery' ),

	icon: Icon,

	category: 'artgallery',

	attributes: {
		width: {
			type: 'number',
			source: 'meta',
			meta: ARTWORK_WIDTH,
		},
		height: {
			type: 'number',
			source: 'meta',
			meta: ARTWORK_HEIGHT,
		},
		depth: {
			type: 'number',
			source: 'meta',
			meta: ARTWORK_DEPTH,
		},
		date: {
			type: 'string',
			source: 'meta',
			meta: ARTWORK_DATE,
			default: null,
		},
	},

	edit: compose(
		withSelect( select => ( {
			postId: select( 'core/editor' ).getEditedPostAttribute( 'id' ),
		} ) ),
		withDispatch( dispatch => ( {
			openSidebar: () => dispatch( 'core/edit-post' ).openGeneralSidebar( 'edit-post/document' ),
		} ) ),
	)( EditDimensionsBlock ),

	save() {
		return null;
	},
};

// Limit the post types in which this block is available.
export const postTypes = [ ARTWORK_POST_TYPE ];
