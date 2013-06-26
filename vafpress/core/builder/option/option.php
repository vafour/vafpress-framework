<?php

return array(
	'title' => __('Vafpress Option Panel', 'vp_textdomain'),
	'page' => __('Vafpress Menu', 'vp_textdomain'),
	'logo' => '',
	'menus' => array(
		array(
			'title' => __('Standard Controls', 'vp_textdomain'),
			'name' => 'menu_1',
			'icon' => 'font-awesome:icon-magic',
			'menus' => array(
				array(
					'title' => __('Regular', 'vp_textdomain'),
					'name' => 'submenu_1',
					'icon' => 'font-awesome:icon-th-large',
					'controls' => array(
						array(
							'type' => 'section',
							'title' => __('TextBox', 'vp_textdomain'),
							'name' => 'section_1',
							'description' => __('TextBox and TextArea Showcase', 'vp_textdomain'),
							'fields' => array(
								array(
									'type' => 'textbox',
									'name' => 'tb_1',
									'label' => __('Alphabet', 'vp_textdomain'),
									'description' => __('Only alphabets allowed here.', 'vp_textdomain'),
									'validation' => 'alphabet',
								),
								array(
									'type' => 'textbox',
									'name' => 'tb_2',
									'label' => __('Alphanumeric', 'vp_textdomain'),
									'description' => __('Only alphabets and numbers allowed here.', 'vp_textdomain'),
									'default' => 'abcd123',
									'validation' => 'alphanumeric',
								),
								array(
									'type' => 'textbox',
									'name' => 'tb_3',
									'label' => __('Numeric', 'vp_textdomain'),
									'description' => __('Only numbers allowed here.', 'vp_textdomain'),
									'default' => '123',
									'validation' => 'numeric',
								),
								array(
									'type' => 'textbox',
									'name' => 'tb_4',
									'label' => __('Email', 'vp_textdomain'),
									'description' => __('Only valid email allowed here.', 'vp_textdomain'),
									'default' => 'contact@vafour.com',
									'validation' => 'email',
								),
								array(
									'type' => 'textbox',
									'name' => 'tb_5',
									'label' => __('URL', 'vp_textdomain'),
									'description' => __('Only valid URL allowed here.', 'vp_textdomain'),
									'default' => 'http://vafpress.com',
									'validation' => 'url',
								),
							),
						),
						array(
							'type' => 'textarea',
							'name' => 'ta_1',
							'label' => __('Textarea', 'vp_textdomain'),
							'description' => __('Everytime you need long text..', 'vp_textdomain'),
							'default' => 'lorem ipsum',
						),
						array(
							'type' => 'section',
							'name' => 'section_2',
							'title' => __('Multiple Choices', 'vp_textdomain'),
							'description' => __('Controls with multiple specified options.', 'vp_textdomain'),
							'fields' => array(
								array(
									'type' => 'checkbox',
									'name' => 'cb_1',
									'label' => __('CheckBox with Min and Max Selected Validation', 'vp_textdomain'),
									'description' => __('Minimum selected of 2 items and maximum selected of 2 items, in other words need to choose exactly 2 items.', 'vp_textdomain'),
									'validation' => 'minselected[2]|maxselected[2]',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vp_textdomain'),
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vp_textdomain'),
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vp_textdomain'),
										),
									),
									'default' => array(
										'value_1',
									),
								),
								array(
									'type' => 'checkbox',
									'name' => 'cb_2',
									'label' => __('CheckBox with Required Validation', 'vp_textdomain'),
									'description' => __('Required to choose anything.', 'vp_textdomain'),
									'validation' => 'required',
									'default' => '',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vp_textdomain'),
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vp_textdomain'),
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vp_textdomain'),
										),
									),
								),
								array(
									'type' => 'radiobutton',
									'name' => 'rb_1',
									'label' => __('RadioButton', 'vp_textdomain'),
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vp_textdomain'),
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vp_textdomain'),
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vp_textdomain'),
										),
									),
									'default' => array(
										'value_3',
									),
								),
								array(
									'type' => 'select',
									'name' => 'ss_1',
									'label' => __('Single Select Box', 'vp_textdomain'),
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vp_textdomain'),
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vp_textdomain'),
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vp_textdomain'),
										),
									),
									'default' => array(
										'value_3',
									),
								),
								array(
									'type' => 'select',
									'name' => 'ss_2',
									'label' => __('Select Box with Get Categories Data Source', 'vp_textdomain'),
									'items' => array(
										'data' => array(
											array(
												'source' => 'function',
												'value' => 'vp_get_categories',
											),
										),
									),
									'default' => array(
										'{{last}}',
									),
								),
								array(
									'type' => 'multiselect',
									'name' => 'ms_1',
									'label' => __('Multiple Select Box', 'vp_textdomain'),
									'description' => __('Minimum selected of 2 items and maximum selected of 3 items.', 'vp_textdomain'),
									'validation' => 'minselected[2]|maxselected[3]',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vp_textdomain'),
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vp_textdomain'),
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vp_textdomain'),
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vp_textdomain'),
										),
									),
									'default' => array(
										'{{first}}',
										'{{last}}',
									),
								),
							),
						),
					),
				),
				array(
					'title' => __('Image', 'vp_textdomain'),
					'name' => 'submenu_2',
					'icon' => 'font-awesome:icon-picture',
					'controls' => array(
						array(
							'type' => 'section',
							'title' => __('Check Images', 'vp_textdomain'),
							'fields' => array(
								array(
									'type' => 'checkimage',
									'name' => 'ci_1',
									'label' => __('Various Sized Images', 'vp_textdomain'),
									'description' => __('CheckImage with unspecified item max height and item max width', 'vp_textdomain'),
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vp_textdomain'),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vp_textdomain'),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vp_textdomain'),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vp_textdomain'),
											'img' => 'http://placehold.it/50x50',
										),
									),
								),
								array(
									'type' => 'checkimage',
									'name' => 'ci_2',
									'label' => __('Specified Images Maximum Height', 'vp_textdomain'),
									'description' => __('CheckImage with specified item max height', 'vp_textdomain'),
									'item_max_height' => '70',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vp_textdomain'),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vp_textdomain'),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vp_textdomain'),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vp_textdomain'),
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
									'label' => __('Specified Images Maximum Width', 'vp_textdomain'),
									'description' => __('CheckImage with specified item max width', 'vp_textdomain'),
									'item_max_width' => '50',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vp_textdomain'),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vp_textdomain'),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vp_textdomain'),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vp_textdomain'),
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
									'label' => __('Specified Images Maximum Width and Height', 'vp_textdomain'),
									'description' => __('CheckImage with specified item max width and item max height', 'vp_textdomain'),
									'item_max_height' => '70',
									'item_max_width' => '70',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vp_textdomain'),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vp_textdomain'),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vp_textdomain'),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vp_textdomain'),
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
									'label' => __('Validation Rules Applied', 'vp_textdomain'),
									'description' => __('Minimum selected of 2 items and Maximum selected of 3 items.', 'vp_textdomain'),
									'item_max_height' => '70',
									'item_max_width' => '70',
									'validation' => 'required|minselected[2]|maxselected[3]',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vp_textdomain'),
											'img' => 'http://placehold.it/80x80',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vp_textdomain'),
											'img' => 'http://placehold.it/80x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vp_textdomain'),
											'img' => 'http://placehold.it/80x80',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vp_textdomain'),
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
							'type' => 'section',
							'title' => __('Radio Images', 'vp_textdomain'),
							'fields' => array(
								array(
									'type' => 'radioimage',
									'name' => 'ri_1',
									'label' => __('Various Sized Images', 'vp_textdomain'),
									'description' => __('RadioImage with unspecified item max height and item max width', 'vp_textdomain'),
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vp_textdomain'),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vp_textdomain'),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vp_textdomain'),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vp_textdomain'),
											'img' => 'http://placehold.it/50x50',
										),
									),
								),
								array(
									'type' => 'radioimage',
									'name' => 'ri_2',
									'label' => __('Specified Images Maximum Height', 'vp_textdomain'),
									'description' => __('RadioImage with specified item max height', 'vp_textdomain'),
									'item_max_height' => '70',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vp_textdomain'),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vp_textdomain'),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vp_textdomain'),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vp_textdomain'),
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
									'label' => __('Specified Images Maximum Width', 'vp_textdomain'),
									'description' => __('RadioImage with specified item max width', 'vp_textdomain'),
									'item_max_width' => '50',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vp_textdomain'),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vp_textdomain'),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vp_textdomain'),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vp_textdomain'),
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
									'label' => __('Specified Images Maximum Width and Height', 'vp_textdomain'),
									'description' => __('RadioImage with specified item max width and item max height', 'vp_textdomain'),
									'item_max_height' => '70',
									'item_max_width' => '70',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vp_textdomain'),
											'img' => 'http://placehold.it/100x100',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vp_textdomain'),
											'img' => 'http://placehold.it/120x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vp_textdomain'),
											'img' => 'http://placehold.it/80x120',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vp_textdomain'),
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
									'label' => __('Validation Rules Applied', 'vp_textdomain'),
									'description' => __('Required to Choose.', 'vp_textdomain'),
									'item_max_height' => '70',
									'item_max_width' => '70',
									'validation' => 'required',
									'items' => array(
										array(
											'value' => 'value_1',
											'label' => __('Label 1', 'vp_textdomain'),
											'img' => 'http://placehold.it/80x80',
										),
										array(
											'value' => 'value_2',
											'label' => __('Label 2', 'vp_textdomain'),
											'img' => 'http://placehold.it/80x80',
										),
										array(
											'value' => 'value_3',
											'label' => __('Label 3', 'vp_textdomain'),
											'img' => 'http://placehold.it/80x80',
										),
										array(
											'value' => 'value_4',
											'label' => __('Label 4', 'vp_textdomain'),
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
			'title' => __('Special Controls', 'vp_textdomain'),
			'name' => 'menu_2',
			'icon' => 'font-awesome:icon-gift',
			'controls' => array(
				array(
					'type' => 'section',
					'title' => __('Section 1', 'vp_textdomain'),
					'fields' => array(
						array(
							'type' => 'wpeditor',
							'name' => 'we_1',
							'label' => __('WP TinyMCE Editor', 'vp_textdomain'),
							'description' => __('Wordpress tinyMCE editor.', 'vp_textdomain'),
							'use_external_plugins' => '0',
							'disabled_externals_plugins' => '',
							'disabled_internals_plugins' => '',
						),
						array(
							'type' => 'toggle',
							'name' => 'tg_1',
							'label' => __('Toggle', 'vp_textdomain'),
							'description' => __('Suits the need to ask user a yes or no option.', 'vp_textdomain'),
							'default' => '1',
						),
						array(
							'type' => 'slider',
							'name' => 'sl_1',
							'label' => __('Decimal Slider', 'vp_textdomain'),
							'description' => __('This slider has minimum value of -10, maximum value of 17.5, sliding step of 0.1 and default value 15.9, everything can be customized.', 'vp_textdomain'),
							'min' => '-10',
							'max' => '17.5',
							'step' => '0.1',
							'default' => '15.9',
						),
						array(
							'type' => 'slider',
							'name' => 'sl_2',
							'label' => __('Custom Step Slider', 'vp_textdomain'),
							'description' => __('This slider has minimum value of 100, maximum value of 1000, sliding step of 5 and default value 275, everything can be customized.', 'vp_textdomain'),
							'min' => '100',
							'max' => '1000',
							'step' => '5',
							'default' => '275',
						),
						array(
							'type' => 'upload',
							'name' => 'up_1',
							'label' => __('Upload', 'vp_textdomain'),
							'description' => __('Media uploader, using the powerful WP Media Upload', 'vp_textdomain'),
							'default' => 'http://lorempixel.com/500/400/animals/',
						),
						array(
							'type' => 'color',
							'name' => 'cl_1',
							'label' => __('Color 1', 'vp_textdomain'),
							'description' => __('Color Picker, you can set the default color.', 'vp_textdomain'),
							'default' => 'rgba(255,0,0,0.5)',
							'format' => 'rgba',
						),
						array(
							'type' => 'color',
							'name' => 'cl_2',
							'label' => __('Color 2', 'vp_textdomain'),
							'description' => __('Color Picker, you can set the default color.', 'vp_textdomain'),
							'default' => '#98ed28',
						),
						array(
							'type' => 'date',
							'name' => 'dt_1',
							'label' => __('International Date Format', 'vp_textdomain'),
							'description' => __('Date Picker with International date format.', 'vp_textdomain'),
							'format' => 'yy-mm-dd',
							'default' => '2012-12-12',
						),
						array(
							'type' => 'date',
							'name' => 'dt_2',
							'label' => __('Asian Date Format', 'vp_textdomain'),
							'description' => __('Date Picker with Asian date format.', 'vp_textdomain'),
							'format' => 'dd-mm-yy',
							'default' => '',
							'validation' => 'required',
						),
						array(
							'type' => 'date',
							'name' => 'dt_3',
							'label' => __('Ranged Date Picker', 'vp_textdomain'),
							'description' => __('The range can be exact date or formatted string to define the offset from today, for example &quot;+1D&quot; will be parsed as tommorow date, or &quot;+1D +1W&quot;, please refer to [jQueryUI Datepicker Docs](http://jqueryui.com/datepicker/#min-max)', 'vp_textdomain'),
							'min_date' => '1-1-2000',
							'max_date' => 'today',
							'format' => 'yy-mm-dd',
							'default' => '-1W',
						),
						array(
							'type' => 'fontawesome',
							'name' => 'fa_1',
							'label' => __('Fontawesome Icon', 'vp_textdomain'),
							'description' => __('Fontawesome icon chooser with small preview.', 'vp_textdomain'),
							'default' => array(
								'{{first}}',
							),
						),
						array(
							'type' => 'sorter',
							'name' => 'so_1',
							'label' => __('Sorter', 'vp_textdomain'),
							'description' => __('Select and sort your choices', 'vp_textdomain'),
							'items' => array(
								'data' => array(
									array(
										'source' => 'function',
										'value' => 'vp_get_social_medias',
									),
								),
							),
						),
						array(
							'type' => 'codeeditor',
							'name' => 'ce_1',
							'label' => __('Custom CSS', 'vp_textdomain'),
							'description' => __('Write your custom css here.', 'vp_textdomain'),
							'theme' => 'github',
							'mode' => 'css',
						),
						array(
							'type' => 'codeeditor',
							'name' => 'ce_2',
							'label' => __('Custom JS', 'vp_textdomain'),
							'description' => __('Write your custom js here.', 'vp_textdomain'),
							'theme' => 'twilight',
							'mode' => 'javascript',
						),
					),
				),
			),
		),
		array(
			'title' => __('Custom Data Source', 'vp_textdomain'),
			'name' => 'menu_3',
			'icon' => 'font-awesome:icon-th-list',
			'menus' => array(
				array(
					'title' => __('Dynamic', 'vp_textdomain'),
					'name' => 'dynamic_data_source',
					'icon' => 'font-awesome:icon-fire',
					'controls' => array(
						array(
							'type' => 'section',
							'title' => __('Data Source and Smart Tags', 'vp_textdomain'),
							'fields' => array(
								array(
									'type' => 'multiselect',
									'name' => 'ms_categories',
									'label' => __('Categories', 'vp_textdomain'),
									'description' => __('MultiSelect field with WP Categories Data Source and **{{first}}** **{{last}}** default items.', 'vp_textdomain'),
									'items' => array(
										'data' => array(
											array(
												'source' => 'function',
												'value' => 'vp_get_categories',
											),
										),
									),
									'default' => array(
										'{{first}}',
										'{{last}}',
									),
								),
								array(
									'type' => 'select',
									'name' => 'se_pages',
									'label' => __('Pages', 'vp_textdomain'),
									'description' => __('Select field with WP Pages Data Source', 'vp_textdomain'),
									'items' => array(
										'data' => array(
											array(
												'source' => 'function',
												'value' => 'vp_get_pages',
											),
										),
									),
								),
								array(
									'type' => 'checkbox',
									'name' => 'cb_users',
									'label' => __('Users Data Source', 'vp_textdomain'),
									'description' => __('Checkbox field with WP Users Data Source and **{{all}}** default items.', 'vp_textdomain'),
									'items' => array(
										'data' => array(
											array(
												'source' => 'function',
												'params' => 'admin',
												'value' => 'vp_get_users',
											),
										),
									),
									'default' => array(
										'{{all}}',
									),
								),
								array(
									'type' => 'radiobutton',
									'name' => 'rb_roles',
									'label' => __('Roles', 'vp_textdomain'),
									'description' => __('RadioButton field with WP Roles Data Source and **{{last}}** default item.', 'vp_textdomain'),
									'items' => array(
										'data' => array(
											array(
												'source' => 'function',
												'value' => 'vp_get_roles',
											),
										),
									),
									'default' => array(
										'{{last}}',
									),
								),
								array(
									'type' => 'multiselect',
									'name' => 'ms_tags',
									'label' => __('Tags', 'vp_textdomain'),
									'description' => __('MultiSelect field with WP Tags Data Source and **{{first}}** default items.', 'vp_textdomain'),
									'items' => array(
										'data' => array(
											array(
												'source' => 'function',
												'value' => 'vp_get_tags',
											),
										),
									),
									'default' => array(
										'{{first}}',
									),
								),
								array(
									'type' => 'select',
									'name' => 'se_posts',
									'label' => __('Posts', 'vp_textdomain'),
									'description' => __('Select field with WP Post Data Source', 'vp_textdomain'),
									'items' => array(
										'data' => array(
											array(
												'source' => 'function',
												'value' => 'vp_get_posts',
											),
										),
									),
								),
							),
						),
					),
				),
				array(
					'title' => __('Binding', 'vp_textdomain'),
					'name' => 'binding_data_source',
					'icon' => 'font-awesome:icon-link',
					'controls' => array(
						array(
							'type' => 'section',
							'title' => __('Choose Font', 'vp_textdomain'),
							'fields' => array(
								array(
									'type' => 'select',
									'name' => 'logo_font_face',
									'label' => __('Logo Font Face', 'vp_textdomain'),
									'description' => __('Select Font', 'vp_textdomain'),
									'items' => array(
										'data' => array(
											array(
												'source' => 'function',
												'value' => 'vp_get_gwf_family',
											),
										),
									),
								),
								array(
									'type' => 'radiobutton',
									'name' => 'logo_font_style',
									'label' => __('Logo Font Style', 'vp_textdomain'),
									'description' => __('Select Font Style', 'vp_textdomain'),
									'items' => array(
										'data' => array(
											array(
												'source' => 'binding',
												'field' => 'logo_font_face',
												'value' => 'vp_get_gwf_style',
											),
										),
									),
									'default' => array(
										'{{first}}',
									),
								),
								array(
									'type' => 'radiobutton',
									'name' => 'logo_font_weight',
									'label' => __('Logo Font Weight', 'vp_textdomain'),
									'description' => __('Select Font Weight', 'vp_textdomain'),
									'items' => array(
										'data' => array(
											array(
												'source' => 'binding',
												'field' => 'logo_font_face',
												'value' => 'vp_get_gwf_weight',
											),
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
			'title' => __('Fields Interactions', 'vp_textdomain'),
			'name' => 'fields_interactions',
			'icon' => 'font-awesome:icon-exchange',
			'menus' => array(
				array(
					'name' => 'binding_field',
					'title' => __('Binding Field', 'vp_textdomain'),
					'icon' => 'font-awesome:icon-retweet',
					'controls' => array(
						array(
							'type' => 'section',
							'title' => __('Color Set', 'vp_textdomain'),
							'fields' => array(
								array(
									'type' => 'select',
									'name' => 'color_preset',
									'label' => __('Color Preset', 'vp_textdomain'),
									'default' => 'red',
									'items' => array(
										array(
											'value' => 'red',
											'label' => __('Red', 'vp_textdomain'),
										),
										array(
											'value' => 'green',
											'label' => __('Green', 'vp_textdomain'),
										),
										array(
											'value' => 'blue',
											'label' => __('Blue', 'vp_textdomain'),
										),
									),
								),
								array(
									'type' => 'color',
									'name' => 'color_accent',
									'label' => __('Color Accent', 'vp_textdomain'),
									'binding' => array(
										'field' => 'color_preset',
										'function' => 'vp_bind_color_accent',
									),
								),
								array(
									'type' => 'color',
									'name' => 'color_subtle',
									'label' => __('Color Subtle', 'vp_textdomain'),
									'binding' => array(
										'field' => 'color_preset',
										'function' => 'vp_bind_color_subtle',
									),
								),
								array(
									'type' => 'color',
									'name' => 'color_background',
									'label' => __('Color Background', 'vp_textdomain'),
									'binding' => array(
										'field' => 'color_preset',
										'function' => 'vp_bind_color_background',
									),
								),
							),
						),
					),
				),
				array(
					'name' => 'dependent_field',
					'title' => __('Dependent Field', 'vp_textdomain'),
					'icon' => 'font-awesome:icon-key',
					'controls' => array(
						array(
							'type' => 'section',
							'title' => __('Decider', 'vp_textdomain'),
							'name' => 'section_custom_font_decider',
							'fields' => array(
								array(
									'type' => 'toggle',
									'name' => 'use_custom_font',
									'label' => __('Use Custom Font', 'vp_textdomain'),
									'description' => __('Use custom font or not', 'vp_textdomain'),
								),
							),
						),
						array(
							'type' => 'section',
							'title' => __('Custom Font', 'vp_textdomain'),
							'name' => 'section_custom_font',
							'dependency' => array(
								'field' => 'use_custom_font',
								'function' => 'vp_dep_boolean',
							),
							'fields' => array(
								array(
									'type' => 'select',
									'name' => 'dep_font_face',
									'label' => __('Font Face', 'vp_textdomain'),
									'description' => __('Select Font', 'vp_textdomain'),
									'items' => array(
										'data' => array(
											array(
												'source' => 'function',
												'value' => 'vp_get_gwf_family',
											),
										),
									),
								),
								array(
									'type' => 'radiobutton',
									'name' => 'dep_font_style',
									'label' => __('Font Style', 'vp_textdomain'),
									'description' => __('Select Font Style', 'vp_textdomain'),
									'items' => array(
										'data' => array(
											array(
												'source' => 'binding',
												'field' => 'dep_font_face',
												'value' => 'vp_get_gwf_style',
											),
										),
									),
									'default' => array(
										'{{first}}',
									),
								),
								array(
									'type' => 'radiobutton',
									'name' => 'dep_font_weight',
									'label' => __('Font Weight', 'vp_textdomain'),
									'description' => __('Select Font Weight', 'vp_textdomain'),
									'items' => array(
										'data' => array(
											array(
												'source' => 'binding',
												'field' => 'dep_font_face',
												'value' => 'vp_get_gwf_weight',
											),
										),
									),
								),
							),
						),
						array(
							'type' => 'section',
							'title' => __('Single Field dependency', 'vp_textdomain'),
							'name' => 'section_single_field_deps',
							'fields' => array(
								array(
									'type' => 'toggle',
									'name' => 'use_custom_logo',
									'label' => __('Use Custom Logo', 'vp_textdomain'),
									'description' => __('Use custom logo or not', 'vp_textdomain'),
								),
								array(
									'type' => 'upload',
									'name' => 'custom_logo',
									'label' => __('Custom Logo', 'vp_textdomain'),
									'dependency' => array(
										'field' => 'use_custom_logo',
										'function' => 'vp_dep_boolean',
									),
									'description' => __('Upload or choose custom logo', 'vp_textdomain'),
								),
							),
						),
					),
				),
			),
		),
		array(
			'name' => 'notebox',
			'title' => __('Notebox', 'vp_textdomain'),
			'icon' => 'font-awesome:icon-info-sign',
			'controls' => array(
				array(
					'type' => 'notebox',
					'name' => 'nb_1',
					'label' => __('Normal Announcement', 'vp_textdomain'),
					'description' => __('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas', 'vp_textdomain'),
					'status' => 'normal',
				),
				array(
					'type' => 'notebox',
					'name' => 'nb_2',
					'label' => __('Info Announcement', 'vp_textdomain'),
					'description' => __('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas', 'vp_textdomain'),
					'status' => 'info',
				),
				array(
					'type' => 'notebox',
					'name' => 'nb_3',
					'label' => __('Success Announcement', 'vp_textdomain'),
					'description' => __('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas', 'vp_textdomain'),
					'status' => 'success',
				),
				array(
					'type' => 'notebox',
					'name' => 'nb_4',
					'label' => __('Warning Announcement', 'vp_textdomain'),
					'description' => __('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas', 'vp_textdomain'),
					'status' => 'warning',
				),
				array(
					'type' => 'notebox',
					'name' => 'nb_5',
					'label' => __('Critical Announcement', 'vp_textdomain'),
					'description' => __('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas', 'vp_textdomain'),
					'status' => 'error',
				),
				array(
					'type' => 'section',
					'title' => __('Notebox in a Section', 'vp_textdomain'),
					'name' => 'section_notebox',
					'fields' => array(
						array(
							'type' => 'notebox',
							'name' => 'nb_6',
							'label' => __('Normal Announcement', 'vp_textdomain'),
							'description' => __('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas', 'vp_textdomain'),
							'status' => 'normal',
						),
						array(
							'type' => 'notebox',
							'name' => 'nb_7',
							'label' => __('Info Announcement', 'vp_textdomain'),
							'description' => __('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas', 'vp_textdomain'),
							'status' => 'info',
						),
						array(
							'type' => 'notebox',
							'name' => 'nb_8',
							'label' => __('Success Announcement', 'vp_textdomain'),
							'description' => __('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas', 'vp_textdomain'),
							'status' => 'success',
						),
						array(
							'type' => 'notebox',
							'name' => 'nb_9',
							'label' => __('Warning Announcement', 'vp_textdomain'),
							'description' => __('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas', 'vp_textdomain'),
							'status' => 'warning',
						),
						array(
							'type' => 'notebox',
							'name' => 'nb_10',
							'label' => __('Critical Announcement', 'vp_textdomain'),
							'description' => __('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas', 'vp_textdomain'),
							'status' => 'error',
						),
					),
				),
				array(
					'type' => 'section',
					'title' => __('Notebox with Fields', 'vp_textdomain'),
					'name' => 'section_notebox',
					'fields' => array(
						array(
							'type' => 'notebox',
							'name' => 'nb_11',
							'label' => __('Info Announcement', 'vp_textdomain'),
							'description' => __('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas', 'vp_textdomain'),
							'status' => 'info',
						),
						array(
							'type' => 'textbox',
							'name' => 'tb_6',
							'label' => __('Textbox', 'vp_textdomain'),
							'default' => '',
						),
						array(
							'type' => 'textbox',
							'name' => 'tb_7',
							'label' => __('Textbox', 'vp_textdomain'),
							'default' => '',
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