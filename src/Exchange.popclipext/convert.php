<?php

function getCode($mark) {
	$currencyMap = [
		'$' => 'USD',
		'€' => 'EUR',
		'¥' => 'JPY',
		'£' => 'GBP',
	];
	return $currencyMap[$mark];
}

function getMark($currency) {
	$currencyMap = [
		'JPY' => '¥',
		'USD' => '$',
		'EUR' => '€',
		'GBP' => '£',
	];
	return $currencyMap[$currency];
}

function getPatterns() {
	$patterns = [
		'USD' => ['/\$(\d+\.?\d*)/', '/(\d+)ドル/', '/(\d+)USD/'],
		'EUR' => ['/€(\d+)/', '/(\d+)ユーロ/', '/(\d+)EUR/'],
		'GBP' => ['/£(\d+)/', '/(\d+)ポンド/', '/(\d+)GBP/'],
	];
	return $patterns;
}

function getRate($quotes, $from, $to) {
	foreach ($quotes as $currency) {
		if ($currency['currencyPairCode'] === "${from}${to}") {
			return $currency['ask'];
		}
	}
	return null;
}

function addMark($num, $currency) {
	$mark = getMark($currency);
	return "${mark}${num}";
}

class Converter
{
	public $json;

	function __construct($json_str) {
		$this->json = json_decode($json_str, true);
	}

	function convert($str) {
		$str = str_replace(',', '', $str);
		$quotes = $this->json['quotes'];
		$to_currency = 'JPY';
    
		$patterns = getPatterns();
		foreach ($patterns as $currency => $patterns) {
			foreach ($patterns as $pattern) {
				$match = preg_match($pattern, $str, $matches);
				if ($match) {
					$current = $matches[1];
					$rate = getRate($quotes, $currency, $to_currency);
					$money = 1.0 * $current * $rate;
					return addMark($money, $to_currency);
				}
			}
		}
		return "no match";
	}
}

if ($input = getenv('POPCLIP_TEXT')) {
	$url = 'https://www.gaitameonline.com/rateaj/getrate';
	$json_str = file_get_contents($url);
	$converter = new Converter($json_str);
	echo $converter->convert($input);
}
