<?php

/**
 * Singleton class to manage Google Web Fonts embedding,
 * add the fonts with the weight and style
 * 
 */
class VP_Site_GoogleWebFont
{
	private $_fonts = array();

	private static $_instance = null;

	public static function instance()
	{
		if(self::$_instance == null)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function add($name, $weights = 'normal', $styles = 'normal')
	{
		
		if(empty($name))
			return;

		$weights = (array) $weights;
		$styles  = (array) $styles;
		$name    = str_replace(' ', '+', $name);

		if(!isset($this->_fonts[$name]))
			$this->_fonts[$name] = array();

		foreach ($weights as $weight)
		{
			foreach ($styles as $style)
			{
				// set it to empty if style is equal to normal
				if($style === 'normal')
					$style = '';

				if($style != '')
					if($weight === 'normal') $weight = '';

				// skip if both are empty
				if($style === '' and $weight === '')
					continue;

				$couple = $weight . $style;

				if(!in_array($couple, $this->_fonts[$name]))
					$this->_fonts[$name][] = $couple;
			}
		}
	}

	public function register()
	{
		$links = $this->get_font_links();
		foreach ($links as $name => $link)
		{
			wp_register_style( $name, $link);
		}
	}

	public function enqueue()
	{
		$names = $this->get_names();
		foreach ($names as $name)
		{
			wp_enqueue_style( $name );
		}
	}

	public function register_and_enqueue()
	{
		$this->register();
		$this->enqueue();
	}

	public function get_font_links()
	{
		$links = array();
		foreach ($this->_fonts as $name => $atts)
		{
			$param = implode(',', $atts);
			$link  = "http://fonts.googleapis.com/css?family=$name" . ($param !== '' ? ":$param" : '');
			$links[$name] = $link;
		}
		return $links;
	}

	public function get_fonts()
	{
		return $this->_fonts;
	}

	public function get_names()
	{
		return array_keys($this->_fonts);
	}

}

/**
 * EOF
 */