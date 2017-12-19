<?php

///////////////////////////////////////////////////////////////////////////////////
// PHP Class that convert numbers to words strings and vice versa
// Similar to GfyCat/Twitch's URL naming scheme
//
// GfyCat Naming Convention: https://medium.com/@Gfycat/naming-conventions-97960fc40179
//
// Usage:
//
// require("wordInt.class.php");
//
// $word   = wordInt::number_to_string(57893);
// $number = wordInt::string_to_number("FloodsNeedyCharm");
//
///////////////////////////////////////////////////////////////////////////////////
//
// Alternates:
// HashIDs: http://hashids.org/
///////////////////////////////////////////////////////////////////////////////////

class wordInt {
	public static $bits_per_word = 12;
	public static $debug         = 0;
	public static $word_file     = __DIR__ . "/dictionary.txt";

	static function string_to_number($str) {
		$list = self::get_word_list();
		// Split the string at all of the capital letters
		$parts = preg_split('/(?=[A-Z])/',$str, -1, PREG_SPLIT_NO_EMPTY);

		$ret = array();
		// Find the XX bit interger for each word
		foreach ($parts as $p) {
			$p     = strtolower($p);
			$index = array_search($p,$list);

			if (self::$debug) {
				print "$p = $index<br />";
			}

			if ($index !== false) {
				$ret[] = $index;
			} else {
				trigger_error("Word '$p' not found in dictionary",E_USER_ERROR);
			}
		}

		// Conver the XX bit sections back in to an actual long integer
		$int = 0;
		for ($i = 0; $i < count($ret); $i++) {
			$num = $ret[$i];
			$int += $num << ($i * self::$bits_per_word);
		}

		return $int;
	}

	static function number_to_string($number) {
		$list = self::get_word_list();

		$number = doubleval($number);

		if ($number < 0) {
			trigger_error("Negative numbers not supported ($number)",E_USER_ERROR);
		}

		$ret = "";

		// Number of XX bit chunks it will take to store this number
		//$groups = ceil(strlen(sprintf('%b', $number)) / self::$bits_per_word);
		$groups = ceil(log($number + 1) / (self::$bits_per_word * log(2)));

		for ($i = 0; $i < $groups; $i++) {
			$section = $number >> (self::$bits_per_word * $i) & (2**self::$bits_per_word - 1);
			$word    = ucfirst($list[$section]);

			if (self::$debug) {
				print "$section = $word<br />";
			}
			$ret .= $word;
		}

		return $ret;
	}

	static function get_word_list($file = "") {
		// Memoize the wordlist for speed
		static $ret;

		if ($ret) {
			return $ret;
		}

		if (!$file) {
			$file = self::$word_file;
		}

		if (!is_readable($file)) {
			trigger_error("Dictionary file '$file' not found",E_USER_ERROR);
		}

		$i   = file($file);
		$ret = array_map("trim",$i);

		$total  = count($ret);
		$needed = 2**self::$bits_per_word;

		if ($needed > $total) {
			trigger_error("Not enough data in dictionary file. Need $needed but only found $total.",E_USER_ERROR);
		}

		return $ret;
	}
}
