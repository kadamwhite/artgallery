import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';
import ServerSideRender from '@wordpress/server-side-render';
import { TextControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import { withDispatch, withSelect } from '@wordpress/data';

import {
	MEDIA_TAXONOMY,
} from '../../constants';
import { bemBlock } from '../../utils';

import metadata from './block.json';
import Icon from './icon';

import './editor.scss';

const block = bemBlock( 'artwork-metadata' );

const EditDimensionsBlock = ( { attributes, isSelected, setAttributes, openSidebar } ) => {
	const hasAttributeValues = (
		attributes.width || attributes.height || attributes.depth || attributes.date
	);
	return isSelected || ! hasAttributeValues ? (
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
					className={ `components-button is-button is-default ${ block.element( 'button' ) }` }
					onClick={ openSidebar }
				>
					{ __( 'Open Document Sidebar', 'artgallery' ) }
				</button>
			</p>
		</Fragment>
	) : (
		<ServerSideRender block={ metadata.name } attributes={ attributes } />
	);
};

const Edit = compose(
	withSelect( select => ( {
		postId: select( 'core/editor' ).getEditedPostAttribute( 'id' ),
	} ) ),
	withDispatch( ( dispatch, ownProps, { select } ) => ( {
		openSidebar: () => {
			dispatch( 'core/edit-post' ).openGeneralSidebar( 'edit-post/document' );

			const mediaPanel = `taxonomy-panel-${ MEDIA_TAXONOMY }`;
			if ( ! select( 'core/editor' ).isEditorPanelOpened( mediaPanel ) ) {
				dispatch( 'core/editor' ).toggleEditorPanelOpened( mediaPanel );
			}
		},
	} ) ),
)( EditDimensionsBlock );

const MetadataEdit = props => (
	<div { ...useBlockProps() }>
		<Edit { ...props } />
	</div>
);

registerBlockType( metadata.name, {
	icon: Icon,
	edit: MetadataEdit,
	save: () => null,
} );
