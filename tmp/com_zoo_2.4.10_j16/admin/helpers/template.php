<?php
/**
* @package   com_zoo Component
* @file      template.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: TemplateHelper
		The general helper Class for template
*/
class TemplateHelper extends AppHelper {

	/*
		Function: __construct
			Class Constructor.
	*/
	public function __construct($app) {
		parent::__construct($app);

		// load class
		$this->app->loader->register('AppTemplate', 'classes:template.php');
	}

	/*
		Function: create
			Get a template instance

		Returns:
			AppParameter
	*/
	public function create($args = array()) {
		$args = (array) $args;
		return $this->app->object->create('AppTemplate', $args);
	}

}