<?php
/**
* @package   com_zoo Component
* @file      object.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: ObjectHelper
		Helper for creating objects
*/
class ObjectHelper extends AppHelper {
    
	/*
		Function: create
			Create a object by classname

		Parameters:
			$class - Classname
			$args - Constructor arguments

		Returns:
			Mixed
	*/
	public function create($class, $args = array()) {

		// load class
		$this->app->loader->register($class, 'classes:'.strtolower($class).'.php');

		// use reflection or new for object creation
		if (count($args) > 0) {
			$reflection = new ReflectionClass($class);
			$object = $reflection->newInstanceArgs($args);
		} else {
			$object = new $class();
		}
		
		// add reference to related app instance
		if (property_exists($object, 'app')) {
			$object->app = $this->app;
		}
		
		return $object;
	}

}