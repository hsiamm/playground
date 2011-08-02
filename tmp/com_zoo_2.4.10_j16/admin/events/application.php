<?php
/**
* @package   com_zoo Component
* @file      application.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: ApplicationEvent
		Application events.
*/
class ApplicationEvent {

	public static function init($event) {

		$application = $event->getSubject();

		// load site language
		if ($application->app->system->application->isSite()) {
			$application->app->system->language->load('com_zoo', $application->getPath(), null, true);
		}

	}

	public static function saved($event) {

		$application = $event->getSubject();
		$new = $event['new'];

	}

	public static function deleted($event) {

		$application = $event->getSubject();
		
	}

}
