import { __ } from '@wordpress/i18n';
import { ServerSideRender } from '@wordpress/editor';

import ChildMonitor from '../../components/child-monitor';
import Icon from './icon';

const maybeRecomputeResponsiveContainers = () => {
	if ( window.agUpdateResponsiveContainers ) {
		window.agUpdateResponsiveContainers();
	}
};

export const name = 'artgallery/artwork-grid';

export const options = {
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

	edit: () => {
		// Duplicate the responsive container div so that if the callback fires
		// before the ServerSideRender is done, it is still wrapped in those classes.

		return (
			<ChildMonitor
				onChange={ maybeRecomputeResponsiveContainers  }
				check={ container => container.querySelector( '[data-responsive-container]' ) }
				once
			>
				<ServerSideRender block={ name } />
			</ChildMonitor>
		);
	},

	save() {
		return null;
	},
};
