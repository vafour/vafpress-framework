<?php

return array(
	'id'          => 'vp_meta_sample_4',
	'types'       => array('post'),
	'title'       => __('VP All Notebox', 'vp_textdomain'),
	'priority'    => 'low',
	'template'    => array(
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
			'type'      => 'group',
			'repeating' => false,
			'length'    => 1,
			'name'      => 'notebox_group',
			'title'     => __('Noteboxes inside Group', 'vp_textdomain'),
			'fields'    => array(
				array(
					'type' => 'notebox',
					'name' => 'nb_5',
					'label' => __('Normal Announcement', 'vp_textdomain'),
					'description' => __('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas', 'vp_textdomain'),
					'status' => 'normal',
				),
				array(
					'type' => 'notebox',
					'name' => 'nb_6',
					'label' => __('Info Announcement', 'vp_textdomain'),
					'description' => __('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas', 'vp_textdomain'),
					'status' => 'info',
				),
				array(
					'type' => 'notebox',
					'name' => 'nb_7',
					'label' => __('Success Announcement', 'vp_textdomain'),
					'description' => __('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas', 'vp_textdomain'),
					'status' => 'success',
				),
				array(
					'type' => 'notebox',
					'name' => 'nb_8',
					'label' => __('Warning Announcement', 'vp_textdomain'),
					'description' => __('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas', 'vp_textdomain'),
					'status' => 'warning',
				),
				array(
					'type' => 'notebox',
					'name' => 'nb_9',
					'label' => __('Critical Announcement', 'vp_textdomain'),
					'description' => __('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas', 'vp_textdomain'),
					'status' => 'error',
				),
			),
		),
	),
);

/**
 * EOF
 */