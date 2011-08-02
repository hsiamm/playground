<?php
/**
* @package   com_zoo Component
* @file      validator.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: ValidatorHelper
		The general helper Class for validators
*/
class ValidatorHelper extends AppHelper {

	/*
		Function: __construct
			Class Constructor.
	*/
	public function __construct($app) {
		parent::__construct($app);

		// load class
		$this->app->loader->register('AppValidator', 'classes:validator.php');
	}

	/*
		Function: create
			Creates a validator instance

		Parameters:
			$type - Validator type

		Returns:
			AppParameterForm
	*/
	public function create($type = '') {

		$args = func_get_args();
		$type = array_shift($args);

		$class = 'AppValidator' . $type;

		// load class
		$this->app->loader->register($class, 'classes:validator.php');
		return $this->app->object->create($class, $args);

	}

}