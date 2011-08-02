<?php
/**
* @package   com_zoo Component
* @file      json.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: JSONData
		Read/Write data in JSON format.
*/
class JSONData extends AppData {

    /*
		Variable: _assoc
			Returned object's will be converted into associative array's.
    */
	protected $_assoc = true;

	/*
		Function: __construct
			Constructor
	*/	
	public function __construct($data = array()) {
		
		// decode JSON string
		if (is_string($data)) {
			$data = $this->_read($data);
		}
		
		parent::__construct($data);
	}

	/*
		Function: _read
			Decode JSON string
	*/	
	protected function _read($json = '') {
		return json_decode($json, $this->_assoc);
	}

	/*
		Function: _write
			Encode JSON string
	*/
	protected function _write($data) {
		return json_encode($data);
	}
	
}