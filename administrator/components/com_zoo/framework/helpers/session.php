<?php
/**
* @package   com_zoo Component
* @file      session.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: SessionHelper
		Session helper class. Wrapper for JSession.
*/
class SessionHelper extends AppHelper {

	/*
		Function: __call
			Map all functions to JSession class

		Parameters:
			$name - Method name
			$args - Method arguments

		Returns:
			Mixed
	*/	
    public function __call($method, $args) {
		return $this->_call(array($this->app->system->session, $method), $args);
    }
		
}