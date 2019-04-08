/**
 * This file defines the configuration that is used for the production build.
 */
const { externals, helpers, presets } = require( '@humanmade/webpack-helpers' );
const { filePath } = helpers;

/**
 * Theme production build configuration.
 */
module.exports = presets.production( {
	externals,
	entry: {
		artgallery: filePath( 'src/index.js' ),
	},
	output: {
		path: filePath( 'build' ),
	},
} );
