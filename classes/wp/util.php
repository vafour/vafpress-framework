<?php

class VP_WP_Util
{

	public static function kses_html($html)
	{
		$allow = array_merge(wp_kses_allowed_html( 'post' ), array(
			'link' => array(
				'href' => true,
				'rel'  => true,
				'type' => true,
			)
		));
		return wp_kses($html, $allow);
	}

	public static function admin_notice($message, $is_error = false)
	{
		if ($is_error)
			echo '<div class="error">';
		else
			echo '<div class="updated">';

		echo "<p><strong>$message</strong></p></div>";
	}

}