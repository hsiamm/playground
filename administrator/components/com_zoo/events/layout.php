<?php
/**
* @package   com_zoo Component
* @file      layout.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: LayoutEvent
		Layout events.
*/
class LayoutEvent {

	public static function init($event) {

		$app = $event->getSubject();

		$extensions = $event->getReturnValue();

		// get modules
		foreach ($app->path->dirs('modules:') as $module) {
			if ($app->path->path("modules:$module/renderer")) {
				$extensions[] = array('type' => 'modules', 'name' => $module, 'path' => $app->path->path("modules:$module"));
			}
		}

		// get plugins
		foreach ($app->path->dirs('plugins:') as $plugin_type) {
			foreach ($app->path->dirs('plugins:'.$plugin_type) as $plugin) {
				if ($app->path->path("plugins:$plugin_type/$plugin/renderer")) {
					$extensions[] = array('type' => 'plugin', 'name' => $plugin, 'path' => $app->path->path("plugins:$plugin_type/$plugin"));
				}
			}
		}

		$event->setReturnValue($extensions);

	}

}
