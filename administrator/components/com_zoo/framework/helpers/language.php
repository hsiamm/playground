<?php
/**
* @package   com_zoo Component
* @file      language.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: LanguageHelper
		Language helper class. Wrapper for JLanguage/JText.
*/
class LanguageHelper extends AppHelper {

	/*
		Function: l
			Translates a string into the current language

		Parameters:
			$string - String to translate
			$js_safe - Make the result javascript safe

		Returns:
			Mixed
	*/	
	public function l($string, $js_safe = false) {
		return $this->app->system->language->_($string, $js_safe);
	}
	
}