import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';
import ServerSideRender from '@wordpress/server-side-render';
import { TextControl } from '@wordpress/components';
import { useDispatch, useSelect } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';

import {
	ARTWORK_DATE,
	ARTWORK_DEPTH,
	ARTWORK_HEIGHT,
	ARTWORK_POST_TYPE,
	ARTWORK_WIDTH,
	MEDIA_TAXONOMY,
} from '../../constants';
import { bemBlock } from '../../utils';

import metadata from './block.json';
import Icon from './icon';

import './editor.scss';

const block = bemBlock( 'artwork-metadata' );

const EditDimensionsBlock = ( { isSelected, postId, postType } ) => {
	const [ meta = {}, setMeta ] = useEntityProp( 'postType', postType, 'meta', postId );

	const mediaPanel = `taxonomy-panel-${ MEDIA_TAXONOMY }`;
	const isMediaPanelOpened = useSelect(
		select => select( 'core/editor' ).isEditorPanelOpened( mediaPanel ),
		[ mediaPanel ]
	);
	const { openGeneralSidebar } = useDispatch( 'core/edit-post' );
	const { toggleEditorPanelOpened } = useDispatch( 'core/editor' );

	const openSidebar = () => {
		openGeneralSidebar( 'edit-post/document' );

		if ( ! isMediaPanelOpened ) {
			toggleEditorPanelOpened( mediaPanel );
		}
	};

	// Registered number meta rejects empty strings, so store those as 0.
	const setMetaValue = ( key, value ) => setMeta( { ...meta, [ key ]: value } );
	const setMetaNumber = ( key, value ) => setMetaValue( key, Number( value ) );

	const hasAttributeValues = (
		meta[ ARTWORK_WIDTH ] ||
		meta[ ARTWORK_HEIGHT ] ||
		meta[ ARTWORK_DEPTH ] ||
		meta[ ARTWORK_DATE ]
	);
	return isSelected || ! hasAttributeValues ? (
		<Fragment>
			<h2 className={ block.element( 'title' ) }>
				{ __( 'Edit Artwork Metadata', 'artgallery' ) }
			</h2>
			<TextControl
				className={ block.element( 'date' ) }
				label={ __( 'When was this artwork completed?', 'artgallery' ) }
				value={ meta[ ARTWORK_DATE ] ?? '' }
				onChange={ date => setMetaValue( ARTWORK_DATE, date ) }
			/>
			<p className={ block.element( 'message' ) }>
				{ __( 'Specify artwork dimensions:', 'artgallery' ) }
			</p>
			<div className={ block.element( 'container' ) }>
				<TextControl
					className={ block.element( 'input' ) }
					label={ __( 'inches width', 'artgallery' ) }
					value={ meta[ ARTWORK_WIDTH ] ?? '' }
					type="number"
					onChange={ width => setMetaNumber( ARTWORK_WIDTH, width ) }
				/>
				<span>x</span>
				<TextControl
					className={ block.element( 'input' ) }
					label={ __( 'inches tall', 'artgallery' ) }
					value={ meta[ ARTWORK_HEIGHT ] ?? '' }
					type="number"
					onChange={ height => setMetaNumber( ARTWORK_HEIGHT, height ) }
				/>
				<span>x</span>
				<TextControl
					className={ block.element( 'input' ) }
					label={ __( 'inches deep (optional)', 'artgallery' ) }
					value={ meta[ ARTWORK_DEPTH ] ?? '' }
					type="number"
					onChange={ depth => setMetaNumber( ARTWORK_DEPTH, depth ) }
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
		<ServerSideRender
			block={ metadata.name }
			attributes={ {
				// Pass unsaved meta back so the preview reflects pending edits.
				width: Number( meta[ ARTWORK_WIDTH ] ) || 0,
				height: Number( meta[ ARTWORK_HEIGHT ] ) || 0,
				depth: Number( meta[ ARTWORK_DEPTH ] ) || 0,
				date: meta[ ARTWORK_DATE ] || '',
			} }
			urlQueryArgs={ { post_id: postId } }
		/>
	);
};

const MetadataEdit = props => {
	const { context: { postId, postType, queryId } } = props;
	const isEditable = postId && postType === ARTWORK_POST_TYPE && ! Number.isFinite( queryId );

	let content;
	if ( ! postId ) {
		content = (
			<p className={ block.element( 'message' ) }>
				{ __( 'Displays the current artwork\'s date, dimensions and media.', 'artgallery' ) }
			</p>
		);
	} else if ( isEditable ) {
		content = <EditDimensionsBlock { ...props } postId={ postId } postType={ postType } />;
	} else {
		content = (
			<ServerSideRender
				block={ metadata.name }
				attributes={ props.attributes }
				urlQueryArgs={ { post_id: postId } }
			/>
		);
	}

	return <div { ...useBlockProps() }>{ content }</div>;
};

registerBlockType( metadata.name, {
	icon: Icon,
	edit: MetadataEdit,
	save: () => null,
} );
