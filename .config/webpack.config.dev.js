/**
 * This file defines the configuration for development and dev-server builds.
 */
const { externals, helpers, presets } = require( '@humanmade/webpack-helpers' );
const { choosePort, cleanOnExit, filePath } = helpers;

// Clean up manifests on exit.
cleanOnExit( [
	filePath( 'build/asset-manifest.json' ),
] );

module.exports = choosePort( 9091 ).then( port => presets.development( {
	devServer: {
		port,
	},
	externals: {
		...externals,
		jquery: 'jQuery',
	},
	entry: {
		editor: filePath( 'src/index.js' ),
	},
	output: {
		path: filePath( 'build' ),
		publicPath: `http://localhost:${ port }/build/`,
	},
} ) );
