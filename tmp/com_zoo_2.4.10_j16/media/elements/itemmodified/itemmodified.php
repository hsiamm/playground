<?php
/**
* @package   com_zoo Component
* @file      itemmodified.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: ElementItemModified
		The item modified element class
*/
class ElementItemModified extends Element {

	/*
		Function: hasValue
			Checks if the element's value is set.

	   Parameters:
			$params - render parameter

		Returns:
			Boolean - true, on success
	*/
	public function hasValue($params = array()) {
		return true;
	}
	
	/*
	   Function: edit
	       Renders the edit form field.

	   Returns:
	       String - html
	*/
	public function edit() {
		return null;
	}
		
	/*
		Function: render
			Renders the element.

	   Parameters:
            $params - render parameter

		Returns:
			String - html
	*/
	public function render($params = array()) {
		
		$format = $params['date_format'];
		
		if ($format == 'custom') {
			$format = $params['custom_format'];
		}

		if (!empty($this->_item)) {
			return $this->app->html->_('date', $this->_item->modified, $this->app->date->format($format), $this->app->date->getOffset());
		}
	}
	
}