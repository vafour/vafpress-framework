<?php

/**
 * Gathering statistical data for framework development
 * No personal data collected
 */
if(!class_exists('VP_WP_Tracker'))
{
	class VP_WP_Tracker
	{

		function schedule_track()
		{
			add_filter( 'cron_schedules', array( $this, 'add_schedule' ) );

			if ( !wp_next_scheduled( 'vpf_tracking' ) ) {
				wp_schedule_event( time(), 'weekly', 'vpf_tracking' );
			}
			add_action( 'vpf_tracking', array( $this, 'track' ) );
		}

		function add_schedule()
		{
			// Adds once weekly to the existing schedules.
			$schedules['weekly'] = array(
				'interval' => 60 * 60 * 24 * 7,
				'display' => __( 'Once Weekly', 'vp_textdomain' )
			);
			return $schedules;
		}

		function track($old_theme = null)
		{
			$wp_root_path = str_replace('/wp-content/themes', '', get_theme_root());

			// site data
			$data['site'] = $this->get_site_data();

			// theme data
			if(!is_null($old_theme))
			{
				$data['theme']          = $this->get_theme_data($old_theme);
				$data['site']['active'] = false;
			}
			else
			{
				$data['theme']          = $this->get_theme_data();
				$data['site']['active'] = true;
			}
			
			// send to server
			$args = array(
				'timeout'   => 10,
				'sslverify' => false,
				'body'      => array('data' => $data),
			);

			$response = wp_remote_post('https://api.vafpress.com/tracker/sites', $args);
		}

		function get_site_data()
		{
			$site = array(
				'url'       => site_url(),
				'name'      => get_bloginfo( 'name' ),
				'version'   => get_bloginfo( 'version' ),
				'multisite' => is_multisite(),
				'lang'      => get_locale(),
			);
			return $site;
		}

		function get_theme_data($theme_slug = null)
		{

			// setup wp < 3.4 theme path
			$theme_path = get_stylesheet_directory() . '/style.css';
			if(!is_null($theme_slug))
			{
				$theme_path = get_stylesheet_directory() . '/../' . $theme_slug . '/style.css';
			}

			$theme = array();
			if(function_exists('wp_get_theme'))
			{
				if(!is_null($theme_slug))
					$theme_data = wp_get_theme($theme_slug);
				else
					$theme_data = wp_get_theme();
					
				$theme      = array(
					'name'       => $theme_data->get('Name'),
					'theme_uri'  => $theme_data->get('ThemeURI'),
					'version'    => $theme_data->get('Version'),
					'author'     => $theme_data->get('Author'),
					'author_uri' => $theme_data->get('AuthorURI')
				);
				if(isset($theme_data->template) && !empty($theme_data->template) && $theme_data->parent())
				{
					$theme['template'] = array(
						'name'       => $theme_data->parent()->get('Name'),
						'theme_uri'  => $theme_data->parent()->get('ThemeURI'),
						'version'    => $theme_data->parent()->get('Version'),
						'author'     => $theme_data->parent()->get('Author'),
						'author_uri' => $theme_data->parent()->get('AuthorURI'),
					);
				}
			}
			else
			{
				$theme_data = (object) get_theme_data( $theme_path );
				$theme = array(
					'name'       => $theme_data->Name,
					'theme_uri'  => $theme_data->URI,
					'version'    => $theme_data->Version,
					'author'     => $theme_data->AuthorName,
					'author_uri' => $theme_data->AuthorURI,
				);
				if(isset($theme_data->Template) and $theme_data->Template !== '')
				{
					$parent_theme_data = (object) get_theme_data( get_stylesheet_directory() . '/../' . $theme_data->Template . '/style.css' );
					$theme['template'] = array(
						'name'       => $parent_theme_data->Name,
						'theme_uri'  => $parent_theme_data->URI,
						'version'    => $parent_theme_data->Version,
						'author'     => $parent_theme_data->AuthorName,
						'author_uri' => $parent_theme_data->AuthorURI,
					);
				}
			}
			return $theme;
		}

	}	
}

/**
 * EOF
 */