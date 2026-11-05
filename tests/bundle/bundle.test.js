/**
 * Smoke-test the built bundles without WordPress.
 *
 * Each build/** entry is executed in a Node vm with a stubbed `window.wp` that
 * records which packages the bundle touches and which blocks and plugins it
 * registers. This catches the failures a wp-scripts upgrade can introduce
 * (bad transpilation, externals mismatch, block.json drift) at the cost of a
 * build, not a WordPress install. Run `npm run build` first.
 */
import { existsSync, readdirSync, readFileSync } from 'node:fs';
import { join, resolve } from 'node:path';
import { runInNewContext } from 'node:vm';
import { describe, expect, it } from 'vitest';

const BUILD = resolve( __dirname, '../../build' );

const toHandle = ( pkg ) =>
	'wp-' + pkg.replace( /[A-Z]/g, ( c ) => '-' + c.toLowerCase() );

/**
 * Parse the handles out of a wp-scripts *.asset.php file.
 *
 * @param {string} file Path to an .asset.php file.
 * @return {string[]} Declared script handles.
 */
const readAssetDependencies = ( file ) => {
	const php = readFileSync( file, 'utf8' );
	const list = php.match( /'dependencies'\s*=>\s*array\(([^)]*)\)/ );
	return list[ 1 ].match( /'[^']+'/g ).map( ( s ) => s.slice( 1, -1 ) );
};

/**
 * A module whose every property is a callable that returns itself.
 *
 * @param {Object} handlers Real implementations for specific exports.
 * @return {Proxy} Stub module.
 */
const stubModule = ( handlers = {} ) =>
	new Proxy( handlers, {
		get: ( target, prop ) => {
			if ( prop in target ) {
				return target[ prop ];
			}
			const fn = () => fn;
			return fn;
		},
	} );

/**
 * Execute a bundle and return what it did.
 *
 * @param {string} file Path to a built index.js.
 * @return {Object} Packages used, and blocks, plugins and filters registered.
 */
const run = ( file ) => {
	const used = new Set();
	const blocks = [];
	const plugins = [];
	const filters = [];
	const modules = {
		blocks: stubModule( {
			registerBlockType: ( name, settings ) => {
				blocks.push( { name, settings } );
			},
		} ),
		plugins: stubModule( {
			registerPlugin: ( name, settings ) => {
				plugins.push( { name, settings } );
			},
		} ),
		hooks: stubModule( {
			addFilter: ( hook, namespace ) => {
				filters.push( { hook, namespace } );
			},
		} ),
	};
	const wp = new Proxy( modules, {
		get: ( target, pkg ) => {
			used.add( pkg );
			return target[ pkg ] || stubModule();
		},
	} );
	const context = { wp };
	context.window = context;
	// JSX is only called inside render functions, which never run here, so
	// record the global lookup rather than use of jsx()/jsxs().
	Object.defineProperty( context, 'ReactJSXRuntime', {
		get() {
			used.add( 'react-jsx-runtime' );
			return stubModule();
		},
	} );

	runInNewContext( readFileSync( file, 'utf8' ), context, {
		filename: file,
	} );

	return { used, blocks, plugins, filters };
};

/**
 * Handles a bundle used, in the form its .asset.php should declare.
 *
 * @param {Set<string>} used Package names read off `window.wp`.
 * @return {string[]} Sorted script handles.
 */
const usedHandles = ( used ) =>
	[ ...used ]
		.map( ( pkg ) =>
			pkg === 'react-jsx-runtime' ? pkg : toHandle( pkg )
		)
		.sort();

const declaredHandles = ( entry ) =>
	readAssetDependencies( entry.replace( /\.js$/, '.asset.php' ) ).sort();

describe( 'build output', () => {
	it( 'exists', () => {
		expect( existsSync( BUILD ) ).toBe( true );
	} );
} );

describe( 'editor script', () => {
	const entry = join( BUILD, 'editor/index.js' );
	const result = run( entry );

	it( 'declares exactly the packages it uses', () => {
		expect( usedHandles( result.used ) ).toEqual(
			declaredHandles( entry )
		);
	} );

	it( 'registers the featured image warning plugin', () => {
		expect( result.plugins.map( ( p ) => p.name ) ).toEqual( [
			'artgallery-missing-featured-image-warning',
		] );
		expect( result.plugins[ 0 ].settings.render ).toBeTypeOf( 'function' );
	} );

	it( 'hooks the image auto-feature filter', () => {
		expect( result.filters ).toContainEqual( {
			hook: 'editor.BlockEdit',
			namespace: 'artgallery/core-image',
		} );
	} );
} );

describe.each( readdirSync( join( BUILD, 'blocks' ) ) )(
	'block %s',
	( dir ) => {
		const blockDir = join( BUILD, 'blocks', dir );
		const metadata = JSON.parse(
			readFileSync( join( blockDir, 'block.json' ), 'utf8' )
		);
		const entry = join( blockDir, 'index.js' );
		const result = run( entry );

		it( 'declares exactly the packages it uses', () => {
			expect( usedHandles( result.used ) ).toEqual(
				declaredHandles( entry )
			);
		} );

		it( 'registers the block named in block.json', () => {
			expect( result.blocks ).toHaveLength( 1 );
			expect( result.blocks[ 0 ].name ).toBe( metadata.name );
			expect( result.blocks[ 0 ].settings.edit ).toBeTypeOf( 'function' );
			expect( result.blocks[ 0 ].settings.save ).toBeTypeOf( 'function' );
		} );

		it( 'ships every file block.json references', () => {
			const refs = Object.values( metadata )
				.flat()
				.filter(
					( v ) => typeof v === 'string' && v.startsWith( 'file:' )
				)
				.map( ( v ) => v.slice( 5 ) );
			expect( refs.length ).toBeGreaterThan( 0 );
			for ( const ref of refs ) {
				expect( existsSync( join( blockDir, ref ) ) ).toBe( true );
			}
		} );
	}
);
