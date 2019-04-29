<?php
/**
 *  Install Add-ons
 *
 *  The following code will include all 4 premium Add-Ons in your theme.
 *  Please do not attempt to include a file which does not exist. This will produce an error.
 *
 *  The following code assumes you have a folder 'add-ons' inside your theme.
 *
 *  IMPORTANT
 *  Add-ons may be included in a premium theme/plugin as outlined in the terms and conditions.
 *  For more information, please read:
 *  - http://www.advancedcustomfields.com/terms-conditions/
 *  - http://www.advancedcustomfields.com/resources/getting-started/including-lite-mode-in-a-plugin-theme/
 */

// Add-ons
// include_once('add-ons/acf-repeater/acf-repeater.php');
// include_once('add-ons/acf-gallery/acf-gallery.php');
// include_once('add-ons/acf-flexible-content/acf-flexible-content.php');
// include_once( 'add-ons/acf-options-page/acf-options-page.php' );


/**
 *  Register Field Groups
 *
 *  The register_field_group function accepts 1 array which holds the relevant data to register a field group
 *  You may edit the array as you see fit. However, this may result in errors if the array is not compatible with ACF
 */

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_artwork',
		'title' => 'Artwork',
		'fields' => array (
			array (
				'key' => 'field_51c3e6f68dc45',
				'label' => '',
				'name' => '',
				'type' => 'message',
				'instructions' => 'Paste the URL of the YouTube or Vimeo video you wish to embed into this text box',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_51c3e66a8dc44',
							'operator' => '==',
							'value' => 'video',
						),
					),
					'allorany' => 'all',
				),
				'message' => 'Paste the URL of the YouTube or Vimeo video you wish to embed into the text box above. Use the image field below to upload a thumbnail image for your video.',
			),
			array (
				'key' => 'field_51c3e66a8dc44',
				'label' => 'Type of Artwork',
				'name' => 'artwork_type',
				'type' => 'radio',
				'instructions' => 'Select whether this artwork is a static image or a video',
				'required' => 1,
				'choices' => array (
					'image' => 'Image',
					'video' => 'Video',
				),
				'other_choice' => 0,
				'save_other_choice' => 0,
				'default_value' => 'image',
				'layout' => 'vertical',
			),
			array (
				'key' => 'field_51c3e443ad21f',
				'label' => 'Image',
				'name' => 'artwork_image',
				'type' => 'image',
				'instructions' => 'Upload the image of this artwork',
				'save_format' => 'object',
				'preview_size' => 'medium',
				'library' => 'all',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'ag_artwork_item',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
				0 => 'custom_fields',
				1 => 'featured_image',
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_artwork-date',
		'title' => 'Artwork Date',
		'fields' => array (
			array (
				'key' => 'field_51cdb68a07189',
				'label' => 'Date Created',
				'name' => 'artwork_date_created',
				'type' => 'date_picker',
				'instructions' => 'When did you finish / give up on this work?',
				'date_format' => 'yymmdd',
				'display_format' => 'MM yy',
				'first_day' => 1,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'ag_artwork_item',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'side',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 1,
	));
}
