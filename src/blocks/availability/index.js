import { __, sprintf } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { RadioControl } from '@wordpress/components';
import { ServerSideRender, RichText } from '@wordpress/editor';
import { withDispatch, withSelect } from '@wordpress/data';

import { AVAILABILITY_TAXONOMY, ARTWORK_POST_TYPE } from '../../constants';
import { bemBlock } from '../../utils';

import Icon from './icon';

import './style.scss';

export const name = 'artgallery/availability';

const block = bemBlock( 'artwork-availability' );

const AvailabilityOptionsList = ( {
	attributes,
	availability,
	availabilityTerms,
	isSelected,
	setAttributes,
	setAvailability,
} ) => {
	// Try to assign a default term as soon as the block's data loads.
	if ( availabilityTerms && ! availability ) {
		const nfsTerm = availabilityTerms.find( availability => ( availability.slug === 'nfs' ) );
		if ( nfsTerm ) {
			setAvailability( nfsTerm.id );
		}
	}

	if ( ! availabilityTerms || ! availability ) {
		return (
			<h2>
				{ __( 'Artwork availability status loading...', 'artgallery' ) }
			</h2>
		);
	}

	/* Translators: %s is the selected artwork status. */
	const message = sprintf( __( 'Artwork is %s.', 'artgallery' ), availability ? availability.name : '...' );

	if ( ! isSelected ) {
		return availability.slug === 'available' ? (
			<Fragment>
				<p className={ block.element( 'explanation' ) }>
					{ message }
					{ ' ' }
					{ __( 'This message will be displayed on the frontend:', 'artgallery' ) }
				</p>
				<ServerSideRender block={ name } attributes={ {
					// Status is not a registered attribute, but we must pass it back when
					// rendering via ServerSideRender so the backend can be aware of
					// pending term assignment updates and display the correct preview.
					status: availability.slug,
					...attributes,
				} } />
			</Fragment>
		) : (
			<p className={ block.element( 'explanation' ) }>
				(
				{ message }
				{ ' ' }
				{ __( 'No message or indication of artwork availability will be displayed.', 'artgallery' ) }
				)
			</p>
		);
	}

	return (
		<Fragment>
			<h2 className={ block.element( 'title' ) }>
				{ __( 'Manage Artwork Availability', 'artgallery' ) }
			</h2>
			<p className={ block.element( 'explanation' ) }>
				{ __( 'This block controls the messaging indicating whether or not the artwork is available for purchase.', 'artgallery' ) }
			</p>
			<RadioControl
				className={ block.element( 'options' ) }
				label={ __( 'Artwork Status', 'artgallery' ) }
				selected={ availability ? +availability.id : null }
				options={ availabilityTerms.map( term => ( {
					label: term.name,
					value: +term.id,
				} ) ) }
				onChange={ termId => setAvailability( termId ) }
			/>
			{ availability === availabilityTerms.find( term => term.name.match( /Available/i ) ) ? (
				<Fragment>
					<label className={ `${ block.element( 'help-text' ) } components-base-control` }>
						{ __( 'Enter a sales message or link to display at the bottom of the artwork page.', 'artgallery' ) }
					</label>
					<RichText
						tagName="p"
						className={ block.element( 'message' ) }
						value={ attributes.message }
						onChange={ message => setAttributes( { message } ) }
						placeholder={ __( 'Enter text...', 'custom-block' ) }
						keepPlaceholderOnFocus={ true }
					/>
				</Fragment>
			) : null }
		</Fragment>
	);
};

const selectAvailabilityTerms = select => {
	const availabilityTerms = select( 'core' ).getEntityRecords( 'taxonomy', AVAILABILITY_TAXONOMY );
	const assignedTerms = select( 'core/editor' ).getEditedPostAttribute( AVAILABILITY_TAXONOMY );

	const assignedTermId = Array.isArray( assignedTerms ) && assignedTerms.length ?
		assignedTerms[0] :
		null;
	const assignedTerm = assignedTermId && Array.isArray( availabilityTerms ) ?
		availabilityTerms.find( term => ( +term.id === +assignedTermId ) ) :
		null;

	return {
		availability: assignedTerm,
		availabilityTerms: availabilityTerms,
	};
};

const dispatchAvailabilityChanges = dispatch => ( {
	setAvailability: termId => dispatch( 'core/editor' ).editPost( {
		[ AVAILABILITY_TAXONOMY ]: [ termId ],
	} ),
} );

export const options = {
	title: __( 'Artwork Availability' ),

	description: __( 'Mark an artwork as sold, not for sale, or available (with contact link).' ),

	icon: Icon,

	category: 'artgallery',

	attributes: {
		message: {
			type: 'string',
			default: 'Contact artist for pricing.',
		},
	},

	edit: withDispatch( dispatchAvailabilityChanges )( withSelect( selectAvailabilityTerms )( AvailabilityOptionsList ) ),

	save() {
		return null;
	},
};

// Limit the post types in which this block is available.
export const postTypes = [ ARTWORK_POST_TYPE ];
