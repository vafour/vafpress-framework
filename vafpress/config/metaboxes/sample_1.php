<?php

return array(
	'name'        => 'vp_meta_sample_1',
	'types'       => array('post'),
	'title'       => __('VP Metabox 1', 'vp_textdomain'),
	'description' => __('Sample metabox powered by WPAlchemy', 'vp_textdomain'),
	'fields'      => array(
		array(
			'type' => 'group',
			'name' => 'tb_1_g',
			'fields' => array(
				array(
					'type' => 'textbox',
					'name' => 'tb_1_name',
					'label' => __('Alphabet', 'vp_textdomain'),
					'description' => __('Only alphabets allowed here.', 'vp_textdomain'),
					'default' => 'abcdefg',
					'validation' => 'alphabet',
				),
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
			),
		),
	),
);

/**
 * EOF
 */