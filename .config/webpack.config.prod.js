/**
 * This file defines the configuration that is used for the production build.
 */
const { resolve } = require( 'path' );
const { helpers, externals, plugins, presets } = require( '@humanmade/webpack-helpers' );
const { filePath } = helpers;

const pluginPath = ( ...pathParts ) => resolve( __dirname, '..', ...pathParts );

/**
 * Theme production build configuration.
 */
const config = {
	externals,
	entry: {
		editor: pluginPath( 'src/editor.js' ),
		frontend: pluginPath( 'src/frontend.js' ),
	},
	output: {
		path: pluginPath( 'build' ),
	},
	plugins: [
		plugins.manifest( {
			fileName: 'production-asset-manifest.json',
		} ),
	],
};

if ( filePath( '.config' ) === __dirname ) {
	// Prod-mode static file build is being run from within this project.
	module.exports = presets.production( config );
} else {
	// This configuration is being injested by a parent project's build process.
	module.exports = config;
}
