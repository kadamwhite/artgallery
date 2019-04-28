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
