<?php
/**
* @package   com_zoo Component
* @file      countries.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class JElementCountries extends JElement {

	function fetchElement($name, $value, $node, $control_name) {

		$app = App::getInstance('zoo');

		// get element from parent parameter form
		$element = $this->_parent->element;
		$config  = $element->getConfig();
		
		return $app->html->_('zoo.countryselectlist', $app->country->getIsoToNameMapping(), $control_name.'[selectable_countries][]', $config->get('selectable_countries', array()), true);
	}
	
}