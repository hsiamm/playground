<?php
/**
* @package   com_zoo Component
* @file      menu.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: MenuHelper
		The general helper Class for menu
*/
class MenuHelper extends AppHelper {

	protected static $_menus = array();

	/*
		Function: __construct
			Class Constructor.
	*/
	public function __construct($app) {
		parent::__construct($app);

		// load class
		$this->app->loader->register('AppTree', 'classes:tree.php');
		$this->app->loader->register('AppMenu', 'classes:menu.php');
	}

	/*
		Function: getInstance
			Get a menu instance

		Parameters:
			$name - Menu name

		Returns:
			AppMenu
	*/
	public function get($name) {

		if (isset($this->_menus[$name])) {
			return $this->_menus[$name];
		}

		$this->_menus[$name] = $this->app->object->create('AppMenu', array($name));

		return $this->_menus[$name];
	}

}