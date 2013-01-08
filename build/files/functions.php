<?php

/**
 * Bootstraping Vafpress Option Framework
 * - Build your option at vafpress/config/options.xml.
 * - run 'php vp convert' under vafpress directory to build your options.xml into options.php array.
 * - Configure theme at vafpress/config/vafpress.php.
 * - Configure JS Messages at vafpress/config/messages.php.
 */
require_once 'vafpress/bootstrap.php';

function disable_user_profile() {
	if ( is_admin() ) {
		$user  = wp_get_current_user();
		$roles = $user->roles;
		if ( in_array('demo', $roles) )
			wp_redirect( admin_url() . 'themes.php?page=' . VP_Util_Config::get_instance()->load('option/vafpress', 'menu_page_slug') );
	}
}
add_action( 'load-profile.php', 'disable_user_profile' );
add_action( 'load-user-edit.php', 'disable_user_profile' );

/*
 * EOF
 */