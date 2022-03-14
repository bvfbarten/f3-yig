<?php

/*
	Copyright (c) 2022 Brady Barten.

	This is free software: you can redistribute it and/or modify it under the
	terms of the GNU General Public License as published by the Free Software
	Foundation, either version 3 of the License, or later.
*/

namespace DB\Yig;

//! Flat-file DB mapper
class Mapper extends \DB\Jig\Mapper {

	/**
	*	Return database type
	*	@return string
	**/
	function dbtype() {
		return 'Yig';
	}

	/**
	*	Instantiate class
	*	@return void
	*	@param $db object
	*	@param $file string
	**/
	function __construct(\DB\Yig $db,$file) {
		$this->db=$db;
		$this->file=$file;
		$this->reset();
	}

}
