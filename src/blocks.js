/**
 * Dynamically locate, load & register all Gutenberg blocks.
 */
import {
	autoloadBlocks,
	registerBlock,
	unregisterBlock,
} from 'block-editor-hmr';

const currentScreen = window.ARTGALLERY_CURRENT_SCREEN;
const currentPostType = currentScreen && currentScreen.post_type;

/**
 * Wrap a callback in a function which will skip this module callback if it
 * exposes a postTypes array which does not include the current post type.
 *
 * @param {Function} callback The callback to run for matching modules.
 * @returns {Function} A function which will run the provided callback for matching modules.
 */
const runIfSupportedPostType = callback => ( { postTypes, ...module } ) => {
	// If no post types list was exported or we do not know our current context,
	// run the callback as normal.
	if ( ! currentPostType || ! Array.isArray( postTypes ) || ! postTypes.length ) {
		callback( module );
		return;
	}

	// If a postTypes list was exported, run only if this post is one of those types.
	if ( postTypes.includes( currentPostType ) ) {
		callback( module );
	}
};

// Load all block index files.
autoloadBlocks(
	{
		/**
		 * Return a project-specific require.context.
		 */
		getContext: () => require.context( './blocks', true, /index\.js$/ ),

		register: runIfSupportedPostType( registerBlock ),
		unregister: runIfSupportedPostType( unregisterBlock ),
	},
	( context, loadModules ) => {
		if ( module.hot ) {
			module.hot.accept( context.id, loadModules );
		}
	}
);
