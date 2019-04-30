/**
 * Define a Block Editor plugin to display a warning in the publish box
 * if a featured image is not set for an artwork item.
 */
import {
	PluginPostStatusInfo,
	PluginPrePublishPanel,
} from '@wordpress/edit-post';

const name = 'artgallery/featured-item-alert';

// const { __ } = wp.i18n;
// const { PluginPostStatusInfo } = wp.editPost;

// const MyPluginPostStatusInfo = () => (
// 	<PluginPostStatusInfo>
// 		{ __( 'My post status info' ) }
// 	</PluginPostStatusInfo>
// );
