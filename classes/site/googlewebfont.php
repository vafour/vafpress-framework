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

	public function add($name, $weights = 'normal', $styles = 'normal', $subsets = null)
	{
		
		if(empty($name))
			return;

		$weights = (array) $weights;
		$styles  = (array) $styles;
		$subsets = !empty($subsets) ? (array) $subsets : null;
		$name    = str_replace(' ', '+', $name);

		if(!isset($this->_fonts[$name])) {
			$this->_fonts[$name]            = array();
			$this->_fonts[$name]['atts']    = array();
			$this->_fonts[$name]['subsets'] = array('latin');
		}

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

				if(!in_array($couple, $this->_fonts[$name]['atts']))
					$this->_fonts[$name]['atts'][] = $couple;
			}
		}

		if(!empty($subsets))
		{
			$this->_fonts[$name]['subsets'] = array_merge($this->_fonts[$name]['subsets'], $subsets);
			$this->_fonts[$name]['subsets'] = array_unique($this->_fonts[$name]['subsets']);
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
		foreach ($this->_fonts as $name => $font)
		{
			$atts  = $font['atts'];
			$param = implode(',', $atts);
			$link  = "//fonts.googleapis.com/css?family=$name" . ($param !== '' ? ":$param" : '');
			if(!empty($font['subsets']))
			{
				$subsets = implode(',', $font['subsets']);
				$link   .= '&subset=' . $subsets;

			}
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
