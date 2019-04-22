/**
 * This file defines the configuration for development and dev-server builds.
 */
const { resolve } = require( 'path' );
const { externals, helpers, presets } = require( '@humanmade/webpack-helpers' );
const { choosePort, cleanOnExit, filePath } = helpers;

const pluginPath = ( ...pathParts ) => resolve( __dirname, '..', ...pathParts );

// Clean up manifests on exit.
cleanOnExit( [
	filePath( 'build/asset-manifest.json' ),
] );

const config = {
	externals,
	entry: {
		editor: pluginPath( 'src/index.js' ),
	},
	output: {
		path: pluginPath( 'build' ),
	},
};

// If this is the top-level Webpack file loaded by the Webpack DevServer,
// automatically detect & bind to an open port.
if (
	process.argv[1].indexOf( 'webpack-dev-server' ) !== -1
	&& pluginPath( '.config' ) === __dirname
) {
	const cwdRelativePublicPath = ( path, port ) => `http://localhost:${ port }${ path.replace( process.cwd(), '' ) }/`;
	module.exports = choosePort( 9090 ).then( port => presets.development( {
		...config,
		devServer: {
			port,
		},
		output: {
			...config.output,
			publicPath: cwdRelativePublicPath( config.output.path, port ),
		},
	} ) );
} else {
	module.exports = presets.development( config );
}
