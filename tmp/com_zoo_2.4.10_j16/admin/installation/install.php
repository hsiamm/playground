<?php
/**
* @package   com_zoo Component
* @file      install.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$GLOBALS['ZOO_COMPONENT_INSTALLER'] = $this;

function com_install() {

	$installer = $GLOBALS['ZOO_COMPONENT_INSTALLER'];

	// init vars
	$path = dirname(dirname(__FILE__));

	// load install script
	require_once($path.'/file.script.php');

	return Com_ZOOInstallerScript::install($installer);

}