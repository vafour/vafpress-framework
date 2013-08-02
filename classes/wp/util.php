<?php

class VP_WP_Util
{

	public static function admin_notice($message, $is_error = false)
	{
		if ($is_error)
			echo '<div class="error">';
		else
			echo '<div class="updated">';

		echo "<p><strong>$message</strong></p></div>";
	}

}