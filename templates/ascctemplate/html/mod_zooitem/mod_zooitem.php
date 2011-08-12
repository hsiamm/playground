<?php
/**
* @package   ZOO Item
* @file      mod_zooitem.php
* @version   2.4.2
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// load config
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

$zoo = App::getInstance('zoo');

if ($application = $zoo->table->application->get($params->get('application', 0))) {

	$items = $zoo->zoomodule->getItems($params);

	// load template
	if (!empty($items)) {

		// set renderer
		$renderer = $zoo->renderer->create('item')->addPath(array($zoo->path->path('component.site:'), dirname(__FILE__)));

		$layout = $params->get('layout', 'default');

		include(JModuleHelper::getLayoutPath('mod_zooitem', $params->get('theme', 'list-v')));
	}
}