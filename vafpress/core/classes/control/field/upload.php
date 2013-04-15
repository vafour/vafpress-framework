<?php

class VP_Control_Field_Upload extends VP_Control_Field
{

	public function __construct()
	{
		parent::__construct();
	}

	public static function withArray($arr = array())
	{
		$instance = new self();
		$instance->_basic_make($arr);
		return $instance;
	}

	public function _setup_data()
	{

		$preview = '';
		$images  = array('jpg', 'jpeg', 'bmp',  'gif',  'png');
		
		if(filter_var($this->get_value(), FILTER_VALIDATE_URL) !== FALSE)
		{
			$info = pathinfo($this->get_value());
			if(isset($info['extension']))
			{
				if(in_array($info['extension'], $images))
				{
					$preview = 'image';
				}
				else
				{
					$type    = wp_ext2type( $info['extension'] );
					$preview = includes_url() . 'images/crystal/' . $type . '.png';
				}
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
		
		parent::_setup_data();
	}

	public function render($is_compact = false)
	{
		$this->_setup_data();
		$this->add_data('is_compact', $is_compact);
		return VP_View::instance()->load('control/upload', $this->get_data());
	}

}

/**
 * EOF
 */