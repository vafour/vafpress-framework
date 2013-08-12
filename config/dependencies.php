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
		'always' => array('jquery', 'scrollspy', 'tipsy', 'jquery-typing'),
		'paths' => array(
			'jquery' => array(
				'path'     => VP_PUBLIC_URL . '/js/vendor/jquery-1.8.3.min.js',
				'deps'     => array(),
				'ver'      => '1.8.3',
				'override' => false,
			),
			'bootstrap-colorpicker' => array(
				'path'     => VP_PUBLIC_URL . '/js/vendor/bootstrap-colorpicker.js',
				'deps'     => array('jquery'),
				'ver'      => false,
			),
			'tipsy' => array(
				'path'     => VP_PUBLIC_URL . '/js/vendor/jquery.tipsy.js',
				'deps'     => array('jquery'),
				'ver'      => '1.0.0a'
			),
			'scrollspy' => array(
				'path'     => VP_PUBLIC_URL . '/js/vendor/jquery-scrollspy.js',
				'deps'     => array('jquery'),
				'ver'      => false,
			),
			'jquery-ui-core' => array(
				'path'     => VP_PUBLIC_URL . '/js/vendor/jquery.ui.core.js',
				'deps'     => array(),
				'ver'      => '1.9.2',
			),
			'jquery-ui-widget' => array(
				'path'     => VP_PUBLIC_URL . '/js/vendor/jquery.ui.widget.js',
				'deps'     => array(),
				'ver'      => '1.9.2',
			),
			'jquery-ui-mouse' => array(
				'path'     => VP_PUBLIC_URL . '/js/vendor/jquery.ui.mouse.js',
				'deps'     => array('jquery-ui-widget'),
				'ver'      => '1.9.2',
			),
			'jquery-ui-slider' => array(
				'path'     => VP_PUBLIC_URL . '/js/vendor/jquery.ui.slider.js',
				'deps'     => array('jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-mouse'),
				'ver'      => '1.9.2',
			),
			'jquery-ui-datepicker' => array(
				'path'     => VP_PUBLIC_URL . '/js/vendor/jquery.ui.datepicker.js',
				'deps'     => array('jquery', 'jquery-ui-core', 'jquery-ui-widget'),
				'ver'      => '1.9.2',
			),
			'jquery-typing' => array(
				'path'     => VP_PUBLIC_URL . '/js/vendor/jquery.typing-0.2.0.min.js',
				'deps'     => array('jquery'),
				'ver'      => '0.2',
			),
			'ace-editor' => array(
				'path'     => VP_PUBLIC_URL . '/js/vendor/ace/ace.js',
				'deps'     => array(),
				'ver'      => '1.0.0',
			),
			'select2' => array(
				'path'     => VP_PUBLIC_URL . '/js/vendor/select2.min.js',
				'deps'     => array('jquery'),
				'ver'      => '3.3.2',
				'override' => true,
			),
			'select2-sortable' => array(
				'path'     => VP_PUBLIC_URL . '/js/vendor/select2.sortable.js',
				'deps'     => array('jquery', 'jquery-ui-sortable', 'select2'),
				'ver'      => '1.0.0',
				'override' => true,
			),
			'reveal' => array(
				'path'     => VP_PUBLIC_URL . '/js/vendor/jquery.reveal.js',
				'deps'     => array('jquery'),
				'ver'      => '1.0.0',
			),
			'kia-metabox' => array(
				'path'     => VP_PUBLIC_URL . '/js/kia-metabox.js',
				'deps'     => array('jquery', 'editor'),
				'ver'      => '1.0',
				'override' => true,
			),
			'shared' => array(
				'path'     => VP_PUBLIC_URL . '/js/shared.min.js',
				'deps'     => array(),
				'ver'      => '1.1',
				'localize' => array(
					'name' => 'vp_wp',
					'keys' => array(
						'use_upload', 'use_new_media_upload', 'public_url', 'wp_include_url', 'val_msg'
					)
				)
			),
			'vp-option' => array(
				'path'     => VP_PUBLIC_URL . '/js/option.min.js',
				'deps'     => array(),
				'ver'      => '2.0',
				'localize' => array(
					'name' => 'vp_opt',
					'keys' => array(
						'util_msg', 'nonce'
					)
				)
			),
			'vp-metabox' => array(
				'path'     => VP_PUBLIC_URL . '/js/metabox.min.js',
				'deps'     => array(),
				'ver'      => '2.0',
				'localize' => array(
					'name' => 'vp_mb',
					'keys' => array(
						'use_upload', 'use_new_media_upload'
					)
				)
			),
			'vp-shortcode-qt' => array(
				'path'     => VP_PUBLIC_URL . '/js/shortcode-quicktags.js',
				'deps'     => array('reveal'),
				'ver'      => '1.0.0',
			),
			'vp-shortcode' => array(
				'path'     => VP_PUBLIC_URL . '/js/shortcode-menu.js',
				'deps'     => array('reveal', 'vp-shortcode-qt'),
				'ver'      => '2.0',
				'localize' => array(
					'name' => 'vp_ext_sc',
					'keys' => array(
						'use_upload', 'use_new_media_upload', 'public_url'
					)
				)
			),
		),
	),

	/**
	 * Styles.
	 */
	'styles' => array(
		'always' => array('tipsy', 'font-awesome', 'font-awesome-ie7'),
		'paths' => array(
			'bootstrap-colorpicker' => array(
				'path' => VP_PUBLIC_URL . '/css/vendor/bootstrap-colorpicker.css',
				'deps' => array(),
			),
			'tipsy' => array(
				'path' => VP_PUBLIC_URL . '/css/vendor/tipsy.css',
				'deps' => array(),
			),
			'jqui' => array(
				'path' => VP_PUBLIC_URL . '/css/vendor/jqueryui/themes/' . $jqui_theme . '/jquery-ui-1.9.2.custom.min.css',
				'deps' => array(),
			),
			'font-awesome-ie7' => array(
				'path' => VP_PUBLIC_URL . '/css/vendor/font-awesome-ie7.min.css',
				'deps' => array(),
			),
			'font-awesome' => array(
				'path' => VP_PUBLIC_URL . '/css/vendor/font-awesome.min.css',
				'deps' => array(),
			),
			'select2' => array(
				'path' => VP_PUBLIC_URL . '/css/vendor/select2.css',
				'deps' => array(),
			),
			'reveal' => array(
				'path' => VP_PUBLIC_URL . '/css/vendor/reveal.css',
				'deps' => array(),
			),
			'vp-option' => array(
				'path' => VP_PUBLIC_URL . '/css/option.min.css',
				'deps' => array(),
			),
			'vp-metabox' => array(
				'path' => VP_PUBLIC_URL . '/css/metabox.min.css',
				'deps' => array(),
			),
			'vp-shortcode' => array(
				'path' => VP_PUBLIC_URL . '/css/shortcode.min.css',
				'deps' => array('reveal'),
			),
		),
	),

	/**
	 * Rules for dynamic loading of dependencies, load only what needed.
	 */
	'rules'   => array(
		'color'       => array( 'js' => array('bootstrap-colorpicker'), 'css' => array('bootstrap-colorpicker') ),
		'select'      => array( 'js' => array('select2'), 'css' => array('select2') ),
		'multiselect' => array( 'js' => array('select2'), 'css' => array('select2') ),
		'slider'      => array( 'js' => array('jquery-ui-slider'), 'css' => array('jqui') ),
		'date'        => array( 'js' => array('jquery-ui-datepicker'), 'css' => array('jqui') ),
		'codeeditor'  => array( 'js' => array('ace-editor'), 'css' => array() ),
		'sorter'      => array( 'js' => array('select2-sortable'), 'css' => array('select2', 'jqui') ),
		'fontawesome' => array( 'js' => array('select2'), 'css' => array('select2') ),
		'wpeditor'    => array( 'js' => array('kia-metabox'), 'css' => array() ),
	)

);

/**
 * EOF
 */