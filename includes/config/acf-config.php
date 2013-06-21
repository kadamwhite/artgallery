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
				'layout' => 'vertical',
				'choices' => array (
					'image' => 'Image',
					'video' => 'Video',
				),
				'default_value' => 'image',
				'key' => 'field_51c3e66a8dc44',
				'label' => 'Type of Artwork',
				'name' => 'artwork_type',
				'type' => 'radio',
				'instructions' => 'Select whether this artwork is a static image or a video',
				'required' => 1,
			),
			array (
				'save_format' => 'object',
				'preview_size' => 'medium',
				'library' => 'all',
				'key' => 'field_51c3e443ad21f',
				'label' => 'Image',
				'name' => 'artwork_image',
				'type' => 'image',
				'instructions' => 'Upload the image of this artwork',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_51c3e66a8dc44',
							'operator' => '==',
							'value' => 'image',
						),
					),
					'allorany' => 'all',
				),
			),
			array (
				'toolbar' => 'basic',
				'media_upload' => 'no',
				'default_value' => '',
				'key' => 'field_51c3e6f68dc45',
				'label' => 'Video',
				'name' => 'artwork_video',
				'type' => 'wysiwyg',
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
			),
			array (
				'toolbar' => 'full',
				'media_upload' => 'yes',
				'default_value' => '',
				'key' => 'field_51c40a033d327',
				'label' => 'Description',
				'name' => 'artwork_description',
				'type' => 'wysiwyg',
				'instructions' => 'Enter any comments or description for this work',
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
				0 => 'the_content',
				1 => 'custom_fields',
				2 => 'featured_image',
			),
		),
		'menu_order' => 0,
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
				'layout' => 'horizontal',
				'choices' => array (
					'sold' => 'Artwork has been sold',
				),
				'default_value' => '',
				'key' => 'field_51c3f454b36c8',
				'label' => 'Availability',
				'name' => 'artwork_sold',
				'type' => 'checkbox',
			),
			array (
				'default_value' => '',
				'min' => '',
				'max' => '',
				'step' => '',
				'key' => 'field_51c3f51c1d411',
				'label' => 'Price',
				'name' => 'artwork_sale_price',
				'type' => 'number',
				'instructions' => 'Retail price of the artwork (will not display on site)',
			),
			array (
				'default_value' => '',
				'formatting' => 'none',
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
			),
			array (
				'date_format' => 'yymmdd',
				'display_format' => 'dd/mm/yy',
				'first_day' => 1,
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
			),
			array (
				'toolbar' => 'basic',
				'media_upload' => 'no',
				'default_value' => '',
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
			),
			array (
				'key' => 'field_51c3f5aa39bb8',
				'label' => 'Reproductions',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'message' => 'If prints, cards or other reproductions of this work are available for sale, you can include a link to the site where users may purchase them:',
				'key' => 'field_51c3ff22f40b5',
				'label' => 'Message',
				'name' => '',
				'type' => 'message',
			),
			array (
				'default_value' => '',
				'formatting' => 'none',
				'key' => 'field_51c3f5b939bb9',
				'label' => 'Etsy Link URL',
				'name' => 'artwork_sale_reproduction_link',
				'type' => 'text',
				'instructions' => 'Enter a URL where posters, cards or other reproductions of this work may be purchased',
			),
			array (
				'default_value' => 'Buy a print of this image on Etsy',
				'formatting' => 'none',
				'key' => 'field_51c3fe722f5e6',
				'label' => 'Etsy Link Text',
				'name' => 'artwork_sale_reproduction_link_text',
				'type' => 'text',
				'instructions' => 'Enter the text that will display as the link to that URL (will not display on site if no URL is specified)',
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
		'menu_order' => 1,
	));
}
