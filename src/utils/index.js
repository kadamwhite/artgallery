const PLUGIN_NAMESPACE = 'artgallery';

export const namespacedBlock = blockName => `${ PLUGIN_NAMESPACE }/${ blockName }`;

export const bemBlock = blockName => ( {
	element( elementName ) {
		return `${ blockName }__${ elementName }`;
	},
	modifier( elementName, modifier ) {
		if ( elementName && modifier === undefined ) {
			return `${ blockName }--${ elementName }`;
		}
		return `${ blockName }__${ elementName }--${ modifier }`;
	},
	toString() {
		return blockName;
	},
} );
