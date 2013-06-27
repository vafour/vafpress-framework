<?php

return array(
	'id'          => 'vp_meta_sample_6',
	'types'       => array('post'),
	'title'       => __('VP Fixed Group With Item Bindings Fields', 'vp_textdomain'),
	'priority'    => 'high',
	'template'    => array(
		array(
			'type'      => 'group',
			'repeating' => false,
			'length'    => 2,
			'name'      => 'binding_group',
			'title'     => __('Location', 'vp_textdomain'),
			'fields'    => array(
				array(
					'type' => 'select',
					'name' => 'big_continent',
					'label' => __('Big Continent', 'vp_textdomain'),
					'description' => __('Big Continent', 'vp_textdomain'),
					'items' => array(
						'data' => array(
							array(
								'source' => 'function',
								'value'  => 'vp_bind_bigcontinents',
							),
						),
					),
					'default' => '{{last}}',
				),
				array(
					'type' => 'radiobutton',
					'name' => 'continent',
					'label' => __('Continent', 'vp_textdomain'),
					'description' => __('Continent', 'vp_textdomain'),
					'items' => array(
						'data' => array(
							array(
								'source' => 'binding',
								'field'  => 'big_continent',
								'value'  => 'vp_bind_continents',
							),
						),
					),
				),
				array(
					'type' => 'select',
					'name' => 'country',
					'label' => __('Country', 'vp_textdomain'),
					'description' => __('Country', 'vp_textdomain'),
					'items' => array(
						'data' => array(
							array(
								'source' => 'binding',
								'field'  => 'continent',
								'value'  => 'vp_bind_countries',
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