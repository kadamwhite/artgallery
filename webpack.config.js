/**
 * Extends the @wordpress/scripts webpack configuration.
 *
 * Two entries, editor and frontend, emitted as build/{name}.js and
 * build/{name}.css. Block style.scss files stay in the editor bundle rather
 * than being split into style-*.css, which preserves the previous output.
 * A production-asset-manifest.json is written for humanmade/asset-loader.
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
	entry: {
		editor: path.resolve( __dirname, 'src/editor.js' ),
		frontend: path.resolve( __dirname, 'src/frontend.js' ),
	},
	optimization: {
		...defaultConfig.optimization,
		splitChunks: {
			cacheGroups: {
				default: false,
			},
		},
	},
	plugins: [
		...defaultConfig.plugins,
		new AssetManifestPlugin(),
	],
};
