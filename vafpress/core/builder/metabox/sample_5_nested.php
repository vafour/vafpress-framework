<?php

return array(
	'id'          => 'vp_meta_sample_5',
	'types'       => array('post'),
	'title'       => __('VP Nested Group', 'vp_textdomain'),
	'priority'    => 'high',
	'template'    => array(
		array(
			'name'  => 'use_pb',
			'label' => 'Use Page Builder',
			'type'  => 'toggle',
		),
		array(
			'type'      => 'group',
			'repeating' => true,
			'name'      => 'section',
			'title'     => __('Section', 'vp_textdomain'),
			'fields'    => array(
				array(
					'type'      => 'group',
					'repeating' => true,
					'name'      => 'row',
					'title'     => __('Row', 'vp_textdomain'),
					'fields'    => array(
						array(
							'type'      => 'group',
							'repeating' => true,
							'name'      => 'column',
							'title'     => __('Column', 'vp_textdomain'),
							'fields'    => array(
								array(
									'type'                       => 'wpeditor',
									'label'                      => __('Content', 'vp_textdomain'),
									'name'                       => 'content',
									'use_external_plugins'       => 1,
									'disabled_externals_plugins' => 'vp_sc_button',
									'disabled_internals_plugins' => '',
									'validation'                 => 'required',
								),
							),
						),
					),
				),
			),
			'dependency' => array(
				'field'    => 'use_pb',
				'function' => 'vp_dep_boolean'
			)
		),
	),
);

/**
 * EOF
 */