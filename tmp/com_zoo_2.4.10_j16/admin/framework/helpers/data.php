<?php
/**
* @package   com_zoo Component
* @file      data.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: DataHelper
		Data helper class.
*/
class DataHelper extends AppHelper {

	/*
		Function: __construct
			Class Constructor.
	*/
	public function __construct($app) {
		parent::__construct($app);

		// load class
		$this->app->loader->register('AppData', 'classes:data.php');
	}

	/*
		Function: create
			Retrieve a data object

		Parameters:
			$data - Data
			$format - Data format

		Returns:
			Mixed
	*/
	public function create($data = array(), $format = 'json') {
		
		// load data class
		$class = $format.'Data';
		$this->app->loader->register($class, 'data:'.strtolower($format).'.php');

		return new $class($data);
	}
	
}