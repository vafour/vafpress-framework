<?php

return array(

	////////////////////////////////////////////////
	// Scripts and Styles Dependencies Definition //
	////////////////////////////////////////////////

	/**
	 * jQuery UI Theme
	 */
	'jqui_theme' => ($jqui_theme = 'smoothness'),

	/**
	 * Scripts.
	 */
	'scripts' => array(
		'always' => array('jquery', 'prefixfree-js', 'scrollspy-js', 'tipsy-js'),
		'paths' => array(
			array(
				'name' => 'colorpicker-js',
				'path' => VP_PUBLIC_URL . '/js/vendor/colorpicker.js',
				'deps' => array('jquery')
			),
			array(
				'name' => 'tipsy-js',
				'path' => VP_PUBLIC_URL . '/js/vendor/jquery.tipsy.js',
				'deps' => array('jquery')
			),
			array(
				'name' => 'chosen-js',
				'path' => VP_PUBLIC_URL . '/js/vendor/chosen.jquery.min.js',
				'deps' => array('jquery')
			),
			array(
				'name' => 'prefixfree-js',
				'path' => VP_PUBLIC_URL . '/js/vendor/prefixfree.min.js',
				'deps' => array('jquery')
			),
			array(
				'name' => 'scrollspy-js',
				'path' => VP_PUBLIC_URL . '/js/vendor/jquery-scrollspy.js',
				'deps' => array('jquery')
			),
		),
	),

	/**
	 * Styles.
	 */
	'styles' => array(
		'always' => array('tipsy-css'),
		'paths' => array(
			array(
				'name' => 'colorpicker-css',
				'path' => VP_PUBLIC_URL . '/css/vendor/colorpicker.css',
				'deps' => array()
			),
			array(
				'name' => 'tipsy-css',
				'path' => VP_PUBLIC_URL . '/css/vendor/tipsy.css',
				'deps' => array()
			),
			array(
				'name' => 'chosen-css',
				'path' => VP_PUBLIC_URL . '/css/vendor/chosen.css',
				'deps' => array()
			),
			array(
				'name' => 'jqui',
				'path' => VP_PUBLIC_URL . '/css/vendor/jqueryui/themes/' . $jqui_theme . '/jquery-ui-1.9.2.custom.min.css',
				'deps' => array()
			),
		),
	),

	/**
	 * Rules for dynamic loading of dependancies, load only what needed.
	 */
	'rules'   => array(
		'color'       => array( 'js' => array('colorpicker-js'), 'css' => array('colorpicker-css') ),
		'select'      => array( 'js' => array('chosen-js'), 'css' => array('chosen-css') ),
		'multiselect' => array( 'js' => array('chosen-js'), 'css' => array('chosen-css') ),
		'slider'      => array( 'js' => array('jquery-ui-slider'), 'css' => array('jqui') ),
		'date'        => array( 'js' => array('jquery-ui-datepicker'), 'css' => array('jqui') ),
	)

);

/**
 * EOF
 */