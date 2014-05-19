<?php

/**
 * Extended version of WPAlchemy Class
 * so that it can process metabox using an array specification
 * and compatible with all Vafpress Framework Option Controls.
 */

/////////////////////////////////////////
// Include original WPAlchemy Class    //
/////////////////////////////////////////
if(!class_exists('WPAlchemy_MetaBox'))
{
	require_once VP_FileSystem::instance()->resolve_path('includes', 'wpalchemy/MetaBox');
}

class VP_Metabox extends WPAlchemy_MetaBox
{

	public static $pool = array();

	public $is_dev_mode = false;

	function __construct($arr)
	{
		if( !is_array($arr) and file_exists($arr) )
		{
			$arr = include $arr;
		}
		parent::__construct($arr);

		// Modify title in dev mode
		if( $this->is_dev_mode )
		{
			$this->title = __('[Development Mode] ', 'vp_textdomain') . $this->title;
		}

		if ($this->can_output() and VP_WP_Admin::is_post_or_page() )
		{
			// make sure metabox template loaded
			if( !is_array($this->template) and file_exists($this->template) )
				$this->template = include $this->template;
			add_action( 'init', array( $this, 'register_fields' ) );
		}

		self::$pool[$this->id] = $this;
	}

	public function register_fields()
	{
		$loader = VP_WP_Loader::instance();
		$loader->add_types( $this->get_field_types(), 'metabox' );
	}

	public static function get_pool()
	{
		return self::$pool;
	}

	public static function pool_can_output()
	{
		foreach (self::$pool as $mb)
		{
			if( $mb->can_output() )
			{
				return true;
			}
		}
		return false;
	}

	public static function pool_supports_editor()
	{
		foreach (self::$pool as $mb)
		{
			if( $mb->supports_editor() )
			{
				return true;
			}
		}
		return false;
	}

	public function supports_editor()
	{
		$post_type  = self::_get_current_post_type();
		$has_editor = post_type_supports( $post_type, 'editor' );
		return $has_editor;
	}

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
		$mb      =& $this;
		$metabox =& $this;
		$id      =  $this->id;
		$meta    =  $this->_meta(NULL, TRUE);

		// make sure metabox template loaded
		if( !is_array($this->template) and file_exists($this->template) )
		{
			$this->template = include $this->template;
		}
		else
		{
			$fields = $this->_enfactor($this->template);
			$this->_enbind($fields);
			$fields = $this->_endep($fields);

			echo '<div class="vp-metabox">';
			$this->_enview($fields);
			echo '</div>';
		}
	 
		// create a nonce for verification
		echo '<input type="hidden" name="'. $this->id .'_nonce" value="' . wp_create_nonce($this->id) . '" />';

		$this->in_template = FALSE;
	}

	// return all field types
	function get_field_types()
	{
		$types = array();

		if(!function_exists('inner_build'))
		{
			function inner_build($fields, &$types)
			{
				$rules = VP_Util_Config::instance()->load('dependencies', 'rules');
				foreach ($fields as $field)
				{
					if($field['type'] == 'group')
					{
						inner_build($field['fields'], $types);
					}
					else
					{
						if( ! in_array($field['type'], $types) )
							$types[] = $field['type'];
					}
				}
			}
		}

		inner_build($this->template, $types);

		return $types;
	}

	function _enfactor($arr)
	{
		$mb            =& $this;
		$fields        = $arr;
		$field_objects = array();

		foreach ($fields as $field)
		{
			if($field['type'] == 'group' and $field['repeating'])
			{
				$field_objects[$field['name']] = $this->_enfactor_group($field, $mb, true);
			}
			else if($field['type'] == 'group' and !$field['repeating'])
			{
				$field_objects[$field['name']] = $this->_enfactor_group($field, $mb, false);
			}
			else
			{
				$field_objects[$field['name']] = $this->_enfactor_field($field, $mb);
			}
		}

		return $field_objects;
	}

	function _enbind($fields)
	{
		foreach ($fields as $name => $field)
		{
			if(is_array($field))
			{
				foreach ($field['groups'] as $group)
				{
					$this->_enbind($group['childs']);
				}
			}
			else
			{
				$bind = $field->get_binding();
				$val  = $field->get_value();
				if(!empty($bind) and is_null($val))
				{
					$bind   = explode('|', $bind);
					$func   = $bind[0];
					$params = $bind[1];
					$params = preg_split('/[\s,]+/', $params);
					$values = array();
					foreach ($params as $param)
					{
						if(array_key_exists($param, $fields))
						{
							$values[] = $fields[$param]->get_value();
						}
					}
					$result = call_user_func_array($func, $values);

					if(VP_Util_Reflection::is_multiselectable($field))
					{
						$result = (array) $result;
					}
					else
					{
						if(is_array($result))
						{
							$result = reset($result);
						}
						$result = (String) $result;
					}
					$field->set_value($result);
				}
				if($field instanceof VP_Control_FieldMulti)
				{
					$bind = $field->get_items_binding();
					if(!empty($bind))
					{
						$bind   = explode('|', $bind);
						$func   = $bind[0];
						$params = $bind[1];
						$params = preg_split('/[\s,]+/', $params);
						$values = array();
						foreach ($params as $param)
						{
							if(array_key_exists($param, $fields))
							{
								$values[] = $fields[$param]->get_value();
							}
						}
						$items  = call_user_func_array($func, $values);
						$field->add_items_from_array($items);
					}
				}
			}
		}
	}

	function _endep($fields)
	{

		if(!function_exists('loop_fields'))
		{
			function loop_fields(&$fields)
			{
				foreach ($fields as &$field)
				{
					if(is_array($field))
					{
						foreach ($field['groups'] as &$group)
						{
							loop_fields($group['childs']);
						}
					}

					$dependency = '';
					if($field instanceof VP_Control_Field)
					{
						$dependency = $field->get_dependency();
						if(!empty($dependency))
						{
							$dependency = explode('|', $dependency);
							$func       = $dependency[0];
							$params     = $dependency[1];
						}
					}
					else
					{
						if(isset($field['dependency']))
						{
							if(!empty($field['dependency']))
							{
								$dependency = $field['dependency'];
								$func       = $dependency['function'];
								$params     = $dependency['field'];
							}
						}
					}

					if(!empty($dependency))
					{
						$params     = preg_split('/[\s,]+/', $params);
						$values     = array();
						foreach ($params as $param)
						{
							if(array_key_exists($param, $fields))
							{
								$values[] = $fields[$param]->get_value();
							}
						}
						$result  = call_user_func_array($func, $values);
						if(!$result)
						{
							if($field instanceof VP_Control_Field)
							{
								$field->is_hidden(true);
								if($field->is_hidden())
									$field->add_container_extra_classes('vp-hide');

								$field->add_container_extra_classes('vp-dep-inactive');
							}
							else
							{
								$field['is_hidden'] = true;
								if($field['is_hidden'])
									$field['container_extra_classes'][] = 'vp-hide';
									
								$field['container_extra_classes'][] = 'vp-dep-inactive';
							}
						}
					}
				}
			}
		}

		loop_fields($fields);

		return $fields;
	}

	function _enfactor_field($field, $mb, $in_group = false)
	{
		$is_multi = VP_Util_Reflection::is_multiselectable($field['type']);

		if( !$is_multi )
		{
			$mb->the_field($field['name']);
		}
		else
		{
			$mb->the_field($field['name'], WPALCHEMY_FIELD_HINT_CHECKBOX_MULTI);
		}
		$field['name'] = $mb->get_the_name();

		// create the object
		$make     = VP_Util_Reflection::field_class_from_type($field['type']);
		$vp_field = call_user_func("$make::withArray", $field);

		// get value from mb
		$value    = $mb->get_the_value();
		// get default from array
		$default  = $vp_field->get_default();

		// if tocopy always assign default
		if( $mb->is_parent_multi() and $mb->is_in_multi_last() )
		{
			$value = $default;
		}
		else
		{
			// if value is null and default exist, use default
			if( is_null($value) and !is_null($default) and empty($this->meta) )
			{
				$value = $default;
			}
			// if not then set up value from mb
			else
			{
				if( VP_Util_Reflection::is_multiselectable($field['type']) )
				{
					if( !is_array($value) and !is_null($value) )
						$value = array( $value );
					else if( is_null($value) )
						$value = array();
				}
			}
		}
		$vp_field->set_value($value);

		if (!$in_group)
		{
			$vp_field->add_container_extra_classes(array('vp-meta-single'));
		}

		return $vp_field;
	}

	function _enfactor_group($field, $mb, $repeating)
	{
		$ignore       = array('type', 'length', 'fields');
		$groups       = array();
		$indexed_name = '';
		$level        = null;
		if($repeating)
		{
			while($mb->have_fields_and_multi($field['name']))
			{
				if ($indexed_name === '') $indexed_name = $mb->get_the_loop_group_id();
				if (is_null($level)) $level = $mb->get_the_loop_level();
				$fields = array();
				foreach ($field['fields'] as $f)
				{
					if($f['type'] === 'group')
	 					$fields[$f['name']] = $this->_enfactor_group($f, $mb, $f['repeating']);
					else
	 					$fields[$f['name']] = $this->_enfactor_field($f, $mb, true);
				}
				$groups[] = array(
					'name'   => $mb->get_the_loop_group_name(true),
					'childs' => $fields
				);
			}
		}
		else
		{
			$length = isset($field['length']) ? $field['length'] : 1;
			while($mb->have_fields($field['name'], $length))
			{
				if ($indexed_name === '') $indexed_name = $mb->get_the_loop_group_id();
				if (is_null($level)) $level = $mb->get_the_loop_level();
				$fields = array();
				foreach ($field['fields'] as $f)
				{
					if($f['type'] === 'group')
	 					$fields[$f['name']] = $this->_enfactor_group($f, $mb, $f['repeating']);
					else
	 					$fields[$f['name']] = $this->_enfactor_field($f, $mb, true);
				}
				$groups[] = array(
					'name'   => $mb->get_the_loop_group_name(true),
					'childs' => $fields
				);
			}
		}
		// assign groups
		$group['groups']       = $groups;
		$group['indexed_name'] = $indexed_name;
		$group['level']        = $level;

		// assign other information
		$keys = array_keys($field);
		foreach ($keys as $key)
		{
			if(!in_array($key, $ignore))
			{
				$group[$key] = $field[$key];
			}
		}

		// sortable
		if(isset($group['sortable']) and $group['sortable'])
			$group['container_extra_classes'][] = 'vp-sortable';

		return $group;
	}

	function _enview($fields)
	{
		foreach ($fields as $name => $field)
		{
			if( is_array($field) and $field['repeating'] )
			{
				echo $this->_render_repeating_group($field);
			}
			else if( is_array($field) and !$field['repeating'] )
			{
				echo $this->_render_group($field);
			}
			else
			{
				echo $this->_render_field($field);
			}
		}
	}

	function _render_field($field)
	{
		return $field->render();
	}

	function _render_group($group)
	{
		$name       = $group['name'];
		$uid        = $group['indexed_name'];
		$oddity     = ($group['level'] % 2 === 0) ? 'even' : 'odd';
		$dependency = isset($group['dependency']) ? $group['dependency']['function'] . '|' . $group['dependency']['field'] : '';

		$html  = '';
		$html .= '<div id="wpa_loop-' . $uid
				. '" class="vp-wpa-loop level-' . $oddity . ' wpa_loop wpa_loop-' . $name . ' vp-fixed-loop vp-meta-group'
				. (isset($group['container_extra_classes']) ? (' ' . implode(' ', $group['container_extra_classes'])) : '')
				. '"'
				. VP_Util_Text::return_if_exists(isset($dependency) ? $dependency : '', 'data-vp-dependency="%s"')
				. '>';

		$icon = '';
		if(isset($group['sortable']) and $group['sortable'])
			$icon = '<i class="fa fa-move"></i> ';

		foreach ($group['groups'] as $g)
		{
			$is_first = false;
			if ($g === reset($group['groups'])){ $is_first = true;}

			$html .= '<div id="'. $g['name'] .'" class="vp-wpa-group wpa_group wpa_group-' . $name . '">';
			$html .= '<div class="vp-wpa-group-heading"><a href="#" class="vp-wpa-group-title">' . $icon . $group['title'] . '</a></div>';
			$html .= '<div class="vp-controls' . ((!$is_first) ? ' vp-hide' : '') . '">';

			foreach ($g['childs'] as $f)
			{

				if( is_array($f) and $f['repeating'] )
					$html .= $this->_render_repeating_group($f);
				else if( is_array($f) and !$f['repeating'] )
					$html .= $this->_render_group($f);
				else
					$html .= $this->_render_field($f);
			}

			$html .= '</div>';
			$html .= '</div>';
		}

		$html .= '</div>';

		return $html;
	}

	function _render_repeating_group($group)
	{
		$name       = $group['name'];
		$uid        = $group['indexed_name'];
		$oddity     = ($group['level'] % 2 === 0) ? 'even' : 'odd';
		$dependency = isset($group['dependency']) ? $group['dependency']['function'] . '|' . $group['dependency']['field'] : '';

		$html  = '';
		$html .= '<div id="wpa_loop-' . $uid
				. '" class="vp-wpa-loop level-' . $oddity . ' wpa_loop wpa_loop-' . $name . ' vp-repeating-loop vp-meta-group'
				. (isset($group['container_extra_classes']) ? (' ' . implode(' ', $group['container_extra_classes'])) : '')
				. '"'
				. VP_Util_Text::return_if_exists(isset($dependency) ? $dependency : '', 'data-vp-dependency="%s"')
				. '>';

		$icon = '';
		if(isset($group['sortable']) and $group['sortable'])
			$icon = '<i class="fa fa-move"></i> ';

		foreach ($group['groups'] as $g)
		{
			$class    = '';
			$is_first = false;
			$is_last  = false;
			if ($g === end($group['groups'])){ $is_last = true; $class = ' last tocopy';}
			if ($g === reset($group['groups'])){ $is_first = true; $class = ' first';}

			$html .= '<div id="'. $g['name'] .'" class="vp-wpa-group wpa_group wpa_group-' . $name . $class . '">';
			$html .= '<div class="vp-wpa-group-heading"><a href="#" class="vp-wpa-group-title">' . $icon . $group['title'] . '</a><a href="#" class="dodelete vp-wpa-group-remove" title="'. __('Remove', 'vp_textdomain') .'"><i class="fa fa-times"></i> '. __('Remove', 'vp_textdomain') .'</a></div>';
			$html .= '<div class="vp-controls' . ((!$is_first) ? ' vp-hide' : '') . '">';
			if ($g === end($group['groups']))
			{
				$tocopy = $g['name'] . '[tocopy]';
				$html  .= '<input type="hidden" class="tocopy-hidden" name="' . $tocopy . '" value="1">';
			}
			foreach ($g['childs'] as $f)
			{

				if( is_array($f) and $f['repeating'] )
					$html .= $this->_render_repeating_group($f);
				else if( is_array($f) and !$f['repeating'] )
					$html .= $this->_render_group($f);
				else
					$html .= $this->_render_field($f);
			}
			$html .= '</div>';
			$html .= '</div>';
		}

		$html .= '<div class="vp-wpa-group-add">';
		$html .= '<a href="#" class="button button-large docopy-' . $name . '">'. __('Add More', 'vp_textdomain') . ' ' . $group['title'] . '</a>';
		$html .= '</div>';

		$html .= '</div>';

		return $html;
	}

	function _save($post_id) 
	{
		// skip saving if dev mode is on
		if($this->is_dev_mode)
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

		// clean to copy and reset array indexes
		$this->_clean_tocopy($new_data);

		if (empty($new_data))
		{
			$new_data = NULL;
		}

		// filter: save
		if ($this->has_filter('save'))
		{
			$new_data = $this->apply_filters('save', $new_data, $real_post_id);

			/**
			 * halt saving
			 * @since 1.3.4
			 */
			if (FALSE === $new_data) return $post_id;

			$this->_clean_tocopy($new_data);
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

	private function _clean_tocopy(&$arr)
	{
		if(is_array($arr))
		{
			foreach ($arr as $key => $value)
			{
				if(is_array($value))
				{
					$this->_clean_tocopy($arr[$key]);
					if(array_key_exists('tocopy', $value))
					{
						unset($arr[$key]);
					}
				}
			}
			if (!count($arr)) 
			{
				$arr = array();
			}
			else
			{
				$keys = array_keys($arr);

				$is_numeric = TRUE;

				foreach ($keys as $key)
				{
					if (!is_numeric($key)) 
					{
						$is_numeric = FALSE;
						break;
					}
				}

				if ($is_numeric)
				{
					$arr = array_values($arr);
				}
			}
		}
	}

}

/**
 * EOF
 */