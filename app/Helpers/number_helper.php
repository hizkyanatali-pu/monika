<?php


if (!function_exists('toRupiah')) {
	function rupiahFormat($argNumber, $withRp = true, $desimal = 0)
	{
		$number = ($argNumber > 0) ? number_format($argNumber, $desimal, ',', '.') : 0;
		return ($withRp ? 'Rp. ' : '') . $number;
	}
}

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

if (!function_exists('checkMorT')) {
	function checkMorT($argNumber, $withRp = true, $decimal = 0)
	{
		$explodeCheck = explode('.', (string)$argNumber);
		$check = strlen($explodeCheck[0]);
		
		if ($check < 10) {
			return ($withRp ? 'Rp. ' : '') . number_format($argNumber / 1000000000, $decimal, ',', '.') . " M";
		}
		elseif ($check >= 10 and $check < 13) {
			return ($withRp ? 'Rp. ' : '') . number_format($argNumber / 1000000000, $decimal, ',', '.') . " M";
		} elseif ($check >= 13) {
			return ($withRp ? 'Rp. ' : '') . number_format($argNumber / 1000000000000, $decimal, ',', '.') . " T";
		}
	}
}
