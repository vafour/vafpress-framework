<?php

return array(
	'id'          => 'vp_meta_sample_5',
	'types'       => array('post'),
	'title'       => __('VP Nested Group', 'vp_textdomain'),
	'priority'    => 'high',
	'template'    => array(
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
									'type' => 'textarea',
									'name' => 'content',
								),
							),
						),
					),
				),
			),
		),
	),
);

/**
 * EOF
 */