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
	register_field_group(array (
		'id' => 'acf_sale-provenance',
		'title' => 'Sale & Provenance',
		'fields' => array (
			array (
				'key' => 'field_51c3f59639bb7',
				'label' => 'Original',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_523476c022ae2',
				'label' => '',
				'name' => '',
				'type' => 'message',
				'message' => 'While marking an artwork as "Available" will add the text "Contact artist for pricing" to the artwork\'s page, all other information (sold/NFS, pricing, purchaser information, etc) are for record-keeping purposes only and will not be displayed to site visitors.',
			),
			array (
				'key' => 'field_5234d8e5e6f80',
				'label' => 'Availability',
				'name' => 'artwork_availability',
				'type' => 'taxonomy',
				'instructions' => 'Defaults to "Available" when saved if no value is selected',
				'taxonomy' => 'ag_artwork_availability',
				'field_type' => 'radio',
				'allow_null' => 0,
				'load_save_terms' => 1,
				'return_format' => 'id',
				'multiple' => 0,
			),
			array (
				'key' => 'field_5234e4a80fa5b',
				'label' => 'Availability Proxy',
				'name' => 'artwork_availability_proxy',
				'type' => 'radio',
				'instructions' => 'This radio button field is used solely to control the conditional rendering of the other fields in this section. Custom JavaScript is used to synchronize changes from the main "Availability" field into this (hidden) field, triggering the conditional logic.',
				'choices' => array (
					'available' => 'available',
					'sold' => 'sold',
					'notforsale' => 'notforsale',
				),
				'other_choice' => 0,
				'save_other_choice' => 0,
				'default_value' => '',
				'layout' => 'vertical',
			),
			array (
				'key' => 'field_51c3f51c1d411',
				'label' => 'Price',
				'name' => 'artwork_sale_price',
				'type' => 'number',
				'instructions' => 'Retail price of the artwork (will not display on site)',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_5234e4a80fa5b',
							'operator' => '!=',
							'value' => 'notforsale',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
			array (
				'key' => 'field_51c3f4e61d410',
				'label' => 'Location of Sale',
				'name' => 'artwork_sale_location',
				'type' => 'text',
				'instructions' => 'Where was the original work sold?',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_5234e4a80fa5b',
							'operator' => '==',
							'value' => 'sold',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_51c3fe1beb4a4',
				'label' => 'Date of Sale',
				'name' => 'artwork_sale_date',
				'type' => 'date_picker',
				'instructions' => 'When was the original work sold?',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_5234e4a80fa5b',
							'operator' => '==',
							'value' => 'sold',
						),
					),
					'allorany' => 'all',
				),
				'date_format' => 'yymmdd',
				'display_format' => 'mm/dd/yy',
				'first_day' => 1,
			),
			array (
				'key' => 'field_51c3f5591d412',
				'label' => 'Purchaser',
				'name' => 'artwork_sale_purchaser',
				'type' => 'wysiwyg',
				'instructions' => 'Information about the purchaser of the work',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_5234e4a80fa5b',
							'operator' => '==',
							'value' => 'sold',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'toolbar' => 'basic',
				'media_upload' => 'no',
			),
			array (
				'key' => 'field_51c3f5aa39bb8',
				'label' => 'Reproductions',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_52347937aa527',
				'label' => 'Online Retailers',
				'name' => 'artwork_retailers',
				'type' => 'repeater',
				'instructions' => 'If prints, cards or other reproductions of this work are available for sale, you can include a link to the sites where users may purchase them.',
				'sub_fields' => array (
					array (
						'key' => 'field_523481ad31536',
						'label' => 'Link Text',
						'name' => 'artwork_retailer_link_text',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => 'e.g. Buy a print of this image on [Retailer\'s Site]',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_5234815831535',
						'label' => 'Link URL',
						'name' => 'artwork_retailer_url',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
				),
				'row_min' => 0,
				'row_limit' => '',
				'layout' => 'table',
				'button_label' => 'Add Retailer',
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
			'layout' => 'default',
			'hide_on_screen' => array (
				0 => 'custom_fields',
			),
		),
		'menu_order' => 2,
	));
}
