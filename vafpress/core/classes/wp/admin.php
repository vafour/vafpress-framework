<?php

class VP_WP_Admin
{

	/**
	 * [taken from WPAlchemy Class by Dimas Begunoff]
	 * Used to check if creating or editing a post or page
	 *
	 * @static
	 * @access	private
	 * @return	string "post" or "page"
	 */
	public static function is_post_or_page()
	{
		$post_type = self::get_current_post_type();

		if (isset($post_type))
		{
			if ('page' == $post_type)
			{
				return 'page';
			}
			else
			{
				return 'post';
			}
		}

		return NULL;
	}

	/**
	 * [taken from WPAlchemy Class by Dimas Begunoff]
	 * Used to check for the current post type, works when creating or editing a
	 * new post, page or custom post type.
	 *
	 * @static
	 * @return	string [custom_post_type], page or post
	 */
	public static function get_current_post_type()
	{
		
		if(!class_exists('WPAlchemy_MetaBox'))
		{
			require_once VP_FileSystem::instance()->resolve_path('includes', 'wpalchemy/MetaBox');
		}

		$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : NULL ;

		if ( isset( $uri ) )
		{
			$uri_parts = parse_url($uri);

			$file = basename($uri_parts['path']);

			if ($uri AND in_array($file, array('post.php', 'post-new.php')))
			{
				$post_id = WPAlchemy_MetaBox::_get_post_id();

				$post_type = isset($_GET['post_type']) ? $_GET['post_type'] : NULL ;

				$post_type = $post_id ? get_post_type($post_id) : $post_type ;

				if (isset($post_type))
				{
					return $post_type;
				}
				else
				{
					// because of the 'post.php' and 'post-new.php' checks above, we can default to 'post'
					return 'post';
				}
			}
		}

		return NULL;
	}

}