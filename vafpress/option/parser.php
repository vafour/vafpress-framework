<?php

class VP_Option_Parser
{

	public function parse_array_options($arr)
	{
		// Parse XML String with SimpleXML
		$set = new VP_Option_Set();

		if(empty($arr['title']))
			$arr['title'] = 'Vafpress';
		if(empty($arr['logo']))
			$arr['logo']  = 'vp-logo.png';

		$set->set_title(isset($arr['title']) ? $arr['title'] : '')
		    ->set_page(isset($arr['page']) ? $arr['page'] : '')
		    ->set_logo(isset($arr['logo']) ? $arr['logo'] : '');

		// Loops trough all the menus
		if (!empty($arr['menus'])) foreach ($arr['menus'] as $menu)
		{
			// Create menu object ? add to se : ''t
			$vp_menu = new VP_Option_Group_Menu();
			$vp_menu->set_name(isset($menu['name']) ? $menu['name'] : '')
			        ->set_title(isset($menu['title']) ? $menu['title'] : '')
			        ->set_icon(isset($menu['icon']) ? $menu['icon'] : '');

			$set->add_menu($vp_menu);

			// Loops through every submenu in each menu
			if (!empty($menu['menus'])) foreach ($menu['menus'] as $submenu)
			{
				$vp_submenu = new VP_Option_Group_Menu();
				$vp_submenu->set_name(isset($submenu['name']) ? $submenu['name'] : '')
				           ->set_title(isset($submenu['title']) ? $submenu['title'] : '')
				           ->set_icon(isset($submenu['icon']) ? $submenu['icon'] : '');

				$vp_menu->add_menu($vp_submenu);
				
				// Loops through every section in each submenu
				if (!empty($submenu['sections'])) foreach ($submenu['sections'] as $section)
				{
					$vp_sec = new VP_Option_Group_Section();
					$vp_sec->set_name(isset($section['name']) ? $section['name'] : '')
					       ->set_title(isset($section['title']) ? $section['title'] : '')
					       ->set_description(isset($section['description']) ? $section['description'] : '');
						   
					$vp_submenu->add_section($vp_sec);

					// Loops through every field in each submenu
					if (!empty($section['fields'])) foreach ($section['fields'] as $field)
					{
						$make     = 'VP_Option_Field_' . $field['type'];
						$vp_field = call_user_func("$make::withArray", $field);
						$vp_sec->add_field($vp_field);
					}
				}
			}
			else
			{
				// Loops through every section in each submenu
				if (!empty($menu['sections'])) foreach ($menu['sections'] as $section)
				{
					$vp_sec = new VP_Option_Group_Section();
					$vp_sec->set_name(isset($section['name']) ? $section['name'] : '')
					       ->set_title(isset($section['title']) ? $section['title'] : '')
					       ->set_description(isset($section['description']) ? $section['description'] : '');
						   
					$vp_menu->add_section($vp_sec);

					// Loops through every field in each submenu
					if (!empty($section['fields'])) foreach ($section['fields'] as $field)
					{
						$make     = 'VP_Option_Field_' . $field['type'];
						$vp_field = call_user_func("$make::withArray", $field);
						$vp_sec->add_field($vp_field);
					}
				}
			}
		}

		return $set;
	}

}

/**
 * EOF
 */