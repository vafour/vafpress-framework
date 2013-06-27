<?php

return array(
	'id'          => 'vp_meta_sample_1',
	'types'       => array('post'),
	'title'       => __('VP Dependent Fields and Group', 'vp_textdomain'),
	'priority'    => 'high',
	'template'    => array(
		array(
			'type' => 'toggle',
			'name' => 'toggle_filtering',
			'label' => __('Use Filtering', 'vp_textdomain'),
			'description' => __('Checking this will show filtering option group.', 'vp_textdomain'),
		),
		array(
			'type'      => 'group',
			'repeating' => false,
			'length'    => 1,
			'name'      => 'filtering_group',
			'title'     => __('Filtering', 'vp_textdomain'),
			'dependency' => array(
				'field'    => 'toggle_filtering',
				'function' => 'vp_dep_boolean',
			),
			'fields'    => array(
				array(
					'type' => 'radiobutton',
					'name' => 'filter_type',
					'label' => __('Filter By', 'vp_textdomain'),
					'description' => __('Different type will show different type of field', 'vp_textdomain'),
					'items' => array(
						array(
							'value' => 'keyword',
							'label' => __('Keyword', 'vp_textdomain'),
						),
						array(
							'value' => 'tags',
							'label' => __('Tags', 'vp_textdomain'),
						),
					),
				),
				array(
					'type' => 'textbox',
					'name' => 'filter_keyword',
					'label' => __('Keyword', 'vp_textdomain'),
					'description' => __('Keyword to filter.', 'vp_textdomain'),
					'default' => 'abcdefg',
					'dependency' => array(
						'field'    => 'filter_type',
						'function' => 'vp_dep_is_keyword',
					),
				),
				array(
					'type' => 'multiselect',
					'name' => 'filter_tags',
					'label' => __('Choose Tag(s)', 'vp_textdomain'),
					'description' => __('Tag(s) to filter.', 'vp_textdomain'),
					'items' => array(
						'data' => array(
							array(
								'source' => 'function',
								'value'  => 'vp_get_tags',
							),
						),
					),
					'dependency' => array(
						'field'    => 'filter_type',
						'function' => 'vp_dep_is_tags',
					),
				),
			),
		),
	),
);

/**
 * EOF
 */