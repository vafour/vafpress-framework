<?php

class VP_WP_User
{

	public static function get_users()
	{
		global $wpdb;
		if(function_exists('get_users'))
		{
			$wp_users = get_users();
			$result   = array();
			foreach ($wp_users as $user)
			{
				if( property_exists( $user, 'data' ) )
					$user = $user->data;
				$result[] = array('id' => $user->ID, 'display_name' => $user->display_name);
			}
		}
		else
		{
			$wp_user_search = $wpdb->get_results("SELECT ID, display_name FROM $wpdb->users ORDER BY ID");
			foreach ( $wp_user_search as $userid )
			{
				$user_id       = (int) $userid->ID;
				$display_name  = stripslashes($userid->display_name);
				$result[] = array('id' => $user_id, 'display_name' => $display_name);
			}
		}
		return $result;
	}

	public static function get_editable_roles()
	{
		global $wp_roles;
		if(!isset($wp_roles))
		{
			$wp_roles = new WP_Roles();
		}
		$all_roles      = $wp_roles->roles;
		$editable_roles = apply_filters('editable_roles', $all_roles);
		return $editable_roles;
	}

}