<?php
if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_additional-information',
		'title' => 'Additional Information',
		'fields' => array (
			array (
				'key' => 'field_52f57794e685a',
				'label' => 'Website Link',
				'name' => 'website_link',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'hotel',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_company-logo',
		'title' => 'Company Logo',
		'fields' => array (
			array (
				'key' => 'field_52f577154b93a',
				'label' => 'logo',
				'name' => 'logo',
				'type' => 'image',
				'save_format' => 'id',
				'preview_size' => 'logo',
				'library' => 'all',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'hotel',
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
		'menu_order' => 2,
	));
}
