<?php

return array(

	///////////////////////////////
	// Main Option Configuration //
	///////////////////////////////

	/**
	 * Will always load option values from option.php/xml default values, not DB,
	 * so you can play with the option.php/xml freely.
	 */
	'dev_mode'          => true,

	/**
	 * Whether to use import and export menu
	 */
	'impexp'            => true,

	/**
	 * Automatically assign 'name' to each grouping class
	 */
	'auto_group_naming' => true,

	/**
	 * Option name in DB
	 */
	'option_key'        => 'vp_option',

	/**
	 * Minimum Capabilities to access the option page
	 */
	'role'              => 'edit_theme_options',

	/**
	 * Slug of option page menu under appereance
	 */
	'menu_page_slug'    => 'vp_theme_option',

);

/**
 * EOF
 */