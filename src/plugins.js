/**
 * Dynamically locate, load & register all Gutenberg plugins.
 */
import {
	autoload,
	registerPlugin,
	unregisterPlugin,
} from 'block-editor-hmr';

// Load all plugin index files.
autoload(
	{
		/**
		 * Return a project-specific require.context.
		 */
		getContext: () => require.context( './plugins', true, /index\.js$/ ),

		register: registerPlugin,
		unregister: unregisterPlugin,
	},
	( context, loadModules ) => {
		if ( module.hot ) {
			module.hot.accept( context.id, loadModules );
		}
	}
);
