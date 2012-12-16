<?php

return array(
	'title' => __('Vafpress Option Panel', 'vafpress'),
	'page' => __('Vafpress Menu', 'vafpress'),
	'logo' => '',
	'menus' => array(
		array(
			'title' => __('Standard HTML Controls', 'vafpress'),
			'name' => 'menu_1',
			'icon' => '/icon/standard.png',
			'menus' => array(
				array(
					'title' => __('Regular', 'vafpress'),
					'name' => 'submenu_1',
					'icon' => '/icon/standard-regular.png',
					'sections' => array(
						array(
							'title' => __('TextBox and TextArea', 'vafpress'),
							'name' => 'section_1',
							'description' => __('TextBox and TextArea Showcase', 'vafpress'),
							'fields' => array(
								array(
									'type' => 'textbox',
									'name' => 'tb_1',
									'label' => __('Alphabet', 'vafpress'),
									'description' => __('Only alphabets allowed here.', 'vafpress'),
									'default' => 'abcdefg',
									'validation' => 'alphabet',
								),
								array(
									'type' => 'textbox',
									'name' => 'tb_2',
									'label' => __('Alphanumeric', 'vafpress'),
									'description' => __('Only alphabets and numbers allowed here.', 'vafpress'),
									'default' => 'abcd123',
									'validation' => 'alphanumeric',
								),
								array(
									'type' => 'textbox',
									'name' => 'tb_3',
									'label' => __('Numeric', 'vafpress'),
									'description' => __('Only numbers allowed here.', 'vafpress'),
									'default' => '123',
									'validation' => 'numeric',
								),
								array(
									'type' => 'textbox',
									'name' => 'tb_4',
									'label' => __('Email', 'vafpress'),
									'description' => __('Only valid email allowed here.', 'vafpress'),
									'default' => 'contact@vafour.com',
									'validation' => 'email',
								),
								array(
									'type' => 'textbox',
									'name' => 'tb_5',
									'label' => __('URL', 'vafpress'),
									'description' => __('Only valid URL allowed here.', 'vafpress'),
									'default' => 'http://vafpress.com',
									'validation' => 'url',
								),
								array(
									'type' => 'textarea',
									'name' => 'ta_1',
									'label' => __('Textarea', 'vafpress'),
									'description' => __('Everytime you need long text..', 'vafpress'),
									'height' => '300',
									'default' => 'lorem ipsum',
								),
							),
						),
						array(
							'name' => 'section_2',
							'title' => __('Multiple Choices', 'vafpress'),
							'description' => __('Controls with multiple specified options.', 'vafpress'),
							'fields' => array(
								array(
									'type' => 'checkbox',
									'name' => 'cb_1',
									'label' => __('CheckBox with Min and Max Selected Validation', 'vafpress'),
									'description' => __('Minimum selected of 2 items and maximum selected of 2 items, in other words need to choose exactly 2 items.', 'vafpress'),
									'validation' => 'minselected[2]|maxselected[2]',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vafpress'),
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vafpress'),
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vafpress'),
										),
									),
									'default' => array(
										'value_1',
									),
								),
								array(
									'type' => 'checkbox',
									'name' => 'cb_2',
									'label' => __('CheckBox with Required Validation', 'vafpress'),
									'description' => __('Required to choose anything.', 'vafpress'),
									'validation' => 'required',
									'default' => '',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vafpress'),
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vafpress'),
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vafpress'),
										),
									),
								),
								array(
									'type' => 'radiobutton',
									'name' => 'rb_1',
									'label' => __('RadioButton', 'vafpress'),
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vafpress'),
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vafpress'),
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vafpress'),
										),
									),
									'default' => array(
										'value_3',
									),
								),
								array(
									'type' => 'select',
									'name' => 'ss_1',
									'label' => __('Single Select Box', 'vafpress'),
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vafpress'),
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vafpress'),
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vafpress'),
										),
									),
									'default' => array(
										'value_3',
									),
								),
								array(
									'type' => 'select',
									'name' => 'ss_2',
									'label' => __('Select Box with Get Categories Data Source', 'vafpress'),
									'items' => array(
										'data' => array(
											array(
												'type' => 'function',
												'name' => 'vp_get_categories',
											),
										),
									),
									'default' => array(
										'value_3',
									),
								),
								array(
									'type' => 'multiselect',
									'name' => 'ms_1',
									'label' => __('Multiple Select Box', 'vafpress'),
									'description' => __('Minimum selected of 2 items and maximum selected of 3 items.', 'vafpress'),
									'validation' => 'minselected[2]|maxselected[3]',
									'default' => '',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vafpress'),
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vafpress'),
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vafpress'),
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vafpress'),
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
					'title' => __('Image', 'vafpress'),
					'name' => 'submenu_2',
					'icon' => '/icon/standard-image.png',
					'sections' => array(
						array(
							'title' => __('Check Images', 'vafpress'),
							'fields' => array(
								array(
									'type' => 'checkimage',
									'name' => 'ci_1',
									'label' => __('Various Sized Images', 'vafpress'),
									'description' => __('CheckImage with unspecified item max height and item max width', 'vafpress'),
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vafpress'),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vafpress'),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vafpress'),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vafpress'),
											'img' => 'http://placehold.it/50x50',
										),
									),
								),
								array(
									'type' => 'checkimage',
									'name' => 'ci_2',
									'label' => __('Specified Images Maximum Height', 'vafpress'),
									'description' => __('CheckImage with specified item max height', 'vafpress'),
									'item_max_height' => '70',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vafpress'),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vafpress'),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vafpress'),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vafpress'),
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
									'label' => __('Specified Images Maximum Width', 'vafpress'),
									'description' => __('CheckImage with specified item max width', 'vafpress'),
									'item_max_width' => '50',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vafpress'),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vafpress'),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vafpress'),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vafpress'),
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
									'label' => __('Specified Images Maximum Width and Height', 'vafpress'),
									'description' => __('CheckImage with specified item max width and item max height', 'vafpress'),
									'item_max_height' => '70',
									'item_max_width' => '70',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vafpress'),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vafpress'),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vafpress'),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vafpress'),
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
									'label' => __('Validation Rules Applied', 'vafpress'),
									'description' => __('Minimun selected of 2 items and Maximum selected of 3 items.', 'vafpress'),
									'item_max_height' => '70',
									'item_max_width' => '70',
									'validation' => 'minselected[2]|maxselected[3]',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vafpress'),
											'img' => 'http://placehold.it/80x80',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vafpress'),
											'img' => 'http://placehold.it/80x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vafpress'),
											'img' => 'http://placehold.it/80x80',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vafpress'),
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
							'title' => __('Radio Images', 'vafpress'),
							'fields' => array(
								array(
									'type' => 'radioimage',
									'name' => 'ri_1',
									'label' => __('Various Sized Images', 'vafpress'),
									'description' => __('RadioImage with unspecified item max height and item max width', 'vafpress'),
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vafpress'),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vafpress'),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vafpress'),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vafpress'),
											'img' => 'http://placehold.it/50x50',
										),
									),
								),
								array(
									'type' => 'radioimage',
									'name' => 'ri_2',
									'label' => __('Specified Images Maximum Height', 'vafpress'),
									'description' => __('RadioImage with specified item max height', 'vafpress'),
									'item_max_height' => '70',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vafpress'),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vafpress'),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vafpress'),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vafpress'),
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
									'label' => __('Specified Images Maximum Width', 'vafpress'),
									'description' => __('RadioImage with specified item max width', 'vafpress'),
									'item_max_width' => '50',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vafpress'),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vafpress'),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vafpress'),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vafpress'),
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
									'label' => __('Specified Images Maximum Width and Height', 'vafpress'),
									'description' => __('RadioImage with specified item max width and item max height', 'vafpress'),
									'item_max_height' => '70',
									'item_max_width' => '70',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vafpress'),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vafpress'),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vafpress'),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vafpress'),
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
									'label' => __('Validation Rules Applied', 'vafpress'),
									'description' => __('Required to Choose.', 'vafpress'),
									'item_max_height' => '70',
									'item_max_width' => '70',
									'validation' => 'required',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vafpress'),
											'img' => 'http://placehold.it/80x80',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vafpress'),
											'img' => 'http://placehold.it/80x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vafpress'),
											'img' => 'http://placehold.it/80x80',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vafpress'),
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
			'title' => __('Special Controls', 'vafpress'),
			'name' => 'menu_2',
			'icon' => '/icon/special.png',
					'sections' => array(
						array(
							'title' => __('Section 1', 'vafpress'),
							'fields' => array(
								array(
									'type' => 'toggle',
									'name' => 'tg_1',
									'label' => __('Toggle', 'vafpress'),
									'description' => __('Suits the need to ask user a yes or no option.', 'vafpress'),
									'default' => '1',
								),
								array(
									'type' => 'slider',
									'name' => 'sl_1',
									'label' => __('Decimal Slider', 'vafpress'),
									'description' => __('This slider has minimum value of -10, maximum value of 17.5, sliding step of 0.1 and default value 15.9, everything can be customized.', 'vafpress'),
									'min' => '-10',
									'max' => '17.5',
									'step' => '0.1',
									'default' => '15.9',
								),
								array(
									'type' => 'slider',
									'name' => 'sl_2',
									'label' => __('Custom Step Slider', 'vafpress'),
									'description' => __('This slider has minimum value of 100, maximum value of 1000, sliding step of 5 and default value 275, everything can be customized.', 'vafpress'),
									'min' => '100',
									'max' => '1000',
									'step' => '5',
									'default' => '275',
								),
								array(
									'type' => 'upload',
									'name' => 'up_1',
									'label' => __('Upload', 'vafpress'),
									'description' => __('Media uploader, using the powerful WP Media Upload', 'vafpress'),
									'default' => 'http://placehold.it/70x70',
								),
								array(
									'type' => 'color',
									'name' => 'cl_1',
									'label' => __('Color 1', 'vafpress'),
									'description' => __('Color Picker, you can set the default color.', 'vafpress'),
									'default' => '#3eb9e6',
								),
								array(
									'type' => 'color',
									'name' => 'cl_2',
									'label' => __('Color 2', 'vafpress'),
									'description' => __('Color Picker, you can set the default color.', 'vafpress'),
									'default' => '#98ed28',
								),
								array(
									'type' => 'date',
									'name' => 'dt_1',
									'label' => __('International Date Format', 'vafpress'),
									'description' => __('this is description', 'vafpress'),
									'format' => 'yy-mm-dd',
									'default' => '2012-12-12',
								),
								array(
									'type' => 'date',
									'name' => 'dt_2',
									'label' => __('Asian Date Format', 'vafpress'),
									'description' => __('this is description', 'vafpress'),
									'format' => 'dd-mm-yy',
									'default' => '',
									'validation' => 'required',
								),
								array(
									'type' => 'date',
									'name' => 'dt_3',
									'label' => __('Ranged Date Picker', 'vafpress'),
									'description' => __('The range can be exact date or formatted string to define the offset from today, for example "+1D" will be parsed as tommorow date, or "+1D +1W", please refer to [jQueryUI Datepicker Docs](http://jqueryui.com/datepicker/#min-max)', 'vafpress'),
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