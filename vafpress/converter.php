<?php

/**
 * Convert XML configuration to PHP array.
 */
class VP_Converter
{

	protected $group       = array('fields', 'sections', 'menus');
	protected $localized   = array('page', 'label', 'title', 'description');
	protected $text_domain = 'vp_textdomain';

	public function to_array()
	{
		// Get option path and configs
		$option_xml_path = VP_FileSystem::instance()->resolve_path('builder', 'option/option', 'xml');
		$option_php_path = dirname($option_xml_path) . '/option.php';
		$options         = file_get_contents($option_xml_path);
		$config          = VP_Util_Config::instance()->load('option');

		// Set textdomain
		if( isset($config['text_domain']) )
		{
			$this->text_domain = $config['text_domain'];
		}

		// Open root array
		$opt_arr  = "<?php\n";
		$opt_arr .= "\n";
		$opt_arr .= "return array(\n";

		// Parse Set Option and Catch the Menus
		$set = new SimpleXMLIterator($options);
		for($set->rewind(); $set->valid(); $set->next()) {
			// Print Option Set Attributes
			if(!$set->hasChildren())
			{
				if(in_array($set->key(), $this->localized))
				{
					$value    = $this->normalize_text($set->current());
					$opt_arr .= "\t'{$set->key()}' => __('{$value}', '{$this->text_domain}'),\n";
				}
				else
				{
					$opt_arr .= "\t'{$set->key()}' => '{$set->current()}',\n";
				}
			}
			if($set->key() == 'menus')
			{
				$menus = $set->getChildren();
			}
		}

		// Loop Through Menus
		$opt_arr .= "\t'menus' => array(\n";
		foreach ($menus as $menu)
		{
			// Menu Begin
			$opt_arr .= "\t\tarray(\n";
			$submenus = array();
			for($menu->rewind(); $menu->valid(); $menu->next()) {
				// Print Menu Attributes
				if(!$menu->hasChildren() and !in_array($menu->key(), $this->group))
				{
					if(in_array($menu->key(), $this->localized))
					{
						$value    = $this->normalize_text($menu->current());
						$opt_arr .= "\t\t\t'{$menu->key()}' => __('{$value}', '{$this->text_domain}'),\n";
					}
					else
					{
						$opt_arr .= "\t\t\t'{$menu->key()}' => '{$menu->current()}',\n";
					}
				}
				if($menu->key() == 'menus')
				{
					$submenus = $menu->getChildren();
				}
				if($menu->key() == 'controls')
				{
					$sections = $menu->getChildren();
				}
			}
			// Loop Through Submenus
			if(!empty($submenus))
			{
				$opt_arr .= "\t\t\t'menus' => array(\n";
				foreach ($submenus as $submenu)
				{
					$opt_arr .= "\t\t\t\tarray(\n";
					$sections = array();
					for($submenu->rewind(); $submenu->valid(); $submenu->next()) {
						// Print Tab Attributes
						if(!$submenu->hasChildren() and !in_array($submenu->key(), $this->group))
						{
							if(in_array($submenu->key(), $this->localized))
							{
								$value    = $this->normalize_text($submenu->current());
								$opt_arr .= "\t\t\t\t\t'{$submenu->key()}' => __('{$value}', '{$this->text_domain}'),\n";
							}
							else
							{
								$opt_arr .= "\t\t\t\t\t'{$submenu->key()}' => '{$submenu->current()}',\n";
							}
						}
						if($submenu->key() == 'controls')
						{
							$sections = $submenu->getChildren();
						}
					}

					$opt_arr = $this->process_sections($sections, $opt_arr, 5);

					$opt_arr .= "\t\t\t\t),\n";
				}
				// Menu Closing
				$opt_arr .= "\t\t\t),\n";
			}
			else
			{
				if(!empty($sections))
				{
					$opt_arr = $this->process_sections($sections, $opt_arr, 3);
				}
			}

			$opt_arr .= "\t\t),\n";
		}
		$opt_arr .= "\t)\n";

		// Close root array
		$opt_arr .= ");\n";
		$opt_arr .= "\n";
		$opt_arr .= "/**\n";
		$opt_arr .= " *EOF\n";
		$opt_arr .= " */";

		$result = array(
			'opt_arr' => $opt_arr,
			'source'  => $option_xml_path,
			'dest'    => $option_php_path
		);

		return $result;
	}

	private function process_sections($sections, $opt_arr, $ntabs)
	{
		// Loop Sections
		$tabs = str_repeat("\t", $ntabs);
		$opt_arr .= "$tabs'controls' => array(\n";
		foreach ($sections as $key => $section)
		{
			// var_dump($key);
			if($key === 'section')
			{
				$opt_arr = $this->process_section($section, $opt_arr, $ntabs);
			}
			else
			{
				$opt_arr = $this->process_field($key, $section, $opt_arr, $ntabs);
			}
		}
		$opt_arr .= "$tabs),\n";

		return $opt_arr;
	}

	private function process_section($section, $opt_arr, $ntabs)
	{
		// Section Open
		$tabs     = str_repeat("\t", $ntabs);
		$opt_arr .= "$tabs\tarray(\n";
		$fields   = array();

		$opt_arr .= "$tabs\t\t'type' => 'section',\n";
		for($section->rewind(); $section->valid(); $section->next()) {
			// Print Section Attributes
			if(!$section->hasChildren() and !in_array($section->key(), $this->group))
			{
				if($section->key() == 'dependency')
				{
					$current = $section->current();

					$opt_arr .= "$tabs\t\t'dependency' => array(\n";
					$opt_arr .= "$tabs\t\t\t'field' => '{$current['field']}',\n";
					$opt_arr .= "$tabs\t\t\t'function' => '{$current}',\n";
					$opt_arr .= "$tabs\t\t),\n";
				}
				else
				{
					if(in_array($section->key(), $this->localized))
					{
						$value    = $this->normalize_text($section->current());
						$opt_arr .= "$tabs\t\t'{$section->key()}' => __('{$value}', '{$this->text_domain}'),\n";
					}
					else
					{
						$opt_arr .= "$tabs\t\t'{$section->key()}' => '{$section->current()}',\n";
					}
				}
			}
			if($section->key() == 'fields')
			{
				$fields = $section->getChildren();
			}
		}

		// Loop Fields
		$opt_arr .= "$tabs\t\t'fields' => array(\n";
		foreach ($fields as $key => $field)
		{
			$opt_arr = $this->process_field($key, $field, $opt_arr, $ntabs + 2);
		}
		$opt_arr .= "$tabs\t\t),\n";
		// End Loop Fields
		
		// Section Close
		$opt_arr .= "$tabs\t),\n";

		return $opt_arr;
	}

	private function process_field($key, $field, $opt_arr, $ntabs)
	{
		$tabs     = str_repeat("\t", $ntabs);

		$opt_arr .= "$tabs\tarray(\n";
		$opt_arr .= "$tabs\t\t'type' => '$key',\n";
		for($field->rewind(); $field->valid(); $field->next())
		{
			// Print Tab Attributes
			if(!$field->hasChildren())
			{
				if($field->key() == 'dependency')
				{
					$current  = $field->current();
					$opt_arr .= "$tabs\t\t'dependency' => array(\n";
					$opt_arr .= "$tabs\t\t\t'field' => '{$current['field']}',\n";
					$opt_arr .= "$tabs\t\t\t'function' => '{$current}',\n";
					$opt_arr .= "$tabs\t\t),\n";

				}
				else if($field->key() == 'binding')
				{
					$current  = $field->current();
					$opt_arr .= "$tabs\t\t'binding' => array(\n";
					$opt_arr .= "$tabs\t\t\t'field' => '{$current['field']}',\n";
					$opt_arr .= "$tabs\t\t\t'function' => '{$current}',\n";
					$opt_arr .= "$tabs\t\t),\n";
				}
				else
				{
					if(in_array($field->key(), $this->localized))
					{
						$value    = $this->normalize_text($field->current());
						$opt_arr .= "$tabs\t\t'{$field->key()}' => __('{$value}', '{$this->text_domain}'),\n";
					}
					else
					{
						$opt_arr .= "$tabs\t\t'{$field->key()}' => '{$field->current()}',\n";
					}
				}

			}
		}

		// process items
		$xml_items    = $field->items;
		$items        = array();
		$datasources  = array();
		$emb_defaults = array();
		$tag_defaults = array();
		for($xml_items->rewind(); $xml_items->valid(); $xml_items->next())
		{
			$item = $xml_items->current();

			foreach ($item->data as $data)
			{
				$ds = array();
				
				$data_attr = $data->attributes();
				foreach ($data_attr as $key => $att)
					$ds[$key] = (string) $att;
				$ds['value'] = (string) $data;

				$datasources[] = $ds;
			}

			// get use defined items
			$itm  = array();
			foreach ($item->item as $key => $value)
			{
				// Get Items attribute
				$itm['value'] = (string) $value['value'];
				$itm['label'] = (string) $value;
				$img = (string) $value['img'];
				if(!empty($img))
				{
					$itm['img'] = $img;
				}
				$items[] = $itm;

				// Check for default
				$default = (string) $value['default'];
				if(!empty($default))
				{
					$emb_defaults[] = $itm['value'];
				}
			}
		}

		$xml_defaults = $field->default;
		for($xml_defaults->rewind(); $xml_defaults->valid(); $xml_defaults->next())
		{
			$default = $xml_defaults->current();
			foreach ($default as $key => $value)
			{
				if($key == 'item')
					$tag_defaults[] = (string) $value;
			}
		}

		// processing items
		if(!empty($items) || !empty($datasources))
		{
			$opt_arr  .= "$tabs\t\t'items' => array(\n";

			// add custom datasources
			if(!empty($datasources))
			{

				$opt_arr .= "$tabs\t\t\t'data' => array(\n";
				foreach ($datasources as $data)
				{
					$opt_arr .= "$tabs\t\t\t\tarray(\n";
					foreach ($data as $key => $value) {
						$opt_arr .= "$tabs\t\t\t\t\t'$key' => '{$value}',\n";
					}
					$opt_arr .= "$tabs\t\t\t\t),\n";
				}
				$opt_arr .= "$tabs\t\t\t),\n";
			}

			foreach ($items as $item)
			{
				$opt_arr .= "$tabs\t\t\tarray(\n";


				foreach ($item as $key => $value)
				{
					if(in_array($key, $this->localized))
					{
						$value    = $this->normalize_text($value);
						$opt_arr .= "$tabs\t\t\t\t'$key' => __('$value', '{$this->text_domain}'),\n";
					}
					else
					{
						$opt_arr .= "$tabs\t\t\t\t'$key' => '$value',\n";
					}
				}
				$opt_arr .= "$tabs\t\t\t),\n";
			}
			$opt_arr .= "$tabs\t\t),\n";
		}

		// processing defaults
		if(!empty($tag_defaults))
			$defaults = $tag_defaults;
		else
			$defaults = $emb_defaults;
		if(!empty($defaults))
		{
			$opt_arr  .= "$tabs\t\t'default' => array(\n";
			foreach ($defaults as $def)
			{
				$opt_arr .= "$tabs\t\t\t'$def',\n";
			}
			$opt_arr .= "$tabs\t\t),\n";
		}


		$opt_arr .= "$tabs\t),\n";

		return $opt_arr;
	
	}

	private function normalize_text($text)
	{
		return htmlentities($text, ENT_QUOTES);
	}

}

/**
 * EOF
 */