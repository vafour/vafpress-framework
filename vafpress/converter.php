<?php

include_once('../../../../wp-load.php');

class VP_Converter
{

	protected $group = array('fields', 'sections', 'menus');
	protected $localized = array('page', 'label', 'title', 'description');

	public function to_array()
	{
		// Parse XML String with SimpleXML
		$options   = file_get_contents('config/option.xml');

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
				if($menu->key() == 'sections')
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
						if($submenu->key() == 'sections')
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

		return $opt_arr;
	}

	private function process_sections($sections, $opt_arr)
	{
		// Loop Sections
		$opt_arr .= "\t\t\t\t\t'sections' => array(\n";
		foreach ($sections as $section)
		{
			// Section Open
			$opt_arr .= "\t\t\t\t\t\tarray(\n";
			$fields = array();
			for($section->rewind(); $section->valid(); $section->next()) {
				// Print Tab Attributes
				if(!$section->hasChildren() and !in_array($section->key(), $this->group))
				{
					if(in_array($section->key(), $this->localized))
						$opt_arr .= "\t\t\t\t\t\t\t'{$section->key()}' => __('{$section->current()}', 'vp_textdomain'),\n";
					else
						$opt_arr .= "\t\t\t\t\t\t\t'{$section->key()}' => '{$section->current()}',\n";
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
				$opt_arr .= "\t\t\t\t\t\t\t\tarray(\n";
				$opt_arr .= "\t\t\t\t\t\t\t\t\t'type' => '$key',\n";
				for($field->rewind(); $field->valid(); $field->next())
				{
					// Print Tab Attributes
					if(!$field->hasChildren())
					{
						if($field->key() == 'default')
							echo '';
						if(in_array($field->key(), $this->localized))
							$opt_arr .= "\t\t\t\t\t\t\t\t\t'{$field->key()}' => __('{$field->current()}', 'vp_textdomain'),\n";
						else
							$opt_arr .= "\t\t\t\t\t\t\t\t\t'{$field->key()}' => '{$field->current()}',\n";
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

					// get custom data sources
					foreach ($item->data as $data)
					{
						$datasources[] = array(
							'type' => (string) $data['source'],
							'name' => (string) $data,
						);
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
							$opt_arr .= "\t\t\t\t\t\t\t\t\t\t\t\t'type' => '{$data[type]}',\n";
							$opt_arr .= "\t\t\t\t\t\t\t\t\t\t\t\t'name' => '{$data[name]}',\n";
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
			}
			$opt_arr .= "\t\t\t\t\t\t\t),\n";
			// End Loop Fields
			
			// Section Close
			$opt_arr .= "\t\t\t\t\t\t),\n";
		}
		$opt_arr .= "\t\t\t\t\t),\n";

		return $opt_arr;
	}

}

/**
 * EOF
 */