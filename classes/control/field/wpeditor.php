<?php

class VP_Control_Field_WPEditor extends VP_Control_Field
{

	private $_use_external_plugins       = true;

	private $_disabled_externals_plugins = array();

	private $_disabled_internals_plugins = array();

	public function __construct()
	{
		parent::__construct();
	}

	public static function withArray($arr = array(), $class_name = null)
	{
		if(is_null($class_name))
			$instance = new self();
		else
			$instance = new $class_name;

		$use_external_plugins       = isset($arr['use_external_plugins']) ? $arr['use_external_plugins'] : 1;
		$disabled_externals_plugins = array();
		$disabled_internals_plugins = array();

		if(isset($arr['disabled_externals_plugins']))
		{
			$disabled_externals_plugins = explode(',', $arr['disabled_externals_plugins']);
		}
		if(isset($arr['disabled_internals_plugins']))
		{
			$disabled_internals_plugins = explode(',', $arr['disabled_internals_plugins']);
		}

		$instance->use_external_plugins($use_external_plugins);
		$instance->set_disabled_externals_plugins($disabled_externals_plugins);
		$instance->set_disabled_internals_plugins($disabled_internals_plugins);
		$instance->_basic_make($arr);

		return $instance;
	}

	protected function _setup_data()
	{
		$opt = array(
			'use_external_plugins'       => $this->use_external_plugins(),
			'disabled_externals_plugins' => implode(',', $this->get_disabled_externals_plugins()),
			'disabled_internals_plugins' => implode(',', $this->get_disabled_internals_plugins()),
		);
		$this->add_data('opt', VP_Util_Text::make_opt($opt));
		$this->add_data('opt_raw', $opt);
		parent::_setup_data();
	}

	public function render($is_compact = false)
	{
		$this->_setup_data();
		$this->add_data('is_compact', $is_compact);
		return VP_View::instance()->load('control/wpeditor', $this->get_data());
	}

	public function set_value($_value)
	{
		$this->_value = $_value;
		return $this;
	}

	public function use_external_plugins($use = null)
	{
		if(!is_null($use))
		{
			$this->_use_external_plugins = $use;
		}
		return $this->_use_external_plugins;
	}

	/**
	 * Get disable external plugins
	 *
	 * @return Array
	 */
	public function get_disabled_externals_plugins()
	{
		return $this->_disabled_externals_plugins;
	}
	
	/**
	 * Set disabled external plugins
	 *
	 * @param Array $_disabled_externals_plugins
	 */
	public function set_disabled_externals_plugins($_disabled_externals_plugins)
	{
		$this->_disabled_externals_plugins = $_disabled_externals_plugins;
		return $this;
	}


	/**
	 * Get disabled internal plugins
	 *
	 * @return Array
	 */
	public function get_disabled_internals_plugins() {
		return $this->_disabled_internals_plugins;
	}
	
	/**
	 * Set disabled internal plugins
	 *
	 * @param Array $_disabled_internals_plugins 
	 */
	public function set_disabled_internals_plugins($_disabled_internals_plugins) {
		$this->_disabled_internals_plugins = $_disabled_internals_plugins;
		return $this;
	}

}

/**
 * EOF
 */