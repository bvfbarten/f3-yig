<?php

/*

	Copyright (c) 2022 Brady Barten.

	This is free software: you can redistribute it and/or modify it under the
	terms of the GNU General Public License as published by the Free Software
	Foundation, either version 3 of the License, or later.

*/

namespace DB;

use DB\Cig\Csv;

//! In-memory/flat-file DB wrapper
class Cig extends \DB\Jig {

	//@{ Storage formats
	const
		FORMAT_YAML=0,
		FORMAT_CSV=1;
	//@}

	/**
	*	Read data from memory/file
	*	@return array
	*	@param $file string
	**/
	function &read($file) {
		if (!$this->dir || !is_file($dst=$this->dir.$file)) {
			if (!isset($this->data[$file]))
				$this->data[$file]=[];
			return $this->data[$file];
		}
		if ($this->lazy && isset($this->data[$file]))
			return $this->data[$file];
		$fw=\Base::instance();
		$raw=$fw->read($dst);
		switch ($this->format) {
			case self::FORMAT_CSV:
				$rawData = Csv::parse($raw);
				$data = [];
				foreach($rawData as $counter => $line) {
				       $data[$line['_id'] !== "" ? $line['_id'] : $counter] = $line;	
				}
				break;
			case self::FORMAT_YAML:
				throw new \Exception('Use DB\Yig');
				break;
		}
		$this->data[$file] = $data;
		return $this->data[$file];
	}

	/**
	*	Write data to memory/file
	*	@return int
	*	@param $file string
	*	@param $data array
	**/
	function write($file,array $data=NULL) {
		if (!$this->dir || $this->lazy)
			return count($this->data[$file]=$data);
		$fw=\Base::instance();
		switch ($this->format) {
			case self::FORMAT_CSV:
				foreach($data as $_id => $line) {
					$data[$_id]['_id'] = (
						(isset($line['_id']) && $line['_id'] !== "" && $line['_id'] !== null) 
						? $line['_id'] 
						: $_id
					);
				}
				$out=Csv::dump($data);
				break;
			case self::FORMAT_YAML:
				throw new \Exception('Use DB\Yig');
				break;
		}
		return $fw->write($this->dir.$file,$out);
	}

	/**
	*	Instantiate class
	*	@param $dir string
	*	@param $format int
	**/
	function __construct($dir=NULL,$format=self::FORMAT_CSV,$lazy=FALSE) {
		if ($dir && !is_dir($dir))
			mkdir($dir,\Base::MODE,TRUE);
		$this->uuid=\Base::instance()->hash($this->dir=$dir);
		$this->format=$format;
		$this->lazy=$lazy;
	}

}

