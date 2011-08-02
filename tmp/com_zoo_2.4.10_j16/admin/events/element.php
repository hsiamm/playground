<?php
/**
* @package   com_zoo Component
* @file      element.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: ElementEvent
		Element events.
*/
class ElementEvent {

	public static function beforeDisplay($event) {

		$item = $event->getSubject();
		$element = $event['element'];

		// element will not be rendered if $event['render'] is set to false
		// $event['render'] = false;

	}

	public static function afterDisplay($event) {
		
		$item = $event->getSubject();
		$element = $event['element'];

		// set $event['html'] after modifying the html
		// $html = $event['html'];
		// $event['html'] = $html;
	}

	public static function configForm($event) {

		$element = $event->getSubject();
		$form = $event['form'];

	}

	public static function configXML($event) {

		$element = $event->getSubject();
		$xml = $event['xml'];

	}

	public static function download($event) {

		$download_element = $event->getSubject();
		$check = $event['check'];
	}

	public static function afterEdit($event) {
		
		$element = $event->getSubject();

		// set $event['html'] after modifying the html
		// $html = $event['html'];
		// $event['html'] = $html;
	}
	
}