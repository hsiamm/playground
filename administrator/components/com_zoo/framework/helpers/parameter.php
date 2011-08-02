<?php
/**
* @package   com_zoo Component
* @file      parameter.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: ParameterHelper
		The general helper Class for parameter
*/
class ParameterHelper extends AppHelper {

	/*
		Function: create
			Get a menu instance

		Returns:
			AppParameter
	*/
	public function create($param = array()) {
		return new AppParameter($this->app, $param);
	}

}

/*
	Class: AppParameter
		Parameter Class.
*/
class AppParameter {

    /*
		Variable: _data
			Parameter data.
    */
	protected $_data;

    /*
		Variable: app
			App instance.
    */
	public $app;

	/*
		Function: __construct
			Constructor

		Parameters:
			$data - Array or Object
	*/
	public function __construct($app, $data = array()) {
		$this->app = $app;

		if ($data instanceof JRegistry) {
			$data = $data->toArray();
		} else if (is_string($data) && (substr($data, 0, 1) != '{') && (substr($data, -1, 1) != '}')) {
			$data = JRegistryFormat::getInstance('INI')->stringToObject($data);
		}

		$this->_data = $app->data->create($data, 'json');
	}

	/*
		Function: get
			Get a parameter

		Parameters:
			$name - Name of the parameter
			$default - Default value, return if parameter was not found

		Returns:
			Mixed
	*/
	public function get($name, $default = null) {
		$name = (string) $name;

		if (preg_match('/\.$/', $name)) {

			$values = array();

			foreach ($this->_data as $key => $value) {
				if (strpos($key, $name) === 0) {
					$values[substr($key, strlen($name))] = $value;
				}
			}

			if (!empty($values)) {
				return $values;
			}

		} else if ($this->_data->offsetExists($name)) {
			return $this->_data->offsetGet($name);
		}

		return $default;
	}

	/*
		Function: set
			Set a parameter

		Parameters:
			$name - Name of the parameter
			$value - Value of the parameter

		Returns:
			AppParameter
	*/
	public function set($name, $value) {
		$name = (string) $name;

		if (preg_match('/\.$/', $name)) {

			$values = is_object($value) ? get_object_vars($value) : is_array($value) ? $value : array();

			foreach ($values as $key => $val) {
				$this->_data->offsetSet($name.$key, $val);
			}

		} else {
			$this->_data->offsetSet($name, $value);
		}

		return $this;
	}

	/*
		Function: remove
			Remove a parameter

		Parameters:
			$name - Name of the parameter

		Returns:
			AppParameter
	*/
	public function remove($name) {
		$name = (string) $name;

		if (preg_match('/\.$/', $name)) {

			$keys = array();

			foreach ($this->_data as $key => $value) {
				if (strpos($key, $name) === 0) {
					$keys[] = $key;
				}
			}

			foreach ($keys as $key) {
				$this->_data->offsetUnset($key);
			}

		} else {
			$this->_data->offsetUnset($name);
		}

		return $this;
	}

	/*
		Function: clear
			Clear parameter data

		Returns:
			AppParameter
	*/
	public function clear() {
		$this->_data = $this->app->data->create('', 'json');
		return $this;
	}

	/*
		Function: toArray
			Get parameter as array

		Returns:
			Array
	*/
	public function toArray() {
		return (array) $this->_data;
	}

	/*
		Function: getData
			Get parameter as AppData

		Returns:
			AppData
	*/
	public function getData() {
		return $this->_data;
	}

 	/*
		Function: __toString
			Get string (via magic method)

		Returns:
			String
	*/
    public function __toString() {
        return $this->_data->__toString();
    }

	/*
		Function: loadArray
			Load a associative array of values

		Parameters:
			$array - Array of values

		Returns:
			AppParameter
	*/
	public function loadArray($array) {

		foreach ($array as $name => $value) {
			$this->_data->offsetSet($name, $value);
		}

		return $this;
	}

	/*
		Function: loadArray
			Load accessible non-static variables of a object

		Parameters:
			$object - Object with values

		Returns:
			AppParameter
	*/
	public function loadObject($object) {

		if (is_object($object)) {
			foreach (get_object_vars($object) as $name => $value) {
				$this->_data->offsetSet($name, $value);
			}
		}

		return $this;
	}

}