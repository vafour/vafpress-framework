<?php

class VP_Option
{

	private $_option_key;

	private $_page_slug;

	private $_template;

	private $_is_dev_mode;

	private $_use_util_menu;

	private $_use_auto_group_naming;

	private $_role;

	private $_menu_page;

	private $_page_title;

	private $_menu_label;

	private $_layout;

	private $_options_set = NULL;
	
	private $_options = NULL;

	private $_hook_suffix;

	public static $pool;

	public function __construct(array $configs)
	{

		// merge configs with default value
		$configs = array_merge(array(
			'is_dev_mode'           => false,
			'use_auto_group_naming' => true,
			'use_util_menu'         => true,
			'minimum_role'          => 'edit_theme_options',
			'menu_page'             => 'themes.php',
			'layout'                => 'fixed',
			'page_title'            => __( 'Vafpress Options', 'vp_textdomain' ),
			'menu_label'            => __( 'Vafpress Options', 'vp_textdomain' ),
			'priority'              => 10,
		), $configs);

		// options config filter
		$configs = apply_filters('vp_option_configuration_array'  , $configs, $configs['option_key']);
		$configs = apply_filters('vp_option_configuration_array-' . $configs['option_key'], $configs);

		// extract the configs
		extract($configs);

		// check and set required configs
		if(isset($option_key)) $this->set_option_key($option_key);
		else throw new Exception(__( 'Option Key is required', 'vp_textdomain' ), 1);
		if(isset($template)) $this->set_template($template);
		else throw new Exception(__( 'Template Array/File is required', 'vp_textdomain' ), 1);
		if(isset($page_slug)) $this->set_page_slug($page_slug);
		else throw new Exception(__( 'Page Slug is required', 'vp_textdomain' ), 1);

		// swim in the pool
		self::$pool[$this->get_option_key()] = &$this;
		
		// check and set the remaining configs
		if(isset($menu_page))             $this->set_menu_page($menu_page);
		if(isset($is_dev_mode))           $this->is_dev_mode($is_dev_mode);
		if(isset($use_util_menu))         $this->use_util_menu($use_util_menu);
		if(isset($use_auto_group_naming)) $this->use_auto_group_naming($use_auto_group_naming);
		if(isset($minimum_role))          $this->set_minimum_role($minimum_role);
		if(isset($page_title))            $this->set_page_title($page_title);
		if(isset($layout))                $this->set_layout($layout);
		if(isset($menu_label))            $this->set_menu_label($menu_label);

		// add first_activation hook to save initial values to db
		add_action('vp_option_first_activation', array($this, 'initial_db_setup'));

		// check if option key not existed init data from default values
		$options = get_option( $this->get_option_key() );
		if( $options === FALSE )
		{
			do_action('vp_option_first_activation');
		}

		// init options from db and expose to the api
		$this->init_options_from_db();

		// setup ajax
		add_action('wp_ajax_vp_ajax_' . $this->get_option_key() . '_export_option', array($this, 'ajax_export_option'));
		add_action('wp_ajax_vp_ajax_' . $this->get_option_key() . '_import_option', array($this, 'ajax_import_option'));
		add_action('wp_ajax_vp_ajax_' . $this->get_option_key() . '_save'         , array($this, 'ajax_save'));
		add_action('wp_ajax_vp_ajax_' . $this->get_option_key() . '_restore'      , array($this, 'ajax_restore'));

		// register menu page
		add_action( 'admin_menu', array($this, 'register_menu_page'), $priority );
	}

	public static function get_pool()
	{
		return self::$pool;
	}

	public function init_options_from_db()
	{
		$options = get_option( $this->get_option_key() );
		if( $options !== FALSE )
		{
			$this->set_options($options);
		}
	}

	// register menu page as configured
	public function register_menu_page()
	{
		if( is_array( $this->get_menu_page() ) )
		{
			$menu_page = $this->get_menu_page();

			// check and set required configs
			if(!isset($menu_page['icon_url'])) $menu_page['icon_url'] = '';
			if(!isset($menu_page['position'])) $menu_page['position'] = null;

			$hook_suffix = add_menu_page(
				$this->get_page_title(),
				$this->get_menu_label(),
				$this->get_minimum_role(),
				$this->get_page_slug(),
				array($this, 'option_page_display'),
				$menu_page['icon_url'],
				$menu_page['position']
			);
		}
		else
		{
			$hook_suffix = add_submenu_page(
				$this->get_menu_page(),
				$this->get_page_title(),
				$this->get_menu_label(),
				$this->get_minimum_role(),
				$this->get_page_slug(),
				array($this, 'option_page_display')
			);
		}
		$this->set_hook_suffix($hook_suffix);

		// register option page load
		add_action( 'load-' . $this->get_hook_suffix(), array($this, 'setup') );
	}

	public function setup()
	{
		$this->init_options_set();
		$this->init_options();
		$this->enqueue_scripts_and_styles();
		// show dev mode notice
		if( $this->is_dev_mode() )
			add_action( 'admin_notices', array( $this, 'dev_mode_notice' ) );
	}

	public function dev_mode_notice()
	{
		VP_WP_Util::admin_notice(__("Development Mode is Active, options' values won't be saved into database.", 'vp_textdomain'), false);
	}

	public function enqueue_scripts_and_styles()
	{	
		$opt_loader = VP_WP_Loader::instance();
		$opt_loader->add_types( $this->get_field_types(), 'option' );
		$opt_loader->add_main_js( 'vp-option' );
		$opt_loader->add_main_css( 'vp-option' );
		$opt_loader->add_js_data( 'vp-option', 'custom_local.name', $this->_option_key );
		$opt_loader->add_js_data( 'vp-option', 'custom_local.SAVE_SUCCESS', VP_Option_Control_Set::SAVE_SUCCESS );
		$opt_loader->add_js_data( 'vp-option', 'custom_local.SAVE_NOCHANGES', VP_Option_Control_Set::SAVE_NOCHANGES );
		$opt_loader->add_js_data( 'vp-option', 'custom_local.SAVE_FAILED', VP_Option_Control_Set::SAVE_FAILED );
	}

	function save_and_reinit()
	{
		// do saving
		$result = $this->get_options_set()->save($this->get_option_key());

		// re-init $opt
		$this->init_options_from_db();

		// get the new $opt
		$opt = $this->get_options_set()->get_values();

		// save and re-init action hook
		do_action('vp_option_save_and_reinit', $opt, $result['status'], $this->get_option_key());

		// option key specific save and re-init action hook
		do_action('vp_option_save_and_reinit-' . $this->get_option_key(), $opt, $result['status']);

		return $result;
	}

	function ajax_save()
	{
		$result = $this->vp_verify_nonce();
		
		if($result['status'])
		{
			$this->init_options_set();
			$this->init_options();

			$option  = $_POST['option'];
			$nonce   = $_POST['nonce'];

			$option  = VP_Util_Array::unite( $option, 'name', 'value' );
			$option  = $this->get_options_set()->normalize_values($option);

			// stripslashes added by WP in $_GET / $_POST
			$option  = stripslashes_deep($option);

			// get old options from set
			$old_opt = $this->get_options_set()->get_values();

			$this->get_options_set()->populate_values($option, true);

			// get back options from set
			$opt = $this->get_options_set()->get_values();

			// before ajax save action hook
			do_action('vp_option_before_ajax_save', $opt);

			// save and re-init options
			$result = $this->save_and_reinit();

			// after ajax save action hook
			do_action('vp_option_after_ajax_save', $opt, $old_opt, $result['status'], $this->get_option_key());

			// option key specific after ajax save action hook
			do_action('vp_option_after_ajax_save-' . $this->get_option_key(), $opt, $old_opt, $result['status']);
		}

		if (ob_get_length()) ob_clean();
		header('Content-type: application/json');
		echo json_encode($result);
		die();
	}

	function ajax_restore()
	{
		$result = $this->vp_verify_nonce();
		
		if( $result['status'] )
		{
			$this->init_options_set();
			$set     = $this->get_options_set();
			$options = $set->get_defaults();

			// get old options from set
			$old_opt = $this->get_options_set()->get_values();

			// set options so that default value can be accessed in binding done in `setup`
			$this->set_options($options);

			// setup and process values
			$set->setup($options);

			// before ajax save action hook
			do_action('vp_option_before_ajax_restore', $options);

			// save and re-init options
			$result  = $this->save_and_reinit();

			$options = $this->get_options_set()->get_values();

			// after ajax restore action hook
			do_action('vp_option_after_ajax_restore', $options, $old_opt, $result['status'], $this->get_option_key());

			// after ajax restore action hook
			do_action('vp_option_after_ajax_restore-' . $this->get_option_key(), $options, $old_opt, $result['status']);
		}

		if (ob_get_length()) ob_clean();
		header('Content-type: application/json');
		echo json_encode($result);
		die();
	}

	function ajax_import_option()
	{
		global $vp_set, $vp_config;

		$options = null;
		$old_opt = null;

		$result = $this->vp_verify_nonce();
		
		if($result['status'])
		{
			$this->init_options_set();
			$this->init_options();

			$option = $_POST['option'];

			if(empty($option))
			{
				$result['status']  = false;
				$result['message'] = __("Can not be empty", 'vp_textdomain');
			}
			else
			{
				$option = json_decode(stripslashes($option), true);

				if( is_array($option) )
				{
					$set = $this->get_options_set();
					
					// get old options from set
					$old_opt = $this->get_options_set()->get_values();

					// populate new values
					$set->populate_values($option, false);

					// save and re-init options
					$result  = $this->save_and_reinit();

					// get new options
					$options = $this->get_options_set()->get_values();
				}
				else
				{
					$result['status']  = false;
					$result['message'] = __("Invalid data", 'vp_textdomain');
				}
			}
		}

		// after ajax import action hook
		do_action('vp_option_after_ajax_import', $options, $old_opt, $result['status'], $this->get_option_key());

		// after ajax import action hook
		do_action('vp_option_after_ajax_import-' . $this->get_option_key(), $options, $old_opt, $result['status']);

		if (ob_get_length()) ob_clean();
		header('Content-type: application/json');
		echo json_encode($result);
		die();
	}

	function ajax_export_option()
	{
		global $wpdb;

		$sr_options = null;
		$db_options = null;

		$result = $this->vp_verify_nonce();

		if($result['status'])
		{
			$db_options = get_option($this->get_option_key());
			$sr_options = json_encode($db_options);

			$result = array(
				'status' => true,
				'message'=> __("Successful", 'vp_textdomain'),
				'option' => $sr_options,
			);
		}

		// after ajax export action hook
		do_action('vp_option_after_ajax_export', $db_options, $sr_options, $result['status'], $this->get_option_key());

		// after ajax export action hook
		do_action('vp_option_after_ajax_export-' . $this->get_option_key(), $db_options, $sr_options, $result['status']);


		if (ob_get_length()) ob_clean();
		header('Content-type: application/json');
		echo json_encode($result);
		die();
	}

	function vp_verify_nonce()
	{
		$nonce  = $_POST['nonce'];
		$verify = check_ajax_referer('vafpress', 'nonce', false);
		if($verify)
		{
			$result['status']  = true;
			$result['message'] = __("Successful", 'vp_textdomain');	
		}
		else
		{
			$result['status']  = false;
			$result['message'] = __("Unverified Access", 'vp_textdomain');
		}
		return $result;
	}

	function initial_db_setup()
	{
		// init set and options
		$this->init_options();
		$set = $this->get_options_set();

		// get baked values from options set
		$opt = $set->get_values();

		// before db options db action hook
		do_action('vp_option_before_db_init', $opt);

		// save to db
		$result = $this->save_and_reinit();

		// after db options db action hook
		do_action('vp_option_after_db_init', $opt, $result['status'], $this->get_option_key());
		do_action('vp_option_after_db_init-' . $this->get_option_key(), $opt, $result['status']);
	}

	public function init_options()
	{
		$this->init_options_set();
		$set = $this->get_options_set();

		// try load option from DB
		$db_options = get_option($this->get_option_key());
		$default    = $set->get_defaults();
		if (!empty($db_options))
		{
			// unify, preserve option from DB but appends anything new from default
			$options = $db_options;
			$options = $options + $default;
		}
		else
		{
			$options = $set->get_defaults();
		}

		// If dev mode, always use default, no db interaction
		if($this->is_dev_mode())
			$options = $set->get_defaults();

		// set options so that default value can be accessed in binding done in `setup`
		$this->set_options($options);

		// setup and process values
		$set->setup($options);
		
	}

	public function init_options_set()
	{
		if(!is_null($this->get_options_set()))
			return;

		if( is_string($this->get_template()) and is_file($this->get_template()) )
			$template = include $this->get_template();
		else if(is_array($this->get_template()))
			$template = $this->get_template();
		else
			throw new Exception(__( 'Invalid template supplied', 'vp_textdomain' ), 1);

		$parser = new VP_Option_Parser();
		$set    = $parser->parse_array_options($template, $this->use_auto_group_naming());
		$set->set_layout($this->get_layout());

		// assign set object
		$this->set_options_set($set);

		if( $this->use_util_menu() )
		{
			// setup utility menu
			$util_menu = new VP_Option_Control_Group_Menu();
			$util_menu->set_title(__('Utility', 'vp_textdomain'));
			$util_menu->set_name('menu_util');
			$util_menu->set_icon('font-awesome:fa-ambulance');

			// setup restore default section
			$restore_section = new VP_Option_Control_Group_Section();
			$restore_section->set_title(__('Restore Default', 'vp_textdomain'));
			$restore_section->set_name('section_restore');

			// setup restore button
			$restore_button = new VP_Option_Control_Field_Restore();
			$restore_section->add_field($restore_button);

			// setup exim section
			$exim_section = new VP_Option_Control_Group_Section();
			$exim_section->set_title(__('Export/Import', 'vp_textdomain'));
			$exim_section->set_name('section_exim');

			// setup exim field
			$exim_field = new VP_Option_Control_Field_ImpExp();
			$exim_section->add_field($exim_field);

			// add exim section
			$util_menu->add_control($exim_section);

			$util_menu->add_control($restore_section);
			$set->add_menu($util_menu);
		}
	}

	public function option_page_display()
	{
		echo $this->get_options_set()->render();
	}

	public function get_field_types()
	{
		// $this->init_options_set();
		return $this->get_options_set()->get_field_types();
	}

	// @todo return `vp_option` like function
	public function create_get_option_helper()
	{

	}

	//////////////////////////////
	// GETTER AND SETTER CHUNKS //
	//////////////////////////////

	/**
	 * Get _hook_suffix
	 *
	 * @return String _hook_suffix
	 */
	public function get_hook_suffix()
	{
		return $this->_hook_suffix;
	}

	/**
	 * Set _hook_suffix
	 *
	 * @param String $_hook_suffix _hook_suffix
	 */
	public function set_hook_suffix($_hook_suffix)
	{
		$this->_hook_suffix = $_hook_suffix;
		return $this;
	}

	/**
	 * Get _template
	 *
	 * @return String _template
	 */
	public function get_template()
	{
		return $this->_template;
	}

	/**
	 * Set _template
	 *
	 * @param String $_template _template
	 */
	public function set_template($_template)
	{
		$this->_template = $_template;
		return $this;
	}

	/**
	 * Get _options
	 *
	 * @return String _options
	 */
	public function get_options()
	{
		return $this->_options;
	}
	
	/**
	 * Set _options
	 *
	 * @param String $_options _options
	 */
	public function set_options($_options)
	{
		$this->_options = $_options;
		return $this;
	}

	/**
	 * Get _options_set
	 *
	 * @return String _options_set
	 */
	public function get_options_set()
	{
		return $this->_options_set;
	}
	
	/**
	 * Set _options_set
	 *
	 * @param String $_options_set _options_set
	 */
	public function set_options_set($_options_set)
	{
		$this->_options_set = $_options_set;
		return $this;
	}

	/**
	 * Get _menu_page
	 *
	 * @return String _menu_page
	 */
	public function get_menu_page()
	{
		return $this->_menu_page;
	}
	
	/**
	 * Set _menu_page
	 *
	 * @param String $_menu_page _menu_page
	 */
	public function set_menu_page($_menu_page)
	{
		$this->_menu_page = $_menu_page;
		return $this;
	}

	/**
	 * Set _layout
	 *
	 * @return String _layout
	 */
	public function get_layout()
	{
		return $this->_layout;
	}
	
	/**
	 * Get _layout
	 *
	 * @param String $_layout _layout
	 */
	public function set_layout($_layout)
	{
		$this->_layout = $_layout;
		return $this;
	}

	/**
	 * Get _menu_page_slug
	 *
	 * @return String _menu_page_slug
	 */
	public function get_page_slug()
	{
		return $this->_page_slug;
	}
	
	/**
	 * Set _page_slug
	 *
	 * @param String $_page_slug _page_slug
	 */
	public function set_page_slug($_page_slug)
	{
		$this->_page_slug = $_page_slug;
		return $this;
	}

	/**
	 * Get _menu_label
	 *
	 * @return String _menu_label
	 */
	public function get_menu_label()
	{
		return $this->_menu_label;
	}
	
	/**
	 * Set _menu_label
	 *
	 * @param String $_menu_label _menu_label
	 */
	public function set_menu_label($_menu_label)
	{
		$this->_menu_label = $_menu_label;
		return $this;
	}

	/**
	 * Get _page_title value
	 *
	 * @return String _page_title
	 */
	public function get_page_title()
	{
		return $this->_page_title;
	}
	
	/**
	 * Set _page_title
	 *
	 * @param String $_page_title _page_title
	 */
	public function set_page_title($_page_title)
	{
		$this->_page_title = $_page_title;
		return $this;
	}

	/**
	 * Get _minimum_role value
	 *
	 * @return String $_minimum_role
	 */
	public function get_minimum_role()
	{
		return $this->_minimum_role;
	}
	
	/**
	 * Set _minimum_role value
	 *
	 * @param String $_minimum_role _minimum_role
	 */
	public function set_minimum_role($_minimum_role)
	{
		$this->_minimum_role = $_minimum_role;
		return $this;
	}

	/**
	 * Get _option_key value
	 *
	 * @return String $_option_key
	 */
	public function get_option_key()
	{
		return $this->_option_key;
	}
	
	/**
	 * Set _option_key value
	 *
	 * @param String $_option_key $_option_key
	 */
	public function set_option_key($_option_key)
	{
		$this->_option_key = $_option_key;
		return $this;
	}

	/**
	 * Get/Set whether to use auto group naming or not
	 *
	 * @return bool $_use_auto_group_naming
	 */
	public function use_auto_group_naming($_use_auto_group_naming = NULL)
	{
		if(is_null($_use_auto_group_naming))
			return $this->_use_auto_group_naming;
		$this->_use_auto_group_naming = $_use_auto_group_naming;
	}

	/**
	 * Get/Set whether to use export import menu or not
	 *
	 * @return bool $_use_util_menu
	 */
	public function use_util_menu($_use_util_menu = NULL)
	{
		if(is_null($_use_util_menu))
			return $this->_use_util_menu;
		$this->_use_util_menu = $_use_util_menu;
	}

	/**
	 * Get/Set whether it's development mode or not
	 *
	 * @return bool $_dev_mode
	 */
	public function is_dev_mode($_dev_mode = NULL)
	{
		if(is_null($_dev_mode))
			return $this->_dev_mode;
		$this->_dev_mode = $_dev_mode;
	}

}

/**
 * EOF
 */