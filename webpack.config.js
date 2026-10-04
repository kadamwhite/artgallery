/**
 * Extends the @wordpress/scripts webpack configuration.
 *
 * Blocks are built from their block.json files by the default config. This
 * adds one extra entry, editor, for plugin-level editor code, and writes a
 * production-asset-manifest.json for humanmade/asset-loader to find it.
 */
const path = require( 'path' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

class AssetManifestPlugin {
	apply( compiler ) {
		compiler.hooks.thisCompilation.tap( 'AssetManifestPlugin', ( compilation ) => {
			compilation.hooks.processAssets.tap(
				{
					name: 'AssetManifestPlugin',
					stage: compiler.webpack.Compilation.PROCESS_ASSETS_STAGE_SUMMARIZE,
				},
				() => {
					const manifest = {};
					for ( const file of Object.keys( compilation.assets ) ) {
						if ( /\.(js|css)$/.test( file ) ) {
							manifest[ file ] = file;
						}
					}
					compilation.emitAsset(
						'production-asset-manifest.json',
						new compiler.webpack.sources.RawSource( JSON.stringify( manifest, null, 2 ) )
					);
				}
			);
		} );
	}
}

module.exports = {
	...defaultConfig,
	entry: () => ( {
		...defaultConfig.entry(),
		editor: path.resolve( __dirname, 'src/editor.js' ),
	} ),
	plugins: [
		...defaultConfig.plugins,
		new AssetManifestPlugin(),
	],
};
