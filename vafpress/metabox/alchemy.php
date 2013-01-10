<?php

/**
 * Extended version of WPAlchemy Class
 * so that it can process metabox using an array specification
 * and compatible with all Vafpress Framework Option Controls.
 */
class VP_MetaBox_Alchemy extends WPAlchemy_MetaBox
{

	private $things = array();

	/**
	 * Used to setup the meta box content template
	 *
	 * @since	1.0
	 * @access	private
	 * @see		_init()
	 */
	function _setup()
	{
		$this->in_template = TRUE;
		
		// also make current post data available
		global $post;

		// shortcuts
		$mb =& $this;
		$metabox =& $this;
		$id = $this->id;
		$meta = $this->_meta(NULL, TRUE);

		// use include because users may want to use one templete for multiple meta boxes
		if( !is_array($this->template) and file_exists($this->template) )
		{
			include $this->template;
		}
		else
		{
			echo '<div class="vp-metabox">';
			echo '<table>';
			echo '<tbody>';
			$this->_enview($this->template);
			echo '</tbody>';
			echo '</table>';
			echo '</div>';
		}
	 
		// create a nonce for verification
		echo '<input type="hidden" name="'. $this->id .'_nonce" value="' . wp_create_nonce($this->id) . '" />';

		$this->in_template = FALSE;
	}

	function _enview($arr)
	{
		$mb       =& $this;
		$fields   = $arr['fields'];

		foreach ($fields as $field)
		{
			// if it's a group
			if( $field['type'] == 'group' )
			{
				echo $this->_render_group($field, $mb);
			}
			else
			{
				echo $this->_render_field($field, $mb);
			}
		}
	}

	function _render_field($field, $mb, $in_group = false)
	{
		$multiple = array('checkbox', 'checkimage', 'multiselect');

		if( !in_array($field['type'], $multiple) )
		{
			$mb->the_field($field['name']);
		}
		else
		{
			$mb->the_field($field['name'], WPALCHEMY_FIELD_HINT_CHECKBOX_MULTI);						
		}
		$field['name'] = $mb->get_the_name();

		// create the object
		$make     = 'VP_Control_Field_' . $field['type'];
		$vp_field = call_user_func("$make::withArray", $field);

		// get value from mb
		$value    = $mb->get_the_value();
		// get default from array
		$default  = $vp_field->get_default();

		// if value is null and default exist, use default
		if( is_null($value) and !empty($default) )
		{
			$value = $default;				
		}
		// if not the set up value from mb
		else
		{
			if( in_array($field['type'], $multiple) )
			{
				if( !is_array($value) )
					$value = array( $value );
			}
		}
		$vp_field->set_value($value);

		if (!$in_group) {
			$vp_field->set_container_extra_classes('vp-meta-row');
		}

		return $vp_field->render();

	}

	function _render_group($field, $mb) {

		$html  = '';
		$html .= '<tr id="wpa_loop-' . $field['name'] . '" class="vp-wpa-loop vp-meta-row wpa_loop wpa_loop-' . $field['name'] . '">';
		$html .= '<td colspan="2">';
			$html .= '<table>';
			$html .= '<tbody>';

			while($mb->have_fields_and_multi($field['name']))
			{
				$class = '';
				if ($this->is_last()) $class = ' last tocopy';
				if ($this->is_first()) $class = ' first';
				$html .= '<tr class="vp-wpa-group wpa_group wpa_group-' . $field['name'] . $class . '">';
				$html .= '<td>';
					$html .= '<table>';
					$html .= '</tbody>';
					foreach ($field['fields'] as $f) { $html .= $this->_render_field($f, $mb, true); }
					$html .= '</tbody>';
					$html .= '</table>';
				$html .= '</td>';
				$html .= '<td class="vp-wpa-group-remove">';
				$html .= '<a href="#" class="dodelete" title="Remove">X</a>';
				$html .= '</td>';
				$html .= '</tr>';
			}

				$html .= '<tr>';
				$html .= '<td class="vp-wpa-group-add">';
				$html .= '<a href="#" class="button button-large docopy-' . $field['name'] . '">Add More</a>';
				$html .= '</td>';
				$html .= '<td></td>';
				$html .= '</tr>';
			$html .= '</tbody>';
			$html .= '</table>';
		$html .= '</td>';
		$html .= '</tr>';

		return $html;
	}

	// get all groups index as array
	function _get_groups_idx()
	{
		$groups = array();
		foreach ($this->template['fields'] as $field)
		{
			if( $field['type'] == 'group' )
			{
				$groups[] = $field['name'];
			}
		}
		return $groups;
	}

	function _save($post_id) 
	{
		// skip saving if dev mode is on
		$dev_mode = VP_Util_Config::get_instance()->load('metabox/main', 'dev_mode');
		if($dev_mode)
			return;

		$real_post_id = isset($_POST['post_ID']) ? $_POST['post_ID'] : NULL ;
		
		// check autosave
		if (defined('DOING_AUTOSAVE') AND DOING_AUTOSAVE AND !$this->autosave) return $post_id;
	 
		// make sure data came from our meta box, verify nonce
		$nonce = isset($_POST[$this->id.'_nonce']) ? $_POST[$this->id.'_nonce'] : NULL ;
		if (!wp_verify_nonce($nonce, $this->id)) return $post_id;
	 
		// check user permissions
		if ($_POST['post_type'] == 'page') 
		{
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		}
		else 
		{
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
	 
		// authentication passed, save data
		$new_data = isset( $_POST[$this->id] ) ? $_POST[$this->id] : NULL ;

		// remove 'to-copy' item from being saved
		$groups = $this->_get_groups_idx();
		foreach ($new_data as $key => &$value)
		{
			if( in_array($key, $groups) and is_array($value) )
			{
				end($value);
				$key = key($value);
				unset($value[$key]);
			}
		}

		// try to normalize data, since alchemy clean any empty value, it will
		// throw away empty checkbox value, making unchecked checkbox not being saved
		$scheme = $this->_get_scheme();
		foreach ($scheme as $key => &$value)
		{
			if( in_array($key, $groups) and is_array($value) )
			{
				$count = count($new_data[$key]);
				$data  = $value[0];
				$value = array();
				for ($i=0; $i < $count; $i++)
				{ 
					array_push($value, $data);
				}
			}
		}
	 
		WPAlchemy_MetaBox::clean($new_data);

		if (empty($new_data))
		{
			$new_data = NULL;
		}

		// continuation of normalizing data
		$new_data = VP_Util_Array::array_merge_recursive_all($scheme, $new_data);

		// filter: save
		if ($this->has_filter('save'))
		{
			$new_data = $this->apply_filters('save', $new_data, $real_post_id);

			/**
			 * halt saving
			 * @since 1.3.4
			 */
			if (FALSE === $new_data) return $post_id;

			WPAlchemy_MetaBox::clean($new_data);
		}

		// get current fields, use $real_post_id (checked for in both modes)
		$current_fields = get_post_meta($real_post_id, $this->id . '_fields', TRUE);

		if ($this->mode == WPALCHEMY_MODE_EXTRACT)
		{
			$new_fields = array();

			if (is_array($new_data))
			{
				foreach ($new_data as $k => $v)
				{
					$field = $this->prefix . $k;
					
					array_push($new_fields,$field);

					$new_value = $new_data[$k];

					if (is_null($new_value))
					{
						delete_post_meta($post_id, $field);
					}
					else
					{
						update_post_meta($post_id, $field, $new_value);
					}
				}
			}

			$diff_fields = array_diff((array)$current_fields,$new_fields);

			if (is_array($diff_fields))
			{
				foreach ($diff_fields as $field)
				{
					delete_post_meta($post_id,$field);
				}
			}

			delete_post_meta($post_id, $this->id . '_fields');

			if ( ! empty($new_fields))
			{
				add_post_meta($post_id,$this->id . '_fields', $new_fields, TRUE);
			}

			// keep data tidy, delete values if previously using WPALCHEMY_MODE_ARRAY
			delete_post_meta($post_id, $this->id);
		}
		else
		{
			if (is_null($new_data))
			{
				delete_post_meta($post_id, $this->id);
			}
			else
			{
				update_post_meta($post_id, $this->id, $new_data);
			}

			// keep data tidy, delete values if previously using WPALCHEMY_MODE_EXTRACT
			if (is_array($current_fields))
			{
				foreach ($current_fields as $field)
				{
					delete_post_meta($post_id, $field);
				}

				delete_post_meta($post_id, $this->id . '_fields');
			}
		}

		// action: save
		if ($this->has_action('save'))
		{
			$this->do_action('save', $new_data, $real_post_id);
		}

		return $post_id;
	}

	private function _get_scheme()
	{

		$this->in_template = TRUE;

		$scheme      = array();
		$curr_group  = '';
		$is_in_group = false;
		$multiple = array('checkbox', 'checkimage', 'multiselect');

		$fields = $this->template;
		$fields = $fields['fields'];

		foreach ($fields as $field)
		{
			if( $field['type'] == 'group' )
			{
				$curr_group          = $field['name'];
				$scheme[$curr_group] = array();
				$is_in_group         = true;
				while($this->have_fields_and_multi($curr_group))
				{
					$ops = array();
					foreach ($field['fields'] as $f)
					{
						if(in_array($f['type'], $multiple))
							$ops[$f['name']] = array();
						else
							$ops[$f['name']] = '';
					}
					$scheme[$curr_group][] = $ops;
				}
				end($scheme[$curr_group]);
				$key = key($scheme[$curr_group]);
				unset($scheme[$curr_group][$key]);
			}
			else
			{
				if(in_array($field['type'], $multiple))
					$scheme[$field['name']] = array();
				else
					$scheme[$field['name']] = '';
			}
		}
		return $scheme;
	}

}