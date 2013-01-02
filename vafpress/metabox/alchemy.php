<?php

/**
 * Extended version of WPAlchemy Class
 * so that it can process metabox using an array specification
 * and compatible with all Vafpress Framework Option Controls.
 */
class VP_MetaBox_Alchemy extends WPAlchemy_MetaBox
{

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
			$this->_enview($this->template);
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
				$group = $field['name'];

				while($mb->have_fields_and_multi($group))
				{
					$mb->the_group_open();
					echo '<a href="#" class="dodelete button" style="float:right;">Remove</a>';
					foreach ($field['fields'] as $f)
					{
						$this->_render_field($f, $mb);
					}
					$mb->the_group_close();
				}
				echo '<p><a href="#" class="docopy-' . $group . ' button">Add</a></p>';
			}
			else
			{
				$this->_render_field($field, $mb);
			}
		}
	}

	function _render_field($field, $mb)
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
		$make     = 'VP_Option_Field_' . $field['type'];
		$vp_field = call_user_func("$make::withArray", $field);

		// get value from mb
		$value    = $mb->get_the_value();
		// get default from array
		$default  = $vp_field->get_default();

		// if value is null and default exist, use default
		if( empty($value) and !empty($default) )
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

		echo '<div class="vp-'.$field['type'].'">';
		echo $vp_field->render();
		echo '</div>';

		/**
		 * @todo
		 * - test every elements
		 * - push
		 * - begin on shortcode
		 */
	}

}