<?php

class VP_Control_Field_NoteBox extends VP_Control_Field
{

	/**
	 * Status of message, can be one of these:
	 * - normal
	 * - info
	 * - warning
	 * - error
	 * - succed
	 * @var String
	 */
	protected $_status;

	public function __construct()
	{
		parent::__construct();
	}

	public static function withArray($arr)
	{
		$instance = new self();
		$instance->_basic_make($arr);
		return $instance;
	}

	protected function _basic_make($arr)
	{
		parent::_basic_make($arr);
		$this->set_status(isset($arr['status']) ? $arr['status'] : '');
	}

	public function render()
	{
		// Setup Data
		switch ($this->get_status()) {
			case 'normal':
				$this->add_container_extra_classes('normal');
				break;
			case 'info':
				$this->add_container_extra_classes('info');
				break;
			case 'warning':
				$this->add_container_extra_classes('warning');
				break;
			case 'error':
				$this->add_container_extra_classes('error');
				break;
			case 'succed':
				$this->add_container_extra_classes('succed');
				break;
			default:
				$this->add_container_extra_classes('normal');
				break;
		}
		$this->_setup_data();
		return VP_View::get_instance()->load('control/notebox', $this->get_data());
	}

	/**
	 * Get the status of message
	 *
	 * @return String Status of message
	 */
	public function get_status() {
	    return $this->_status;
	}
	
	/**
	 * Set the status of message
	 *
	 * @param String $_status Status of message
	 */
	public function set_status($_status) {
	    $this->_status = $_status;
	    return $this;
	}

}

/**
 * EOF
 */