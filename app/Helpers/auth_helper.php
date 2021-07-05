<?php

use Config\Services;

if (! function_exists('logged_in'))
{
	/**
	 * Checks to see if the user is logged in.
	 *
	 * @return bool
	 */
	function logged_in()
	{
		return session()->isLoggedIn;
	}
}

if (! function_exists('user'))
{

	function user()
	{
		
		return session()->userData;
	}
}

if (! function_exists('user_id'))
{

	function user_id()
	{
		
		return session()->userData('uid');
	}
}
