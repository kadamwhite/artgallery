import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';

import metadata from './block.json';
import Icon from './icon';

import './style.scss';

const Edit = () => (
	<div { ...useBlockProps() }>
		<ServerSideRender block={ metadata.name } />
	</div>
);

registerBlockType( metadata.name, {
	icon: Icon,
	edit: Edit,
	save: () => null,
} );
