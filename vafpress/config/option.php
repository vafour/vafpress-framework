<?php

return array(
	'title' => __('Vafpress Option Panel', VP_TEXTDOMAIN),
	'page' => __('Vafpress Menu', VP_TEXTDOMAIN),
	'logo' => '',
	'menus' => array(
		array(
			'title' => __('Standard HTML Controls', VP_TEXTDOMAIN),
			'name' => 'menu_1',
			'icon' => '/icon/standard.png',
			'menus' => array(
				array(
					'title' => __('Regular', VP_TEXTDOMAIN),
					'name' => 'submenu_1',
					'icon' => '/icon/standard-regular.png',
					'sections' => array(
						array(
							'title' => __('TextBox and TextArea', VP_TEXTDOMAIN),
							'name' => 'section_1',
							'description' => __('TextBox and TextArea Showcase', VP_TEXTDOMAIN),
							'fields' => array(
								array(
									'type' => 'textbox',
									'name' => 'tb_1',
									'label' => __('Alphabet', VP_TEXTDOMAIN),
									'description' => __('Only alphabets allowed here.', VP_TEXTDOMAIN),
									'default' => 'abcdefg',
									'validation' => 'alphabet',
								),
								array(
									'type' => 'textbox',
									'name' => 'tb_2',
									'label' => __('Alphanumeric', VP_TEXTDOMAIN),
									'description' => __('Only alphabets and numbers allowed here.', VP_TEXTDOMAIN),
									'default' => 'abcd123',
									'validation' => 'alphanumeric',
								),
								array(
									'type' => 'textbox',
									'name' => 'tb_3',
									'label' => __('Numeric', VP_TEXTDOMAIN),
									'description' => __('Only numbers allowed here.', VP_TEXTDOMAIN),
									'default' => '123',
									'validation' => 'numeric',
								),
								array(
									'type' => 'textbox',
									'name' => 'tb_4',
									'label' => __('Email', VP_TEXTDOMAIN),
									'description' => __('Only valid email allowed here.', VP_TEXTDOMAIN),
									'default' => 'contact@vafour.com',
									'validation' => 'email',
								),
								array(
									'type' => 'textbox',
									'name' => 'tb_5',
									'label' => __('URL', VP_TEXTDOMAIN),
									'description' => __('Only valid URL allowed here.', VP_TEXTDOMAIN),
									'default' => 'http://vafpress.com',
									'validation' => 'url',
								),
								array(
									'type' => 'textarea',
									'name' => 'ta_1',
									'label' => __('Textarea', VP_TEXTDOMAIN),
									'description' => __('Everytime you need long text..', VP_TEXTDOMAIN),
									'height' => '300',
									'default' => 'lorem ipsum',
								),
							),
						),
						array(
							'name' => 'section_2',
							'title' => __('Multiple Choices', VP_TEXTDOMAIN),
							'description' => __('Controls with multiple specified options.', VP_TEXTDOMAIN),
							'fields' => array(
								array(
									'type' => 'checkbox',
									'name' => 'cb_1',
									'label' => __('CheckBox with Min and Max Selected Validation', VP_TEXTDOMAIN),
									'description' => __('Minimum selected of 2 items and maximum selected of 2 items, in other words need to choose exactly 2 items.', VP_TEXTDOMAIN),
									'validation' => 'minselected[2]|maxselected[2]',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', VP_TEXTDOMAIN),
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', VP_TEXTDOMAIN),
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', VP_TEXTDOMAIN),
										),
									),
									'default' => array(
										'value_1',
									),
								),
								array(
									'type' => 'checkbox',
									'name' => 'cb_2',
									'label' => __('CheckBox with Required Validation', VP_TEXTDOMAIN),
									'description' => __('Required to choose anything.', VP_TEXTDOMAIN),
									'validation' => 'required',
									'default' => '',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', VP_TEXTDOMAIN),
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', VP_TEXTDOMAIN),
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', VP_TEXTDOMAIN),
										),
									),
								),
								array(
									'type' => 'radiobutton',
									'name' => 'field_5',
									'label' => __('RadioButton', VP_TEXTDOMAIN),
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', VP_TEXTDOMAIN),
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', VP_TEXTDOMAIN),
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', VP_TEXTDOMAIN),
										),
									),
									'default' => array(
										'value_3',
									),
								),
								array(
									'type' => 'select',
									'name' => 'field_12',
									'label' => __('Single Select Box', VP_TEXTDOMAIN),
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', VP_TEXTDOMAIN),
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', VP_TEXTDOMAIN),
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', VP_TEXTDOMAIN),
										),
									),
									'default' => array(
										'value_3',
									),
								),
								array(
									'type' => 'multiselect',
									'name' => 'field_13',
									'label' => __('Multiple Select Box', VP_TEXTDOMAIN),
									'description' => __('Minimum selected of 2 items and maximum selected of 3 items.', VP_TEXTDOMAIN),
									'validation' => 'minselected[2]|maxselected[3]',
									'default' => '',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', VP_TEXTDOMAIN),
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', VP_TEXTDOMAIN),
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', VP_TEXTDOMAIN),
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', VP_TEXTDOMAIN),
										),
									),
									'default' => array(
										'value_1',
									),
								),
							),
						),
					),
				),
				array(
					'title' => __('Image', VP_TEXTDOMAIN),
					'name' => 'submenu_2',
					'icon' => '/icon/standard-image.png',
					'sections' => array(
						array(
							'title' => __('Check Images', VP_TEXTDOMAIN),
							'fields' => array(
								array(
									'type' => 'checkimage',
									'name' => 'ci_1',
									'label' => __('Various Sized Images', VP_TEXTDOMAIN),
									'description' => __('CheckImage with unspecified item max height and item max width', VP_TEXTDOMAIN),
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/50x50',
										),
									),
								),
								array(
									'type' => 'checkimage',
									'name' => 'ci_2',
									'label' => __('Specified Images Maximum Height', VP_TEXTDOMAIN),
									'description' => __('CheckImage with specified item max height', VP_TEXTDOMAIN),
									'item_max_height' => '70',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/50x50',
										),
									),
									'default' => array(
										'value_1',
										'value_2',
									),
								),
								array(
									'type' => 'checkimage',
									'name' => 'ci_3',
									'label' => __('Specified Images Maximum Width', VP_TEXTDOMAIN),
									'description' => __('CheckImage with specified item max width', VP_TEXTDOMAIN),
									'item_max_width' => '50',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/50x50',
										),
									),
									'default' => array(
										'value_3',
										'value_4',
									),
								),
								array(
									'type' => 'checkimage',
									'name' => 'ci_4',
									'label' => __('Specified Images Maximum Width and Height', VP_TEXTDOMAIN),
									'description' => __('CheckImage with specified item max width and item max height', VP_TEXTDOMAIN),
									'item_max_height' => '70',
									'item_max_width' => '70',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/50x50',
										),
									),
									'default' => array(
										'value_1',
										'value_4',
									),
								),
								array(
									'type' => 'checkimage',
									'name' => 'ci_5',
									'label' => __('Validation Rules Applied', VP_TEXTDOMAIN),
									'description' => __('Minimun selected of 2 items and Maximum selected of 3 items.', VP_TEXTDOMAIN),
									'item_max_height' => '70',
									'item_max_width' => '70',
									'validation' => 'minselected[2]|maxselected[3]',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/80x80',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/80x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/80x80',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/80x80',
										),
									),
									'default' => array(
										'value_1',
									),
								),
							),
						),
						array(
							'title' => __('Radio Images', VP_TEXTDOMAIN),
							'fields' => array(
								array(
									'type' => 'radioimage',
									'name' => 'ri_1',
									'label' => __('Various Sized Images', VP_TEXTDOMAIN),
									'description' => __('RadioImage with unspecified item max height and item max width', VP_TEXTDOMAIN),
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/50x50',
										),
									),
								),
								array(
									'type' => 'radioimage',
									'name' => 'ri_2',
									'label' => __('Specified Images Maximum Height', VP_TEXTDOMAIN),
									'description' => __('RadioImage with specified item max height', VP_TEXTDOMAIN),
									'item_max_height' => '70',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/50x50',
										),
									),
									'default' => array(
										'value_1',
									),
								),
								array(
									'type' => 'radioimage',
									'name' => 'ri_3',
									'label' => __('Specified Images Maximum Width', VP_TEXTDOMAIN),
									'description' => __('RadioImage with specified item max width', VP_TEXTDOMAIN),
									'item_max_width' => '50',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/50x50',
										),
									),
									'default' => array(
										'value_3',
									),
								),
								array(
									'type' => 'radioimage',
									'name' => 'ri_4',
									'label' => __('Specified Images Maximum Width and Height', VP_TEXTDOMAIN),
									'description' => __('RadioImage with specified item max width and item max height', VP_TEXTDOMAIN),
									'item_max_height' => '70',
									'item_max_width' => '70',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/50x50',
										),
									),
									'default' => array(
										'value_4',
									),
								),
								array(
									'type' => 'radioimage',
									'name' => 'ri_5',
									'label' => __('Validation Rules Applied', VP_TEXTDOMAIN),
									'description' => __('Required to Choose.', VP_TEXTDOMAIN),
									'item_max_height' => '70',
									'item_max_width' => '70',
									'validation' => 'required',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/80x80',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/80x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/80x80',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', VP_TEXTDOMAIN),
											'img' => 'http://placehold.it/80x80',
										),
									),
								),
							),
						),
					),
				),
			),
		),
		array(
			'title' => __('Special Controls', VP_TEXTDOMAIN),
			'name' => 'menu_2',
			'icon' => '/icon/special.png',
					'sections' => array(
						array(
							'title' => __('Section 1', VP_TEXTDOMAIN),
							'fields' => array(
								array(
									'type' => 'toggle',
									'name' => 'tg_1',
									'label' => __('Toggle', VP_TEXTDOMAIN),
									'description' => __('Suits the need to ask user a yes or no option.', VP_TEXTDOMAIN),
									'default' => '1',
								),
								array(
									'type' => 'slider',
									'name' => 'sl_1',
									'label' => __('Decimal Slider', VP_TEXTDOMAIN),
									'description' => __('This slider has minimum value of -10, maximum value of 17.5, sliding step of 0.1 and default value 15.9, everything can be customized.', VP_TEXTDOMAIN),
									'min' => '-10',
									'max' => '17.5',
									'step' => '0.1',
									'default' => '15.9',
								),
								array(
									'type' => 'slider',
									'name' => 'sl_2',
									'label' => __('Custom Step Slider', VP_TEXTDOMAIN),
									'description' => __('This slider has minimum value of 100, maximum value of 1000, sliding step of 5 and default value 275, everything can be customized.', VP_TEXTDOMAIN),
									'min' => '100',
									'max' => '1000',
									'step' => '5',
									'default' => '275',
								),
								array(
									'type' => 'upload',
									'name' => 'up_1',
									'label' => __('Upload', VP_TEXTDOMAIN),
									'description' => __('Media uploader, using the powerful WP Media Upload', VP_TEXTDOMAIN),
									'default' => 'http://placehold.it/70x70',
								),
								array(
									'type' => 'color',
									'name' => 'cl_1',
									'label' => __('Color 1', VP_TEXTDOMAIN),
									'description' => __('Color Picker, you can set the default color.', VP_TEXTDOMAIN),
									'default' => '#3eb9e6',
								),
								array(
									'type' => 'color',
									'name' => 'cl_2',
									'label' => __('Color 2', VP_TEXTDOMAIN),
									'description' => __('Color Picker, you can set the default color.', VP_TEXTDOMAIN),
									'default' => '#98ed28',
								),
								array(
									'type' => 'date',
									'name' => 'dt_1',
									'label' => __('International Date Format', VP_TEXTDOMAIN),
									'description' => __('this is description', VP_TEXTDOMAIN),
									'format' => 'yy-mm-dd',
									'default' => '2012-12-12',
								),
								array(
									'type' => 'date',
									'name' => 'dt_2',
									'label' => __('Asian Date Format', VP_TEXTDOMAIN),
									'description' => __('this is description', VP_TEXTDOMAIN),
									'format' => 'dd-mm-yy',
									'default' => '',
									'validation' => 'required',
								),
								array(
									'type' => 'date',
									'name' => 'dt_3',
									'label' => __('Ranged Date Picker', VP_TEXTDOMAIN),
									'description' => __('The range can be exact date or formatted string to define the offset from today, for example "+1D" will be parsed as tommorow date, or "+1D +1W", please refer to [jQueryUI Datepicker Docs](http://jqueryui.com/datepicker/#min-max)', VP_TEXTDOMAIN),
									'min_date' => '1-1-2000',
									'max_date' => 'today',
									'format' => 'yy-mm-dd',
									'default' => '-1W',
								),
							),
						),
					),
		),
	)
);

/**
 *EOF
 */