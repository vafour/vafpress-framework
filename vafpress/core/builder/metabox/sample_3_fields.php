<?php

return array(
	'id'          => 'vp_meta_sample_3',
	'types'       => array('post'),
	'title'       => __('VP All Control Fields Demo', 'vp_textdomain'),
	'priority'    => 'high',
	'template'    => array(
		array(
			'type' => 'upload',
			'name' => 'up_1',
			'label' => __('Upload', 'vp_textdomain'),
			'description' => __('Built in WP media upload, upload any media', 'vp_textdomain'),
			'default' => 'http://lorempixel.com/500/400/animals/',
		),
		array(
			'type'        => 'codeeditor',
			'name'        => 'ce_1',
			'label'       => 'Custom CSS',
			'description' => 'Define your custom CSS',
			'mode'        => 'css',
			'theme'       => 'solarized_light',
		),
		array(
			'type'        => 'sorter',
			'name'        => 'so_1',
			'label'       => 'Sorter',
			'description' => 'Select and sort your choices',
			'items'       => array(
				array(
					'value' => 'item_1',
					'label' => __('Item 1', 'vp_textdomain'),
				),
				array(
					'value' => 'item_2',
					'label' => __('Item 2', 'vp_textdomain'),
				),
				array(
					'value' => 'item_3',
					'label' => __('Item 3', 'vp_textdomain'),
				),
				array(
					'value' => 'item_4',
					'label' => __('Item 4', 'vp_textdomain'),
				),
			),
			'default'     => array(
				'item_2',
				'item_4',
			),
		),
		array(
			'type' => 'checkbox',
			'name' => 'cb_1',
			'label' => __('Checkbox', 'vp_textdomain'),
			'description' => __('Normal Checkbox', 'vp_textdomain'),
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
			'type' => 'checkimage',
			'name' => 'ci_1',
			'label' => __('CheckImage', 'vp_textdomain'),
			'description' => __('Checkbox with image item', 'vp_textdomain'),
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
			'type' => 'radiobutton',
			'name' => 'rb_1',
			'label' => __('RadioButton', 'vp_textdomain'),
			'description' => __('Normal RadioButton', 'vp_textdomain'),
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
			'type' => 'radioimage',
			'name' => 'ri_1',
			'label' => __('RadioImage', 'vp_textdomain'),
			'description' => __('RadioButton with image item', 'vp_textdomain'),
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
			'type' => 'color',
			'name' => 'cl_2',
			'label' => __('ColorPicker', 'vp_textdomain'),
			'description' => __('ColorPicker using eyecon colorpicker library', 'vp_textdomain'),
			'default' => '#98ed28',
			'format' => 'rgb'
		),
		array(
			'type' => 'date',
			'name' => 'dt_1',
			'label' => __('Date Picker', 'vp_textdomain'),
			'description' => __('DatePicker using jQuery UI', 'vp_textdomain'),
			'format' => 'yy-mm-dd',
			'default' => '2012-12-12',
		),
		array(
			'type' => 'radiobutton',
			'name' => 'rb_roles',
			'label' => __('Select Role', 'vp_textdomain'),
			'description' => __('RadioButton field with WP Roles Data Source and **{{last}}** default item.', 'vp_textdomain'),
			'items' => array(
				'data' => array(
					array(
						'source' => 'function',
						'value'  => 'vp_get_roles',
					),
				),
			),
			'default' => array(
				'{{last}}',
			),
		),
		array(
			'type' => 'select',
			'name' => 'se_pages',
			'label' => __('Select Page', 'vp_textdomain'),
			'description' => __('Select field with WP Pages Data Source', 'vp_textdomain'),
			'items' => array(
				'data' => array(
					array(
						'source' => 'function',
						'value'  => 'vp_get_pages',
					),
				),
			),
		),
		array(
			'type' => 'toggle',
			'name' => 'tg_1',
			'label' => __('Toggle', 'vp_textdomain'),
			'description' => __('Yes or No question', 'vp_textdomain'),
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
			'type' => 'multiselect',
			'name' => 'ms_categories',
			'label' => __('Choose Categorie(s)', 'vp_textdomain'),
			'description' => __('MultiSelect field with WP Categories Data Source and **{{first}}** **{{last}}** default items.', 'vp_textdomain'),
			'items' => array(
				'data' => array(
					array(
						'source' => 'function',
						'value'  => 'vp_get_categories',
					),
				),
			),
			'default' => array(
				'{{first}}',
				'{{last}}',
			),
		),
		array(
			'type' => 'textarea',
			'name' => 'ta_1',
			'label' => __('Textarea', 'vp_textdomain'),
			'description' => __('Everytime you need long text.', 'vp_textdomain'),
			'height' => '300',
			'default' => 'lorem ipsum',
		),
	),
);

/**
 * EOF
 */