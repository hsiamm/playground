<?php
/**
* @package   com_zoo Component
* @file      types.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class JElementTypes extends JElement {

	function fetchElement($name, $value, &$node, $control_name) {

		$app = App::getInstance('zoo');

		// get element from parent parameter form
		$element 	 = $this->_parent->element;
		$config  	 = $element->getConfig();
		$application = $this->_parent->application;

		// init vars
		$attributes = array();
		$attributes['class'] = $node->attributes('class') ? $node->attributes('class') : 'inputbox';
		$attributes['multiple'] = 'multiple';
		$attributes['size'] = $node->attributes('size') ? $node->attributes('size') : '';

		foreach ($application->getTypes() as $type) {
			$options[] = $app->html->_('select.option', $type->id, JText::_($type->name));
		}

		return $app->html->_('select.genericlist', $options, $control_name.'[selectable_types][]', $attributes, 'value', 'text', $config->get('selectable_types', array()));
	}

}