<?php
/**
* @package   com_zoo Component
* @file      category.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: CategoryEvent
		Category events.
*/
class CategoryEvent {

	public static function init($event) {

		$category = $event->getSubject();

	}

	public static function saved($event) {

		$category = $event->getSubject();
		$new = $event['new'];

	}

	public static function deleted($event) {

		$category = $event->getSubject();

	}

	public static function stateChanged($event) {

		$category = $event->getSubject();
		$old_state = $event['old_state'];
		
	}

}
