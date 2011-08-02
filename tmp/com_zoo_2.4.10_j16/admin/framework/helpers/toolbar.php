<?php
/**
* @package   com_zoo Component
* @file      toolbar.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: ToolbarHelper
		Toolbar helper class. Wrapper for JToolBarHelper.
*/
class ToolbarHelper extends AppHelper {

	/*
		Function: __call
			Map all functions to JToolBarHelper class

		Parameters:
			$name - Method name
			$args - Method arguments

		Returns:
			Mixed
	*/	
    public function __call($method, $args) {
		return $this->_call(array('JToolBarHelper', $method), $args);
    }
	
}