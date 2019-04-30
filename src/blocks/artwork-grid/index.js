import { __ } from '@wordpress/i18n';
// import { Fragment } from '@wordpress/element';
// import { RadioControl } from '@wordpress/components';
import { ServerSideRender } from '@wordpress/editor';
// import { withDispatch, withSelect } from '@wordpress/data';

// import { AVAILABILITY_TAXONOMY } from '../../constants';
// import { bemBlock } from '../../utils';

import Icon from './icon';

export const name = 'artgallery/artwork-grid';

// const block = bemBlock( 'artwork-grid' );

export const options = {
	title: __( 'Artwork Grid', 'artgallery' ),

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
		<ServerSideRender block={ name } />
	),

	save() {
		return null;
	},
};
