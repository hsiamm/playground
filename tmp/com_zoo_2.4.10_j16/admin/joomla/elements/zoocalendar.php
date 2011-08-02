<?php
/**
* @package   com_zoo Component
* @file      zoocalendar.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// load config
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

class JElementZooCalendar extends JElement {

	var	$_name = 'ZooCalendar';

	protected static $_count = 1;	
	
	function fetchElement($name, $value, &$node, $control_name) {

		// get app
		$app = App::getInstance('zoo');

		// init vars
		$id    = 'calendar-'.self::$_count++;
		$name  = $control_name.'['.$name.']';
		$class = $node->attributes('class') ? $node->attributes('class') : 'inputbox';

		// create html
		$html[] = '<div class="zoo-calendar">';
		$html[] = $app->html->_('zoo.calendar', $value, $name, $id, array('class' => $class), true);
		$html[] = '</div>';
		
		return implode("\n", $html);
	}

}