<?php

return array(

	////////////////////////////////////////
	// Localized JS Message Configuration //
	////////////////////////////////////////

	/**
	 * Validation Messages
	 */
	'validation' => array(
		'alphabet'     => __('Value needs to be Alphabet', 'vp_textdomain'),
		'alphanumeric' => __('Value needs to be Alphanumeric', 'vp_textdomain'),
		'numeric'      => __('Value needs to be Numeric', 'vp_textdomain'),
		'email'        => __('Value needs to be Valid Email', 'vp_textdomain'),
		'url'          => __('Value needs to be Valid URL', 'vp_textdomain'),
		'maxlength'    => __('Length needs to be less than {0} characters', 'vp_textdomain'),
		'minlength'    => __('Length needs to be more than {0} characters', 'vp_textdomain'),
		'maxselected'  => __('Select no more than {0} items', 'vp_textdomain'),
		'minselected'  => __('Select at least {0} items', 'vp_textdomain'),
		'required'     => __('This is required', 'vp_textdomain'),
	),

	/**
	 * Import / Export Messages
	 */
	'util' => array(
		'import_success'    => __('Import succeed, option page will be refreshed..', 'vp_textdomain'),
		'import_failed'     => __('Import failed', 'vp_textdomain'),
		'export_success'    => __('Export succeed, copy the JSON formatted options', 'vp_textdomain'),
		'export_failed'     => __('Export failed', 'vp_textdomain'),
		'restore_success'   => __('Restoration succeed, option page will be refreshed..', 'vp_textdomain'),
		'restore_nochanges' => __('Options identical to default', 'vp_textdomain'),
		'restore_failed'    => __('Restoration failed', 'vp_textdomain'),
	),

	/**
	 * Control Fields String
	 */
	'control' => array(
		// select2 select box
		'select2_placeholder' => __('Select option(s)', 'vp_textdomain'),
		// fontawesome chooser
		'fac_placeholder'     => __('Select an Icon', 'vp_textdomain'),
	),

);

/**
 * EOF
 */