<?php
/**
 *  Install Add-ons
 *
 *  The following code will include all 4 premium Add-Ons in your theme.
 *  Please do not attempt to include a file which does not exist. This will produce an error.
 *
 *  All fields must be included during the 'acf/register_fields' action.
 *  Other types of Add-ons (like the options page) can be included outside of this action.
 *
 *  The following code assumes you have a folder 'add-ons' inside your theme.
 *
 *  IMPORTANT
 *  Add-ons may be included in a premium theme as outlined in the terms and conditions.
 *  However, they are NOT to be included in a premium / free plugin.
 *  For more information, please read http://www.advancedcustomfields.com/terms-conditions/
 */

// Fields
add_action('acf/register_fields', 'my_register_fields');

function my_register_fields()
{
	//include_once('add-ons/acf-repeater/repeater.php');
	//include_once('add-ons/acf-gallery/gallery.php');
	//include_once('add-ons/acf-flexible-content/flexible-content.php');
}

// Options Page
//include_once( 'add-ons/acf-options-page/acf-options-page.php' );


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
				'display_format' => 'dd/mm/yy',
				'first_day' => 1,
			),
			array (
				'key' => 'field_51f35e8145545',
				'label' => '',
				'name' => '',
				'type' => 'message',
				'message' => '<small><strong>Note:</strong> this date determines the order in which work appears on the site. Newer works will display first: an artwork dated 3/22/13 will display before an artwork dated 3/10/13.</small>',
			),
			array (
				'key' => 'field_51ede948847be',
				'label' => 'Date Accuracy',
				'name' => 'artwork_date_accuracy',
				'type' => 'select',
				'instructions' => 'By default, the date for an artwork will render as a month (e.g. "March, 2013"). Change this value to have this work display with the full calendar date (e.g. "March 22, 2013").',
				'choices' => array (
					'month' => 'Month',
					'day' => 'Day',
				),
				'default_value' => 'month',
				'allow_null' => 0,
				'multiple' => 0,
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
				'key' => 'field_51c3f454b36c8',
				'label' => 'Availability',
				'name' => 'artwork_sold',
				'type' => 'checkbox',
				'choices' => array (
					'sold' => 'Artwork has been sold',
				),
				'default_value' => '',
				'layout' => 'horizontal',
			),
			array (
				'key' => 'field_51c3f51c1d411',
				'label' => 'Price',
				'name' => 'artwork_sale_price',
				'type' => 'number',
				'instructions' => 'Retail price of the artwork (will not display on site)',
				'default_value' => '',
				'min' => '',
				'max' => '',
				'step' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
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
							'field' => 'field_51c3f454b36c8',
							'operator' => '==',
							'value' => 'sold',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'none',
				'maxlength' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
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
							'field' => 'field_51c3f454b36c8',
							'operator' => '==',
							'value' => 'sold',
						),
					),
					'allorany' => 'all',
				),
				'date_format' => 'yymmdd',
				'display_format' => 'dd/mm/yy',
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
							'field' => 'field_51c3f454b36c8',
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
				'key' => 'field_51c3ff22f40b5',
				'label' => 'Message',
				'name' => '',
				'type' => 'message',
				'message' => 'If prints, cards or other reproductions of this work are available for sale, you can include a link to the site where users may purchase them:',
			),
			array (
				'key' => 'field_51c3f5b939bb9',
				'label' => 'Etsy Link URL',
				'name' => 'artwork_shop1_link',
				'type' => 'text',
				'instructions' => 'Enter a URL where posters, cards or other reproductions of this work may be purchased',
				'default_value' => '',
				'formatting' => 'none',
				'maxlength' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_51c3fe722f5e6',
				'label' => 'Etsy Link Text',
				'name' => 'artwork_shop1_link_text',
				'type' => 'text',
				'instructions' => 'Enter the text that will display as the link to that URL (will not display on site if no URL is specified)',
				'default_value' => 'Buy a print of this image on Etsy',
				'formatting' => 'none',
				'maxlength' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_51ee03babd166',
				'label' => 'Society6 Link URL',
				'name' => 'artwork_shop2_link',
				'type' => 'text',
				'instructions' => 'Enter a URL where posters, cards or other reproductions of this work may be purchased',
				'default_value' => '',
				'formatting' => 'none',
				'maxlength' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_51ee0399bd165',
				'label' => 'Society6 Link Text',
				'name' => 'artwork_shop2_link_text',
				'type' => 'text',
				'instructions' => 'Enter the text that will display as the link to that URL (will not display on site if no URL is specified)',
				'default_value' => 'Buy a print of this image on Society6',
				'formatting' => 'none',
				'maxlength' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
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
