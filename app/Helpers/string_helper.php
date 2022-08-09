<?php

if (!function_exists('strContains')) {
    function strContains($_text, $_wordtToFind)
	{
		return strpos($_text, $_wordtToFind) !== false ? true : false;
	}
}