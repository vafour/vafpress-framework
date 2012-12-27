<?php

class VP_Util_Res
{

	public static function img($url)
	{
		// empty parameter
		if (empty($url)) {
			return '';
		}

		// if already absolute, then just return
		if (parse_url($url, PHP_URL_SCHEME))
		{
			return $url;
		}
		else
		{
			// check if got beginning slash
			if ($url[0] == '/' or $url[0] == '\\')
			{
				return VP_IMAGE_URL . $url;
			}
			return VP_IMAGE_URL . '/' . $url;
		}
	}

	public static function img_out($img, $default)
	{
		if (empty($img))
			echo self::img($default);
		else
			echo self::img($img);
	}

}