<?php
/**
* @package   com_zoo Component
* @file      document.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: DocumentHelper
		Document helper class. Wrapper for JDocument.
*/
class DocumentHelper extends AppHelper {

	/*
		Function: addStylesheet
			Add stylesheet to the document.

		Parameters:
			$path - Path to css file

		Returns:
			Void
	*/
	public function addStylesheet($path) {
		$this->app->system->document->addStylesheet($this->app->path->url($path));
	}

	/*
		Function: script
			Add javascript to the document.

		Parameters:
			$path - Path to js file

		Returns:
			Void
	*/
	public function addScript($path) {
		$this->app->system->document->addScript($this->app->path->url($path));
	}

	/*
		Function: __call
			Map all functions to JDocument class

		Parameters:
			$name - Method name
			$args - Method arguments

		Returns:
			Mixed
	*/	
    public function __call($method, $args) {
		return $this->_call(array($this->app->system->document, $method), $args);
    }
	
}