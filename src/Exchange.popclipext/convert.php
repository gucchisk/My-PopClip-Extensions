<?php

$MARKS = [
  'JPY' => '¥',
  'USD' => '$',
  'EUR' => '€',
  'GBP' => '£',
  'AUD' => 'A$',
  'CAD' => 'C$',
  'NZD' => 'NZ$',
  'CHF' => 'Fr',
  'ZAR' => 'R',
];

$PREFIXES = [
  'JPY' => ["¥", "￥"],
  'USD' => ["^\\$", "US[D\$]"],
  'EUR' => ["€"],
  'GBP' => ["£"],
  'AUD' => ["AU[D\$]", "^A\\$"],
  'CAD' => ["CDN\\$", "CA[D\$]", "C\\$"],
  'NZD' => ["NZ[D\$]"],
  'CHF' => ["Fr"],
  'ZAR' => ["^R"],
];
$SUFFIXES = [
  'JPY' => ["円", "YEN"],
  'USD' => ["ドル", "USD"],
  'EUR' => ["ユーロ", "EUR"],
  'GBP' => ["ポンド", "GBP"],
];

function getPatterns() {
  global $PREFIXES, $SUFFIXES;
  $num = '(\d+\.?\d*)';
	$patterns = [];
  foreach ($PREFIXES as $currency => $prefixes) {
    $patterns[$currency] = [];
    foreach ($prefixes as $prefix) {
      array_push($patterns[$currency], "/${prefix}${num}/i");
    }
    if (!array_key_exists($currency, $SUFFIXES)) {
      continue;
    }
    foreach ($SUFFIXES[$currency] as $suffix) {
      array_push($patterns[$currency], "/${num}${suffix}/i");
    }
  }
 	return $patterns;
}

function getRate($quotes, $from, $to) {
	foreach ($quotes as $currency) {
		if ($currency['currencyPairCode'] === "${from}${to}") {
			return 1.0 * $currency['ask'];
		}
    if ($currency['currencyPairCode'] === "${to}${from}") {
      return 1.0 / $currency['ask'];
    }
	}
	return null;
}

function addMark($num, $currency) {
  global $MARKS;
	$mark = $MARKS[$currency];
	return "${mark}${num}";
}

class Converter
{
	public $json;

	function __construct($json_str) {
		$this->json = json_decode($json_str, true);
	}

	function convert($str, $to) {
		$str = str_replace(',', '', $str);
    $str = str_replace(' ', '', $str);
		$quotes = $this->json['quotes'];
		$patterns = getPatterns();
		foreach ($patterns as $currency => $patterns) {
			foreach ($patterns as $pattern) {
				$match = preg_match($pattern, $str, $matches);
				if ($match) {
					$current = $matches[1];
          if ($currency === $to) {
            return addMark($current, $to);
          }
					$rate = getRate($quotes, $currency, $to);
					$money = floor(1.0 * $current * $rate * 100) / 100;
					return addMark($money, $to);
				}
			}
		}
		return "no match";
	}
}

$currency=getenv('POPCLIP_OPTION_CURRENCY');
if ($input = getenv('POPCLIP_TEXT')) {
	$url = 'https://www.gaitameonline.com/rateaj/getrate';
	$json_str = file_get_contents($url);
	$converter = new Converter($json_str);
	echo $converter->convert($input, $currency);
}
