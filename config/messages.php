<?php

return array(

	////////////////////////////////////////
	// Localized JS Message Configuration //
	////////////////////////////////////////

	/**
	 * Validation Messages
	 */
	'validation' => array(
		'alphabet'     => __('Value need to be Alphabet', 'vp_textdomain'),
		'alphanumeric' => __('Value need to be Alphanumeric', 'vp_textdomain'),
		'numeric'      => __('Value need to be Numeric', 'vp_textdomain'),
		'email'        => __('Value need to be Valid Email', 'vp_textdomain'),
		'url'          => __('Value need to be Valid URL', 'vp_textdomain'),
		'maxlength'    => __('Length need to be less than {0} characters', 'vp_textdomain'),
		'minlength'    => __('Length need to be more than {0} characters', 'vp_textdomain'),
		'maxselected'  => __('Select no more than {0} items', 'vp_textdomain'),
		'minselected'  => __('Select at least {0} items', 'vp_textdomain'),
		'required'     => __('This is Required', 'vp_textdomain'),
	),

	/**
	 * Import / Export Messages
	 */
	'util' => array(
		'import_success'    => __('Import success, option page will be refreshed..', 'vp_textdomain'),
		'import_failed'     => __('Import failed', 'vp_textdomain'),
		'export_success'    => __('Export success, copy the serialized options', 'vp_textdomain'),
		'export_failed'     => __('Export failed', 'vp_textdomain'),
		'restore_success'   => __('Restore success, option page will be refreshed..', 'vp_textdomain'),
		'restore_nochanges' => __('Options identical to default.', 'vp_textdomain'),
		'restore_failed'    => __('Restore failed', 'vp_textdomain'),
	),

);

/**
 * EOF
 */