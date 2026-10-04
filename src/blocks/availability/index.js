import { __, sprintf } from '@wordpress/i18n';
import { createBlock, registerBlockType } from '@wordpress/blocks';
import { Fragment } from '@wordpress/element';
import { RadioControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { RichText, useBlockProps } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';

import {
	ARTWORK_POST_TYPE,
	AVAILABILITY_TAXONOMY,
	AVAILABILITY_TAXONOMY_BASE,
} from '../../constants';
import { bemBlock } from '../../utils';

import metadata from './block.json';
import Icon from './icon';

import './editor.scss';

const block = bemBlock( 'artwork-availability' );

const isAvailable = term => (
	term && term.slug === 'available' ? true : false
);

const AvailabilityOptionsList = ( {
	attributes,
	insertBlocksAfter,
	isSelected,
	postId,
	postType,
	setAttributes,
} ) => {
	const [ assignedTerms, setAssignedTerms ] = useEntityProp(
		'postType',
		postType,
		AVAILABILITY_TAXONOMY_BASE,
		postId
	);
	const availabilityTerms = useSelect(
		select => select( 'core' ).getEntityRecords( 'taxonomy', AVAILABILITY_TAXONOMY ),
		[]
	);

	const availability = Array.isArray( assignedTerms ) && assignedTerms.length ?
		+assignedTerms[0] :
		0;
	const setAvailability = termId => setAssignedTerms( [ +termId ] );

	if ( ! availabilityTerms || ! availabilityTerms.length ) {
		return (
			<p className={ block.element( 'explanation' ) }>
				{ __( 'Artwork availability status loading...', 'artgallery' ) }
			</p>
		);
	}

	// Retrieve the assigned term, if present.
	const availabilityTerm = availabilityTerms.find( term => ( +term.id === +availability ) );

	if ( ! isSelected && ! availabilityTerm ) {
		return (
			<p className={ block.element( 'explanation' ) }>
				{ __( 'Click to configure whether the original for this artwork is available for purchase.', 'artgallery' ) }
			</p>
		);
	}

	if ( ! isSelected ) {
		const statusMessage = sprintf(
			/* Translators: %s is the selected artwork status. */
			__( 'Artwork is marked %s.', 'artgallery' ),
			availabilityTerm.name
		);

		return isAvailable( availabilityTerm ) ? (
			<Fragment>
				<p className={ block.element( 'explanation' ) }>
					{ statusMessage }
					{ ' ' }
					{ __( 'This message will be displayed on the frontend:', 'artgallery' ) }
				</p>
				<ServerSideRender block={ metadata.name } urlQueryArgs={ { post_id: postId } } attributes={ {
					// Status is not a registered attribute, but we must pass it back when
					// rendering via ServerSideRender so the backend can be aware of
					// pending term assignment updates and display the correct preview.
					status: availabilityTerm.slug,
					...attributes,
				} } />
			</Fragment>
		) : (
			<p className={ block.element( 'explanation' ) }>
				{ statusMessage }
				{ ' ' }
				{ __( 'No message or indication of artwork availability will be displayed.', 'artgallery' ) }
			</p>
		);
	}

	return (
		<Fragment>
			<h2 className={ block.element( 'title' ) }>
				{ __( 'Manage Artwork Availability', 'artgallery' ) }
			</h2>
			<p className={ block.element( 'message' ) }>
				{ __( 'This block controls the messaging indicating whether or not the artwork is available for purchase.', 'artgallery' ) }
				{ ' ' }
				{ __( '(Defaults to "not for sale" on publish if no option is selected.)', 'artgallery' ) }
			</p>
			<RadioControl
				className={ block.element( 'options' ) }
				label={ __( 'Artwork Status', 'artgallery' ) }
				selected={ `${ availability }` }
				options={ availabilityTerms.map( term => ( {
					label: term.name,
					value: `${ term.id }`,
				} ) ) }
				onChange={ setAvailability }
			/>
			{ isAvailable( availabilityTerm ) ? (
				<Fragment>
					<label className={ `${ block.element( 'help-text' ) } components-base-control` }>
						{ __( 'Enter a sales message or link to display at the bottom of the artwork page.', 'artgallery' ) }
					</label>
					<RichText
						tagName="p"
						className={ block.element( 'custom-message' ) }
						value={ attributes.message }
						onChange={ message => setAttributes( { message } ) }
						placeholder={ __( 'Enter text...', 'custom-block' ) }
					/>
				</Fragment>
			) : null }
			<p className={ block.element( 'message' ) }>
				{ __( 'Insert a paragraph after this block to add links to reproductions or derivative products.', 'artgallery' ) }
			</p>
			<button
				className="components-button is-button is-default"
				onClick={ () => insertBlocksAfter( createBlock( 'core/paragraph' ) ) }
			>
				{ __( 'Add paragraph', 'artgallery' ) }
			</button>
		</Fragment>
	);
};

const AvailabilityEdit = props => {
	const { context: { postId, postType, queryId } } = props;
	const isEditable = postId && postType === ARTWORK_POST_TYPE && ! Number.isFinite( queryId );

	let content;
	if ( ! postId ) {
		content = (
			<p className={ block.element( 'explanation' ) }>
				{ __( 'Displays the current artwork\'s availability message.', 'artgallery' ) }
			</p>
		);
	} else if ( isEditable ) {
		content = <AvailabilityOptionsList { ...props } postId={ postId } postType={ postType } />;
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
	edit: AvailabilityEdit,
	save: () => null,
} );
