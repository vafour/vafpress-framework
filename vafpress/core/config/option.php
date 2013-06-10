<?php

return array(

	///////////////////////////////
	// Main Option Configuration //
	///////////////////////////////

	/**
	 * Will always load option values from option.php/xml default values, not DB,
	 * so you can play with the option.php/xml freely.
	 */
	'dev_mode'           => true,

	/**
	 * Whether to use import and export menu
	 */
	'impexp'             => true,

	/**
	 * Automatically assign 'name' to each grouping class
	 */
	'auto_group_naming'  => true,

	/**
	 * Option name in DB
	 */
	'option_key'         => 'vp_option',

	/**
	 * Minimum Capabilities to access the option page
	 */
	'role'               => 'edit_theme_options',

	/**
	 * Browser page title
	 */
	'browser_page_title' => 'Vafpress Option Panel',

	/**
	 * Menu label
	 */
	'menu_page_label'    => 'Theme Options',

	/**
	 * Slug of option page menu under appereance
	 */
	'menu_page_slug'     => 'vp_theme_option',

	/**
	 * Whether to use fixed layout or fluid layout for Option Panel
	 */
	'fixed_layout'       => true,

	/**
	 * Theme text domain, to define a new one, change the value here, and then do a search and replace
	 * in vafpress folder replacing the old value of this option with the new one.
	 */
	'text_domain'        => 'vp_textdomain'

);

/**
 * EOF
 */