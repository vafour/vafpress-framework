<?php

return array(
	'id'          => 'vp_meta_sample_2',
	'types'       => array('post'),
	'title'       => __('VP Repeating Group With Binding Fields', 'vp_textdomain'),
	'priority'    => 'high',
	'template'    => array(
		array(
			'type'      => 'group',
			'repeating' => true,
			'name'      => 'binding_group',
			'title'     => __('Reference', 'vp_textdomain'),
			'fields'    => array(
				array(
					'type' => 'textbox',
					'name' => 'name',
					'label' => __('Name', 'vp_textdomain'),
					'description' => __('Source Name', 'vp_textdomain'),
				),
				array(
					'type' => 'textbox',
					'name' => 'url',
					'label' => __('URL', 'vp_textdomain'),
					'description' => __('Source URL', 'vp_textdomain'),
				),
				array(
					'type' => 'upload',
					'name' => 'image',
					'label' => __('Image', 'vp_textdomain'),
					'description' => __('Source Image', 'vp_textdomain'),
				),
				array(
					'type' => 'textbox',
					'name' => 'shortcode',
					'label' => __('Shortcode', 'vp_textdomain'),
					'description' => __('Shortcode', 'vp_textdomain'),
					'binding' => array(
						'field' => 'name,url, image',
						'function' => 'vp_simple_shortcode'
					)
				),
			),
		),
	),
);

/**
 * EOF
 */