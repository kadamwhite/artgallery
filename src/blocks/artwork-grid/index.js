import { __ } from '@wordpress/i18n';
// import { Fragment } from '@wordpress/element';
// import { RadioControl } from '@wordpress/components';
// import { ServerSideRender, RichText } from '@wordpress/editor';
// import { withDispatch, withSelect } from '@wordpress/data';

// import { AVAILABILITY_TAXONOMY } from '../../constants';
// import { bemBlock } from '../../utils';

import Icon from './icon';

import './style.scss';

export const name = 'artgallery/artwork-grid';

// const block = bemBlock( 'artwork-grid' );

export const options = {
	title: __( 'Artwork Availability', 'artgallery' ),

	description: __( 'Display a grid of recent artwork.', 'artgallery' ),

	icon: Icon,

	category: 'artgallery',

	attributes: {
		message: {
			type: 'string',
			default: 'Contact artist for pricing.',
		},
	},

	edit: () => (
		<p>[ Artwork Grid ]</p>
	),

	save() {
		return null;
	},
};
