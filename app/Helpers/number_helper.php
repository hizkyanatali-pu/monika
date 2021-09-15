<?php

if (!function_exists('toTriliun')) {
	function toTriliun($argNumber) {
		return number_format((float)$argNumber / 1000000000000, 2, ',', '') . ' T';
	}
}

if (!function_exists('toRupiah')) {
	function toRupiah($argNumber, $withRp = true) {
		return ($withRp ? 'Rp. ' : '') . number_format($argNumber, 0, ',', '.');
	}
}

if (!function_exists('onlyTwoDecimal')) {
	function onlyTwoDecimal($argNumber) {
		return number_format($argNumber, 2, '.', '');
	}
}