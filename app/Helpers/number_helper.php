<?php

if (!function_exists('toTriliun')) {
	function toTriliun($argNumber)
	{
		return number_format((float)$argNumber / 1000000000000, 2, ',', '') . ' T';
	}
}

if (!function_exists('toRupiah')) {
	function toRupiah($argNumber, $withRp = true, $desimal = 0)
	{
		return ($withRp ? 'Rp. ' : '') . number_format($argNumber / 1000, $desimal, ',', '.');
	}
}

if (!function_exists('toMilyar')) {
	function toMilyar($argNumber, $withRp = true, $decimal = 0)
	{
		return ($withRp ? 'Rp. ' : '') . number_format($argNumber / 1000000000, $decimal, ',', '.');
	}
}

if (!function_exists('onlyTwoDecimal')) {
	function onlyTwoDecimal($argNumber)
	{
		return number_format($argNumber, 2, '.', '');
	}

	if (!function_exists('formatNumber')) {
		function formatNumber($argNumber)
		{
			return number_format($argNumber, 0, ',', '.');
		}
	}
}
