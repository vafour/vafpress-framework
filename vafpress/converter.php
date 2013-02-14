<?php

include_once('../../../../wp-load.php');

class VP_Converter
{

	protected $group = array('fields', 'sections', 'menus');
	protected $localized = array('page', 'label', 'title', 'description');

	public function to_array()
	{
		// Parse XML String with SimpleXML
		
		// Loading the file, if doesn't exists try to load .sample version
		$option_path        = VP_CONFIG_DIR . '/option';

		$source_path        = $option_path  . '/option.xml';
		$source_path_sample = $source_path  . '.sample';
		$dest_path          = $option_path  . '/option.php';
		$dest_path_sample   = $dest_path    . '.sample';

		if(file_exists($source_path))
		{
			$spath = $source_path;
			$dpath = $dest_path;
		}
		else
		{
			$spath = $source_path_sample;
			$dpath = $dest_path_sample;	
		}

		$options = file_get_contents($spath);

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
					$opt_arr .= "\t'{$set->key()}' => __('{$set->current()}', 'vp_textdomain'),\n";
				else
					$opt_arr .= "\t'{$set->key()}' => '{$set->current()}',\n";
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
						$opt_arr .= "\t\t\t'{$menu->key()}' => __('{$menu->current()}', 'vp_textdomain'),\n";
					else
						$opt_arr .= "\t\t\t'{$menu->key()}' => '{$menu->current()}',\n";
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
								$opt_arr .= "\t\t\t\t\t'{$submenu->key()}' => __('{$submenu->current()}', 'vp_textdomain'),\n";
							else
								$opt_arr .= "\t\t\t\t\t'{$submenu->key()}' => '{$submenu->current()}',\n";
						}
						if($submenu->key() == 'controls')
						{
							$sections = $submenu->getChildren();
						}
					}

					$opt_arr = $this->process_sections($sections, $opt_arr);

					$opt_arr .= "\t\t\t\t),\n";
				}
				// Menu Closing
				$opt_arr .= "\t\t\t),\n";
			}
			else
			{
				if(!empty($sections))
				{
					$opt_arr = $this->process_sections($sections, $opt_arr);
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
			'source'  => $spath,
			'dest'    => $dpath
		);

		return $result;
	}

	private function process_sections($sections, $opt_arr)
	{
		// Loop Sections
		$opt_arr .= "\t\t\t\t\t'controls' => array(\n";
		foreach ($sections as $key => $section)
		{
			// var_dump($key);
			if($key === 'section')
				$opt_arr = $this->process_section($section, $opt_arr);
			else
				$opt_arr = $this->process_field($key, $section, $opt_arr);
		}
		$opt_arr .= "\t\t\t\t\t),\n";

		return $opt_arr;
	}

	private function process_section($section, $opt_arr)
	{
		// Section Open
		$opt_arr .= "\t\t\t\t\t\tarray(\n";
		$fields   = array();

		$opt_arr .= "\t\t\t\t\t\t\t'type' => 'section',\n";
		for($section->rewind(); $section->valid(); $section->next()) {
			// Print Section Attributes
			if(!$section->hasChildren() and !in_array($section->key(), $this->group))
			{
				if($section->key() == 'dependency')
				{
					$current = $section->current();

					$opt_arr .= "\t\t\t\t\t\t\t'dependency' => array(\n";
					$opt_arr .= "\t\t\t\t\t\t\t\t'field' => '{$current[field]}',\n";
					$opt_arr .= "\t\t\t\t\t\t\t\t'value' => '{$current}',\n";
					$opt_arr .= "\t\t\t\t\t\t\t),\n";
				}
				else
				{
					if(in_array($section->key(), $this->localized))
						$opt_arr .= "\t\t\t\t\t\t\t'{$section->key()}' => __('{$section->current()}', 'vp_textdomain'),\n";
					else
						$opt_arr .= "\t\t\t\t\t\t\t'{$section->key()}' => '{$section->current()}',\n";
				}
			}
			if($section->key() == 'fields')
			{
				$fields = $section->getChildren();
			}
		}

		// Loop Fields
		$opt_arr .= "\t\t\t\t\t\t\t'fields' => array(\n";
		foreach ($fields as $key => $field)
		{
			$opt_arr = $this->process_field($key, $field, $opt_arr);
		}
		$opt_arr .= "\t\t\t\t\t\t\t),\n";
		// End Loop Fields
		
		// Section Close
		$opt_arr .= "\t\t\t\t\t\t),\n";

		return $opt_arr;
	}

	private function process_field($key, $field, $opt_arr)
	{
		$opt_arr .= "\t\t\t\t\t\t\t\tarray(\n";
		$opt_arr .= "\t\t\t\t\t\t\t\t\t'type' => '$key',\n";
		for($field->rewind(); $field->valid(); $field->next())
		{
			// Print Tab Attributes
			if(!$field->hasChildren())
			{
				if($field->key() == 'dependency')
				{

					$current  = $field->current();

					$opt_arr .= "\t\t\t\t\t\t\t\t\t'dependency' => array(\n";
					$opt_arr .= "\t\t\t\t\t\t\t\t\t\t'field' => '{$current[field]}',\n";
					$opt_arr .= "\t\t\t\t\t\t\t\t\t\t'value' => '{$current}',\n";
					$opt_arr .= "\t\t\t\t\t\t\t\t\t),\n";

				}
				else
				{
					if(in_array($field->key(), $this->localized))
						$opt_arr .= "\t\t\t\t\t\t\t\t\t'{$field->key()}' => __('{$field->current()}', 'vp_textdomain'),\n";
					else
						$opt_arr .= "\t\t\t\t\t\t\t\t\t'{$field->key()}' => '{$field->current()}',\n";
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
			$opt_arr  .= "\t\t\t\t\t\t\t\t\t'items' => array(\n";

			// add custom datasources
			if(!empty($datasources))
			{

				$opt_arr .= "\t\t\t\t\t\t\t\t\t\t'data' => array(\n";
				foreach ($datasources as $data)
				{
					$opt_arr .= "\t\t\t\t\t\t\t\t\t\t\tarray(\n";
					foreach ($data as $key => $value) {
						$opt_arr .= "\t\t\t\t\t\t\t\t\t\t\t\t'$key' => '{$value}',\n";
					}
					$opt_arr .= "\t\t\t\t\t\t\t\t\t\t\t),\n";
				}
				$opt_arr .= "\t\t\t\t\t\t\t\t\t\t),\n";
			}

			foreach ($items as $item)
			{
				$opt_arr .= "\t\t\t\t\t\t\t\t\t\tarray(\n";


				foreach ($item as $key => $value)
				{
					if(in_array($key, $this->localized))
						$opt_arr .= "\t\t\t\t\t\t\t\t\t\t\t'$key' => __('$value', 'vp_textdomain'),\n";
					else
						$opt_arr .= "\t\t\t\t\t\t\t\t\t\t\t'$key' => '$value',\n";
				}
				$opt_arr .= "\t\t\t\t\t\t\t\t\t\t),\n";
			}
			$opt_arr .= "\t\t\t\t\t\t\t\t\t),\n";
		}

		// processing defaaults
		if(!empty($tag_defaults))
			$defaults = $tag_defaults;
		else
			$defaults = $emb_defaults;
		if(!empty($defaults))
		{
			$opt_arr  .= "\t\t\t\t\t\t\t\t\t'default' => array(\n";
			foreach ($defaults as $def)
			{
				$opt_arr .= "\t\t\t\t\t\t\t\t\t\t'$def',\n";
			}
			$opt_arr .= "\t\t\t\t\t\t\t\t\t),\n";
		}


		$opt_arr .= "\t\t\t\t\t\t\t\t),\n";

		return $opt_arr;
	
	}

}

/**
 * EOF
 */