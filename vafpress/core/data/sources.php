<?php

/**
 * Here is the place to put your own defined function that serve as
 * datasource to field with multiple options.
 */

function vp_get_categories()
{
	$wp_cat = get_categories(array('hide_empty' => 0 ));

	$result = array();
	foreach ($wp_cat as $cat)
	{
		$result[] = array('value' => $cat->cat_ID, 'label' => $cat->name);
	}
	return $result;
}

function vp_get_users()
{
	$wp_users = VP_WP_User::get_users();

	$result = array();
	foreach ($wp_users as $user)
	{
		$result[] = array('value' => $user['id'], 'label' => $user['display_name']);
	}
	return $result;
}

function vp_get_posts()
{
	$wp_posts = get_posts();

	$result = array();
	foreach ($wp_posts as $post)
	{
		$result[] = array('value' => $post->ID, 'label' => $post->post_title);
	}
	return $result;
}

function vp_get_pages()
{
	$wp_pages = get_pages();

	$result = array();
	foreach ($wp_pages as $page)
	{
		$result[] = array('value' => $page->ID, 'label' => $page->post_title);
	}
	return $result;
}

function vp_get_tags()
{
	$wp_tags = get_tags(array('hide_empty' => 0));
	$result = array();
	foreach ($wp_tags as $tag)
	{
		$result[] = array('value' => $tag->term_id, 'label' => $tag->name);
	}
	return $result;
}

function vp_get_roles()
{
	$result         = array();
	$editable_roles = VP_WP_User::get_editable_roles();

	foreach ($editable_roles as $key => $role)
	{
		$result[] = array('value' => $key, 'label' => $role['name']);
	}

	return $result;
}

function vp_get_gwf_family()
{
	$fonts = file_get_contents(dirname(__FILE__) . '/gwf.json');
	$fonts = json_decode($fonts);

	$fonts = array_keys(get_object_vars($fonts));

	foreach ($fonts as $font)
	{
		$result[] = array('value' => $font, 'label' => $font);
	}

	return $result;
}

function vp_get_gwf_weight($face)
{
	if(empty($face))
		return array();
	
	$fonts   = file_get_contents(dirname(__FILE__) . '/gwf.json');
	$fonts   = json_decode($fonts);
	$weights = $fonts->{$face}->weights;

	foreach ($weights as $weight)
	{
		$result[] = array('value' => $weight, 'label' => $weight);
	}

	return $result;
}

function vp_get_gwf_style($face)
{
	if(empty($face))
		return array();
	
	$fonts   = file_get_contents(dirname(__FILE__) . '/gwf.json');
	$fonts   = json_decode($fonts);
	$styles = $fonts->{$face}->styles;

	foreach ($styles as $style)
	{
		$result[] = array('value' => $style, 'label' => $style);
	}

	return $result;
}

function vp_copy_content($value, $value2)
{
	$args = func_get_args();
	return implode('', $args);
}

function vp_simple_shortcode($name = "", $url = "", $image = "")
{
	if(is_null($name))  $name = '';
	if(is_null($url))   $url = '';
	if(is_null($image)) $image = '';
	$result = "[shortcode name='$name' url='$url' image='$image']";
	return $result;
}


function vp_get_social_medias() {
	$socmeds = array(
		array('value' => 'blogger', 'label' => 'Blogger'),
		array('value' => 'delicious', 'label' => 'Delicious'),
		array('value' => 'deviantart', 'label' => 'DeviantArt'),
		array('value' => 'digg', 'label' => 'Digg'),
		array('value' => 'dribbble', 'label' => 'Dribbble'),
		array('value' => 'email', 'label' => 'Email'),
		array('value' => 'facebook', 'label' => 'Facebook'),
		array('value' => 'flickr', 'label' => 'Flickr'),
		array('value' => 'forst', 'label' => 'Forst'),
		array('value' => 'foursquare', 'label' => 'Foursquare'),
		array('value' => 'github', 'label' => 'Github'),
		array('value' => 'googleplus', 'label' => 'Google+'),
		array('value' => 'instagram', 'label' => 'Instagram'),
		array('value' => 'lastfm', 'label' => 'Last.FM'),
		array('value' => 'linkedin', 'label' => 'LinkedIn'),
		array('value' => 'myspace', 'label' => 'MySpace'),
		array('value' => 'pinterest', 'label' => 'Pinterest'),
		array('value' => 'reddit', 'label' => 'Reddit'),
		array('value' => 'rss', 'label' => 'RSS'),
		array('value' => 'soundcloud', 'label' => 'SoundCloud'),
		array('value' => 'stumbleupon', 'label' => 'StumbleUpon'),
		array('value' => 'tumblr', 'label' => 'Tumblr'),
		array('value' => 'twitter', 'label' => 'Twitter'),
		array('value' => 'vimeo', 'label' => 'Vimeo'),
		array('value' => 'wordpress', 'label' => 'WordPress'),
		array('value' => 'yahoo', 'label' => 'Yahoo!'),
		array('value' => 'youtube', 'label' => 'Youtube'),
	);

	return $socmeds;
}

function vp_get_fontawesome_icons()
{
	// scrape list of icons from fontawesome css
	if( false === ( $icons  = get_transient( 'vp_fontawesome_icons' ) ) )
	{
		$pattern = '/\.(icon-(?:\w+(?:-)?)+):before\s*{\s*content/';
		$subject = file_get_contents(VP_DIR . '/public/css/vendor/font-awesome.min.css');

		preg_match_all($pattern, $subject, $matches, PREG_SET_ORDER);

		$icons = array();

		foreach($matches as $match)
		{
		    $icons[] = array('value' => $match[1], 'label' => $match[1]);
		}
		set_transient( 'vp_fontawesome_icons', $icons, 60 * 60 * 24 );
	}

	return $icons;
}

function vp_bind_bigcontinents()
{
	$bigcontinents = array(
		'Eurafrasia',
		'America',
		'Oceania',
	);

	$result = array();

	foreach ($bigcontinents as $data)
	{
		$result[] = array('value' => $data, 'label' => $data, 'img' => 'http://placehold.it/100x100');
	}

	return $result;
}

function vp_bind_continents($param = '')
{
	$continents = array(
		'Eurafrasia' => array(
			'Africa',
			'Asia',
			'Europe'
		),
		'America' => array(
			'North America',
			'Central America and the Antilles',
			'South America'
		),
		'Oceania' => array(
			'Australasia',
			'Melanesia',
			'Micronesia',
			'Polynesia',
		),
	);

	$result = array();
	$datas  = array();

	if(is_array($param))
		$param = reset($param);

	if(array_key_exists($param, $continents))
		$datas = $continents[$param];

	foreach ($datas as $data)
	{
		$result[] = array('value' => $data, 'label' => $data, 'img' => 'http://placehold.it/100x100');
	}

	return $result;
}

function vp_bind_countries($param = '')
{
	$countries = array(
		'Africa' => array(
			'Algeria',
			'Nigeria',
			'Egypt',
		),
		'Asia' => array(
			'Indonesia',
			'Malaysia',
			'China',
			'Japan',
		),
		'Europe' => array(
			'France',
			'Germany',
			'Italy',
			'Netherlands',
		),
		'North America' => array(
			'United States',
			'Mexico',
			'Canada',
		),
		'Central America and the Antilles' => array(
			'Cuba',
			'Guatemala',
			'Haiti',
		),
		'South America' => array(
			'Argentina',
			'Brazil',
			'Paraguay',
		),
		'Australasia' => array(
			'Australia',
			'New Zealand',
			'Christmas Island',
		),
		'Melanesia' => array(
			'Fiji',
			'Papua New Guinea',
			'Vanuatu',
		),
		'Micronesia' => array(
			'Guam',
			'Nauru',
			'Palau'
		),
		'Polynesia' => array(
			'American Samoa',
			'Samoa',
			'Tokelau',
		),
	);
	$result = array();
	$datas  = array();

	if(is_null($param))
		$param = '';

	if(is_array($param) and !empty($param))
		$param = reset($param);

	if(empty($param))
		$param = '';

	if(array_key_exists($param, $countries))
		$datas = $countries[$param];

	foreach ($datas as $data)
	{
		$result[] = array('value' => $data, 'label' => $data, 'img' => 'http://placehold.it/100x100');
	}

	return $result;
}

function vp_dep_boolean($value)
{
	$args   = func_get_args();
	$result = true;

	foreach ($args as $val)
	{
		$result = ($result and !empty($val));
	}
	return $result;
}

function vp_dep_is_keyword($value)
{
	if($value === 'keyword')
		return true;
	return false;
}

function vp_dep_is_tags($value)
{
	if($value === 'tags')
		return true;
	return false;
}

function vp_bind_color_accent($preset)
{
	switch ($preset) {
		case 'red':
			return '#ff0000';
		case 'green':
			return '#00ff00';
		case 'blue':
			return '#0000ff';
		default:
			return '#000000';
	}
}

function vp_bind_color_subtle($preset)
{
	return vp_bind_color_accent($preset);
}

function vp_bind_color_background($preset)
{
	return vp_bind_color_accent($preset);
}



/**
 * EOF
 */