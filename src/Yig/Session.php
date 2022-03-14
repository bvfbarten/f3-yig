<?php

/*
	Copyright (c) 2022 Brady Barten.

	This is free software: you can redistribute it and/or modify it under the
	terms of the GNU General Public License as published by the Free Software
	Foundation, either version 3 of the License, or later.
*/

namespace DB\Yig;

//! Yig-managed session handler
class Session extends \DB\Jig\Session {

	/**
	*	Return database type
	*	@return string
	**/
	function dbtype() {
		return 'Yig';
	}
	protected
		//! Session ID
		$sid,
		//! Anti-CSRF token
		$_csrf,
		//! User agent
		$_agent,
		//! IP,
		$_ip,
		//! Suspect callback
		$onsuspect;


	/**
	*	Instantiate class
	*	@param $db \DB\Yig
	*	@param $file string
	*	@param $onsuspect callback
	*	@param $key string
	**/
	function __construct(\DB\Yig $db,$file='sessions',$onsuspect=NULL,$key=NULL) {
		parent::__construct($db,$file);
		$this->onsuspect=$onsuspect;
		session_set_save_handler(
			[$this,'open'],
			[$this,'close'],
			[$this,'read'],
			[$this,'write'],
			[$this,'destroy'],
			[$this,'cleanup']
		);
		register_shutdown_function('session_commit');
		$fw=\Base::instance();
		$headers=$fw->HEADERS;
		$this->_csrf=$fw->hash($fw->SEED.
			extension_loaded('openssl')?
				implode(unpack('L',openssl_random_pseudo_bytes(4))):
				mt_rand()
			);
		if ($key)
			$fw->$key=$this->_csrf;
		$this->_agent=isset($headers['User-Agent'])?$headers['User-Agent']:'';
		$this->_ip=$fw->IP;
	}

}
