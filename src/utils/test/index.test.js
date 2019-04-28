import {
	namespacedBlock,
	bemBlock,
} from '../';

describe( 'namespacedBlock', () => {
	it( 'returns a string', () => {
		expect( typeof namespacedBlock( 'some-block' ) ).toBe( 'string' );
	} );

	it( 'properly namespaces a block name string', () => {
		expect( namespacedBlock( 'some-block' ) ).toBe( 'artgallery/some-block' );
	} );
} );

describe( 'bemBlock', () => {
	it( 'returns an object', () => {
		expect( bemBlock( 'block-name' ) ).toBeInstanceOf( Object );
	} );

	it( 'provides a toString() method', () => {
		expect( `${ bemBlock( 'block-name' ) }` ).toBe( 'block-name' );
	} );

	it( 'provides a .element() method', () => {
		const block = bemBlock( 'block-name' );
		expect( block.element ).toBeDefined();
		expect( block.element ).toBeInstanceOf( Function );
		expect( block.element( 'element-name' ) ).toBe( 'block-name__element-name' );
	} );

	it( 'provides a .modifier() method', () => {
		const block = bemBlock( 'block-name' );
		expect( block.modifier ).toBeDefined();
		expect( block.modifier ).toBeInstanceOf( Function );
		expect( block.modifier( 'modifier' ) ).toBe( 'block-name--modifier' );
		expect( block.modifier( 'element-name', 'modifier' ) ).toBe( 'block-name__element-name--modifier' );
	} );
} );
