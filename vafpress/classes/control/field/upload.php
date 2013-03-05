<?php

class VP_Control_Field_Upload extends VP_Control_Field
{

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

	public function render()
	{
		$this->_setup_data();
		return VP_View::get_instance()->load('control/upload', $this->get_data());
	}

	public function _setup_data()
	{
		parent::_setup_data();

		$preview = '';
		
		if(filter_var($this->get_value(), FILTER_VALIDATE_URL) !== FALSE)
		{
			$info = pathinfo($this->get_value());
			if(isset($info['extension']))
			{
				$type    = wp_ext2type( $info['extension'] );
				$preview = includes_url() . 'images/crystal/' . $type . '.png';
			}
			else
			{
				// if no extension, just assume they are image
				$preview = 'image';
			}

			if($preview == 'image')
				$preview = $this->get_value();
		}

		$this->add_data('preview', $preview);
	}

}

/**
 * EOF
 */