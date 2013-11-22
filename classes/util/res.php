<?php

class VP_Util_Res
{

	public static function is_font_awesome($icon)
	{
		if (strpos($icon, 'font-awesome:') === 0)
		{
			return trim(str_replace('font-awesome:', '', $icon));
		}
		return false;
	}

	public static function get_preview_from_url($url)
	{
		$preview = '';
		$images  = array('jpg', 'JPG', 'jpeg', 'bmp',  'gif',  'png', 'ico');
		
		if(filter_var($url, FILTER_VALIDATE_URL) !== FALSE)
		{
			// check for extension, if it has extension then use it
			$info = pathinfo($url);
			if(isset($info['extension']))
			{
				if(in_array($info['extension'], $images))
				{
					$preview = $url;
				}
				else
				{
					$type = wp_ext2type( $info['extension'] );
					if(is_null($type))
						$type = 'default';
					$preview = includes_url() . 'images/crystal/' . $type . '.png';
				}
			}
			else
			{
				// if no extension, try to discover from mime
				$mime = wp_remote_head( $url );
				if(!is_wp_error( $mime ))
				{
					$mime = $mime['headers']['content-type'];
					if(strpos($mime, 'image') === 0)
						$preview = $url;
					else
						$preview = wp_mime_type_icon( $mime );
				}
				else
				{
					$preview = includes_url() . 'images/crystal/' . 'default' . '.png';
				}
			}
		}

		return $preview;
	}

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