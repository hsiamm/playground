<?php
/**
* @package   com_zoo Component
* @file      loader.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: LoaderHelper
		Class loader helper class. Wrapper for JLoader.
*/
class LoaderHelper extends AppHelper {

	/*
		Function: register
			Register a class with the loader.

		Parameters:
			$class - Class name
			$file - File name

		Returns:
			Void
	*/
	public function register($class, $file) {
		if (!class_exists($class)) {
			return JLoader::register($class, $this->app->path->path($file));
		}
	}

	/*
		Function: __call
			Map all functions to JLoader class

		Parameters:
			$name - Method name
			$args - Method arguments

		Returns:
			Mixed
	*/	
    public function __call($method, $args) {
		return $this->_call(array('JLoader', $method), $args);
    }
	
}