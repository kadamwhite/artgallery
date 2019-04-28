import { __, sprintf } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { RadioControl } from '@wordpress/components';
import { RichText } from '@wordpress/editor';
import { withDispatch, withSelect } from '@wordpress/data';

import { AVAILABILITY_TAXONOMY } from '../../constants';
import { namespacedBlock, bemBlock } from '../../utils';

import Icon from './icon';

import './style.scss';

export const name = namespacedBlock( 'availability' );

const block = bemBlock( 'artwork-availability' );

const AvailabilityOptionsList = ( {
	attributes,
	availability,
	availabilityTerms,
	isSelected,
	setAttributes,
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
	const term = termId && availabilityTerms.find( term => ( +term.id === +termId ) );

	// Try to assign a default term as soon as the block's data loads.
	if ( ! term ) {
		const nfsTerm = availabilityTerms.find( term => ( term.slug === 'nfs' ) );
		if ( nfsTerm ) {
			setAvailability( nfsTerm.id );
			setAttributes( {
				status: nfsTerm.name,
			} );
		}
	}

	if ( ! isSelected ) {
		return (
			<Fragment>
				<h2 className={ block.element( 'title' ) }>
					{ /* Translators: %s is the selected artwork status. */ }
					{ sprintf( __( 'Artwork is %s', 'artgallery' ), term ? term.name : '...' ) }
				</h2>
				{ attributes.status === 'Available' ? (
					<Fragment>
						<p className={ block.element( 'explanation' ) }>
							{ __( 'This message will be displayed on the frontend:', 'artgallery' ) }
						</p>
						<p>{ attributes.message }</p>
					</Fragment>
				) : (
					<p className={ block.element( 'explanation' ) }>
						{ __( '(No message or indication will be displayed)', 'artgallery' ) }
					</p>
				) }
			</Fragment>
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
				selected={ availability ? +availability[ 0 ] : null }
				options={ availabilityTerms.map( term => ( {
					label: term.name,
					value: +term.id,
				} ) ) }
				onChange={ termId => {
					const term = availabilityTerms.find( term => +term.id === +termId );
					setAttributes( {
						status: term.name,
					} );
					setAvailability( termId );
				} }
			/>
			{ term === availabilityTerms.find( term => term.name.match( /Available/i ) ) ? (
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
			default: 'Contact artist for pricing.',
		},
	},

	edit: withDispatch( dispatchAvailabilityChanges )( withSelect( selectAvailabilityTerms )( AvailabilityOptionsList ) ),

	save() {
		return null;
	},
};
