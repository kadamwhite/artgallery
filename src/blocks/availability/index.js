/* eslint-disable no-console */
import { __, sprintf } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { RadioControl } from '@wordpress/components';
import { withDispatch, withSelect } from '@wordpress/data';

import { AVAILABILITY_TAXONOMY } from '../../constants';
import { addPluginNamespace, bemBlock } from '../../utils';

import Icon from './icon';

import './style.scss';
// console.log( styles );

export const name = addPluginNamespace( 'availability' );

const block = bemBlock( 'artwork-availability' );

const AvailabilityOptionsList = ( {
	availability,
	availabilityTerms,
	isSelected,
	setAvailability,
} ) => {
	if ( ! Array.isArray( availabilityTerms ) || ! availabilityTerms.length ) {
		return (
			<Fragment>
				<h2>
					{ __( 'Artwork availability status loading...', 'artgallery' ) }
				</h2>
			</Fragment>
		);
	}

	const termId = Array.isArray( availability ) && availability.length ? availability[0] : null;
	const term = termId && availabilityTerms.find( term => term.id === termId );

	// Try to assign a default term as soon as the block's data loads.
	if ( ! term ) {
		const nfsTerm = availabilityTerms.find( term => term.name.match( /Not For Sale|nfs/i ) );
		if ( nfsTerm ) {
			setAvailability( availabilityTerms[ 0 ].id );
		}
	}

	if ( ! isSelected || ! Array.isArray( availabilityTerms ) ) {
		return (
			<Fragment>
				<h2>
					{ /* Translators: %s is the selected artwork status. */ }
					{ sprintf( __( 'Artwork is %s', 'artgallery' ), term ? term.name : '...' ) }
				</h2>
			</Fragment>
		);
	}

	return (
		<Fragment>
			<h2 className={ block.element( 'title' ) }>
				{ __( 'Manage Artwork Availability', 'artgallery' ) }
			</h2>
			<p className={ block.element( 'explanation' ) }>
				{ __( 'This block controls the messaging indicating whether or not an artwork is available for purchase. Select the appropriate option in the dropdown.', 'artgallery' ) }
			</p>
			<RadioControl
				label={ __( 'Artwork Status', 'artgallery' ) }
				selected={ availability ? +availability[ 0 ] : null }
				options={ availabilityTerms.map( term => ( {
					label: term.name,
					value: +term.id,
				} ) ) }
				onChange={ setAvailability }
			/>
		</Fragment>
	);
};

const selectAvailabilityTerms = select => ( {
	availability: select( 'core/editor' ).getEditedPostAttribute( AVAILABILITY_TAXONOMY ),
	availabilityTerms: select( 'core' ).getEntityRecords( 'taxonomy', AVAILABILITY_TAXONOMY ),
	otherProp: Math.random(),
} );

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
		status: {
			type: 'string',
		},
		message: {
			type: 'string',
			source: 'html',
			selector: `.${ block.element( 'message' ) }`,
			default: 'Contact artist for pricing',
		},
	},

	edit: withDispatch( dispatchAvailabilityChanges )( withSelect( selectAvailabilityTerms )( AvailabilityOptionsList ) ),

	save() {
		return null;
	},
};
