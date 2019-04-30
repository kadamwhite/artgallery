import { __ } from '@wordpress/i18n';
import { ServerSideRender } from '@wordpress/editor';

import Icon from './icon';

export const name = 'artgallery/artwork-grid';

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
