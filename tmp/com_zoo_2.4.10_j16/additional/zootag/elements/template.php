<?php
/**
* @package   ZOO Tag
* @file      template.php
* @version   2.4.1
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// load config
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

class JElementTemplate extends JElement {

	var	$_name = 'Template';

	function fetchElement($name, $value, &$node, $control_name) {

		$app = App::getInstance('zoo');

		// create select
		$path    = dirname(dirname(__FILE__)).'/tmpl';
		$options = array();

		if (is_dir($path)) {
			foreach (JFolder::files($path, '^([-_A-Za-z]*)\.php$') as $tmpl) {
				$tmpl = basename($tmpl, '.php');
				$options[] = $app->html->_('select.option', $tmpl, ucwords($tmpl));
			}
		}

		return $app->html->_('select.genericlist', $options, $control_name.'['.$name.']', '', 'value', 'text', $value);
	}

}