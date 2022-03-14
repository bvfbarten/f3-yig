<?php


/*
	Copyright (c) 2022 Brady Barten.

	This is free software: you can redistribute it and/or modify it under the
	terms of the GNU General Public License as published by the Free Software
	Foundation, either version 3 of the License, or later.
*/


namespace DB\Cig;

class Csv {

	/**
	*	Return Array of Arrays
	*	@var $str string csv string
	*	@return Array
	**/
	public static function parse(string $str) {
		$f = fopen('php://memory', 'r+');
		fwrite($f, $str);
		rewind($f);
		$keys = null;
		$rtn = [];
		while (($row = fgetcsv($f, 0)) !== FALSE) {
			if (!$keys) {
				$keys = $row;
				continue;
			}
			$line = [];
			foreach($keys as $counter => $key) {
				$line[$key] = $row[$counter] ?? "";
			}
			$rtn[] = $line;	
		}
		return $rtn;
	}
	/**
	*	Return String
	*	@var $values array of arrays
	*	@return string csv
	**/
	public static function dump(array $values) {
		$f = fopen('php://memory', 'r+');
		if (!count($values)) {
			return '';
		}
		foreach($values as $counter => $line) {
			foreach($line as $key => $value) {
				$keys[$key] = $key;
			}
		}
		fputcsv($f, $keys);
		foreach($values as $counter => $line) {
			foreach($keys as $key) {
				$correctLine[$key] = $line[$key] ?? "";
				if (is_object($correctLine[$key]) 
					|| is_array($correctLine[$key])
				) {
					$correctLine[$key] = json_encode(
						$correctLine[$key]
					);
				}	
			}
			fputcsv($f, $correctLine);
		}
		rewind($f);
		return( stream_get_contents($f) );
	}
}
