import { __, sprintf } from '@wordpress/i18n';
import { createBlock } from '@wordpress/blocks';
import { Fragment } from '@wordpress/element';
import { RadioControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { RichText } from '@wordpress/block-editor';
import { withDispatch, withSelect } from '@wordpress/data';

import {
	AVAILABILITY_TAXONOMY,
	AVAILABILITY_TAXONOMY_BASE,
	ARTWORK_POST_TYPE,
} from '../../constants';
import { bemBlock } from '../../utils';

import Icon from './icon';

import './style.scss';

export const name = 'artgallery/availability';

const block = bemBlock( 'artwork-availability' );

const isAvailable = term => (
	term && term.slug === 'available' ? true : false
);

const AvailabilityOptionsList = ( {
	attributes,
	availability,
	availabilityTerms,
	insertBlocksAfter,
	isSelected,
	setAttributes,
	setAvailability,
} ) => {
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
				<ServerSideRender block={ name } attributes={ {
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
						keepPlaceholderOnFocus={ true }
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

const selectAvailabilityTerms = select => {
	const availabilityTerms = select( 'core' ).getEntityRecords( 'taxonomy', AVAILABILITY_TAXONOMY );
	const assignedTerms = select( 'core/editor' ).getEditedPostAttribute( AVAILABILITY_TAXONOMY_BASE );

	const assignedTermId = Array.isArray( assignedTerms ) && assignedTerms.length ?
		assignedTerms[0] :
		null;

	return {
		availability: +assignedTermId,
		availabilityTerms: availabilityTerms,
	};
};

const dispatchAvailabilityChanges = ( dispatch, ownProps, { select } ) => ( {
	setAvailability( termId ) {
		dispatch( 'core/editor' ).editPost( {
			[ AVAILABILITY_TAXONOMY_BASE ]: [ +termId ],
		} );
	},
} );

export const settings = {
	title: __( 'Artwork Availability' ),

	description: __( 'Mark an artwork as sold, not for sale, or available (with contact link).' ),

	icon: Icon,

	category: 'artgallery',

	attributes: {
		message: {
			type: 'string',
			default: __( 'Contact artist for pricing.', 'artgallery' ),
		},
	},

	edit: withDispatch( dispatchAvailabilityChanges )( withSelect( selectAvailabilityTerms )( AvailabilityOptionsList ) ),

	save() {
		return null;
	},
};

// Limit the post types in which this block is available.
export const postTypes = [ ARTWORK_POST_TYPE ];
