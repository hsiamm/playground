<?php
/**
* @package   ZOO Tag
* @file      mod_zootag.php
* @version   2.4.1
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// load config
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

// get app
$zoo = App::getInstance('zoo');

// init vars
$path = dirname(__FILE__);

// register helpers
$zoo->path->register($path, 'helpers');
$zoo->loader->register('TagModuleHelper', 'helpers:helper.php');

// init vars
$application = $zoo->table->application->get($params->get('application', 0));

// is application ?
if (empty($application)) {
	return null;
}

// get tags
$tags = $zoo->tagmodule->buildTagCloud($application, $params);

// load template
include(JModuleHelper::getLayoutPath('mod_zootag', $params->get('theme', 'list')));