<?php

class VP_WP_Loader
{

	private static $_instance;

	private $_js_data = array();

	private $_css_data = array();

	private $_localize = array();

	private $_scripts;

	private $_styles;

	private $_dependencies;

	private $_use_media_upload = false;

	private $_use_wp_35_media_upload = false;

	private $_types;

	public static function instance()
	{
		if (is_null(self::$_instance))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct()
	{
		$this->_dependencies = apply_filters( 'vp_dependencies_array', VP_Util_Config::instance()->load('dependencies') );
		$this->_types        = array(
			'option'             => array(),
			'metabox'            => array(),
			'shortcodegenerator' => array(),
		);
	}

	public function build()
	{

		// get scripts and styles dependencies configs
		$req_scripts = $this->_dependencies['scripts']['always'];
		$req_styles  = $this->_dependencies['styles']['always'];
		$scripts     = $this->_dependencies['scripts']['paths'];
		$styles      = $this->_dependencies['styles']['paths'];
		$rules       = $this->_dependencies['rules'];
		$types       = $this->get_flat_types();

		// for all types build required scripts and styles array
		foreach ($types as $type)
		{
			if( array_key_exists($type, $rules) )
			{
				$req_scripts = array_merge($req_scripts, $rules[$type]['js']);
				$req_styles  = array_merge($req_styles, $rules[$type]['css']);
			}
		}

		// also determine whether to use media upload and the WP35 version or not
		if( in_array('upload', $types) )
		{
			global $wp_version;
			$this->_use_media_upload = true;
			if (!version_compare($wp_version, '3.5', '<'))
			{
				$this->_use_wp_35_media_upload = true;
				wp_enqueue_media();
			}
		}

		// build localize data
		$this->build_localize_data();

		// register all depended js
		foreach ($req_scripts as $script)
		{
			$this->js_unit_register($script);
		}

		// register and add shared-js at the end of dependencies
		$this->js_unit_register('shared', $req_scripts);

		// register all styles
		foreach ($styles as $name => $style) 
		{
			if(in_array($name, $req_styles) and ! wp_style_is($name, 'registered'))
				wp_register_style($name, $style['path'], $style['deps']);
		}

		// register all mains
		foreach ($this->_js_data as $name => $js)
		{
			// build main js localize
			$localize = array();
			foreach ($js['local_data'] as $datum)
			{
				if(array_key_exists($datum, $this->_localize))
				{
					$localize[$datum] = $this->_localize[$datum];
				}
			}

			if( isset($js['custom_local']) )
			{
				$localize = array_merge( $localize, $js['custom_local'] );
			}

			$deps   = array();
			if( isset($js['deps']) ) $deps = $js['deps'];
			$deps[] = 'shared';

			foreach ($deps as $dep)
			{
				$this->js_unit_register($dep);
			}

			// register, enqueue and localized scripts
			wp_register_script($name, $js['path'], $deps, '', true);
			wp_localize_script($name, $js['local_name'], $localize);
			wp_enqueue_script($name);
		}

		foreach ($this->_css_data as $name => $css)
		{
			foreach ($css['deps'] as $dep)
			{
				$this->css_unit_register($dep);
			}
			$req_styles = array_merge($req_styles, $css['deps']);
			wp_register_style($name, $css['path'], $req_styles);
			wp_enqueue_style($name);
		}

		do_action( 'vp_after_dependencies_loader_build' );

	}

	public function add_localize_data($key, $value)
	{
		$this->_localize[$key] = $value;
	}

	private function build_localize_data()
	{
		$messages = VP_Util_Config::instance()->load('messages');
		$localize = array(
			'use_upload'               => $this->_use_media_upload,
			'use_new_media_upload'     => $this->_use_wp_35_media_upload,
			'public_url'               => VP_PUBLIC_URL,
			'wp_include_url'           => includes_url(),
			'nonce'                    => wp_create_nonce( 'vafpress' ),
			'val_msg'                  => $messages['validation'],
			'util_msg'                 => $messages['util'],
			'ctrl_msg'                 => $messages['control'],
			// validatable data
			'alphabet_validatable'     => apply_filters( 'vp_alphabet_validatable'    , array( 'vp-textbox', 'vp-textarea' ) ),
			'alphanumeric_validatable' => apply_filters( 'vp_alphanumeric_validatable', array( 'vp-textbox', 'vp-textarea' ) ),
			'numeric_validatable'      => apply_filters( 'vp_numeric_validatable'     , array( 'vp-textbox', 'vp-textarea' ) ),
			'email_validatable'        => apply_filters( 'vp_email_validatable'       , array( 'vp-textbox', 'vp-textarea' ) ),
			'url_validatable'          => apply_filters( 'vp_url_validatable'         , array( 'vp-textbox', 'vp-textarea' ) ),
			'maxlength_validatable'    => apply_filters( 'vp_maxlength_validatable'   , array( 'vp-toggle', 'vp-radiobutton', 'vp-radioimage', 'vp-select' ) ),
			'minlength_validatable'    => apply_filters( 'vp_minlength_validatable'   , array( 'vp-toggle', 'vp-radiobutton', 'vp-radioimage', 'vp-select' ) ),
		);
		$this->_localize = array_merge($this->_localize, $localize);
	}

	private function js_unit_register($name, $extra_deps = null)
	{
		global $wp_scripts;

		$scripts = $this->_dependencies['scripts']['paths'];

		if( isset($scripts[$name]) )
		{

			$registered = wp_script_is($name, 'registered');
			$is_older   = false;
			$script     = $scripts[$name];
			$override   = isset($script['override']) ? $script['override'] : false;
			if( $registered )
			{
				$is_older = version_compare($script['ver'], $wp_scripts->registered[$name]->ver) == 1;
			}
			if( !$registered or ($is_older and $override) )
			{
				if( !is_null($extra_deps) )
				{
					$script['deps'] = array_unique( array_merge( $script['deps'], $extra_deps ) );
				}
				if( !empty($script['deps']) )
				{
					foreach ($script['deps'] as $dep)
					{
						$this->js_unit_register($dep);
					}
				}
				if( $is_older )
				{
					wp_deregister_script($name);
				}

				wp_register_script($name, $script['path'], $script['deps'], $script['ver'], true);

				if(isset($script['localize']))
				{
					$localize = array();
					foreach ($script['localize']['keys'] as $key)
					{
						if(array_key_exists($key, $this->_localize))
						{
							$localize[$key] = $this->_localize[$key];
						}
					}
					wp_localize_script($name, $script['localize']['name'], $localize);
				}
			}
		}
	}

	private function css_unit_register($name, $extra_deps = null)
	{
		$styles = $this->_dependencies['styles']['paths'];

		if( isset($styles[$name]) )
		{
			$style = $styles[$name];

			if( !is_null($extra_deps) )
			{
				$style['deps'] = array_unique( array_merge( $style['deps'], $extra_deps ) );
			}
			if( !empty($style['deps']) )
			{
				foreach ($style['deps'] as $dep)
				{
					$this->css_unit_register($dep);
				}
			}
			wp_register_style($name, $style['path'], $style['deps'], isset($style['ver']) ? $style['ver'] : false);
		}
	}

	// how to setup the localization data?
	public function add_js_data($js_name, $key, $data)
	{
		$this->add_data($js_name, $key, $data, 'js');
	}

	public function add_css_data($css_name, $key, $data)
	{
		$this->add_data($css_name, $key, $data, 'css');
	}

	public function add_data($name, $key, $data, $type)
	{

		$array_data = array();

		if( $type === 'js' )
			$array_data = array('local_data');

		$var_name = '_' . $type . '_data';

		if( in_array($key, $array_data) )
		{
			if( !isset($this->{$var_name}[$name][$key]) || !is_array($this->{$var_name}[$name][$key]) )
				$this->{$var_name}[$name][$key] = array();
			
			$this->{$var_name}[$name][$key] = array_unique(
				array_merge(
					$this->{$var_name}[$name][$key],
					(array) $data
				)
			);
		}
		else
		{
			$keys = explode('.', $key);
			$arr  = &$this->{$var_name}[$name];
			foreach ($keys as $key)
			{
				$arr = &$arr[$key];
			}
			$arr = $data;
		}

	}

	// how to setup the main js and css data?
	public function add_main_css($css)
	{

		if( is_string($css) )
		{
			$css_name = $css;
			$deps     = $this->_dependencies['styles']['paths'];
			$css      = $deps[$css_name];
		}
		else
		{
			$css_name = $css['name'];
		}

		if( isset($css['deps']) )
			$this->add_css_data($css_name, 'deps', $css['deps']);

		if( isset($css['path']) )
			$this->add_css_data($css_name, 'path', $css['path']);
	}

	public function add_main_js($js)
	{

		if( is_string($js) )
		{
			$js_name = $js;
			$deps    = $this->_dependencies['scripts']['paths'];
			$js      = $deps[$js_name];
		}
		else
		{
			$js_name = $js['name'];
		}

		if( isset($js['localize']) and is_array($js['localize']) )
		{
			if( isset($js['localize']['name']) )
				$this->add_js_data($js_name, 'local_name', $js['localize']['name']);
			
			if( isset($js['localize']['keys']) and is_array($js['localize']['keys']) )
				$this->add_js_data($js_name, 'local_data', $js['localize']['keys']);
		}

		if( isset($js['path']) )
			$this->add_js_data($js_name, 'path', $js['path']);

		if( isset($js['deps']) )
			$this->add_js_data($js_name, 'deps', $js['deps']);
	}

	// option class added their types to this
	public function add_types($types, $key)
	{
		$types = (array) $types;
		$this->_types[$key] = array_unique( array_merge( $this->_types[$key], $types ) );
	}

	public function get_types($key = null)
	{
		if( is_null($key) )
			return $this->_types;
		else
			return $this->_types[$key];
	}

	public function get_flat_types()
	{
		$flat_types = array();
		foreach ($this->_types as $types)
		{
			$flat_types = array_unique( array_merge( $flat_types, $types ) );
		}
		return $flat_types;
	}

}

/**
 * EOF
 */