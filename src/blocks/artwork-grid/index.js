import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

import Icon from './icon';

export const name = 'artgallery/artwork-grid';

export const settings = {
	title: __( 'Artwork Grid', 'artgallery' ),

	description: __( 'Display a grid of recent artwork.', 'artgallery' ),

	icon: Icon,

	category: 'artgallery',

	supports: {
		align: [ 'full', 'wide' ],
	},

	attributes: {
		message: {
			type: 'string',
			default: 'Contact artist for pricing.',
		},
	},

	edit: () => <ServerSideRender block={ name } />,

	save() {
		return null;
	},
};
