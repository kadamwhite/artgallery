/**
 * Dynamically locate, load & register all Gutenberg blocks.
 */
import {
	autoload,
	registerBlock,
	unregisterBlock,
	beforeUpdateBlocks,
	afterUpdateBlocks,
} from 'block-editor-hmr';

// Load all block index files.
autoload(
	{
		/**
		 * Return a project-specific require.context.
		 */
		getContext: () => require.context( './blocks', true, /index\.js$/ ),

		register: registerBlock,
		unregister: unregisterBlock,
		before: beforeUpdateBlocks,
		after: afterUpdateBlocks,
	},
	( context, loadModules ) => {
		if ( module.hot ) {
			module.hot.accept( context.id, loadModules );
		}
	}
);
