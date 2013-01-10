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

?>