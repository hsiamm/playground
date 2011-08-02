<?php
/**
* @package   com_zoo Component
* @file      date.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: DateHelper
		Date helper class.
*/
class DateHelper extends AppHelper {

	public function create($time = 'now', $offset = 0) {
		return $this->_call(array('JFactory', 'getDate'), array($time, $offset));
	}

	public function isToday($date) {

		// get dates
		$now  = $this->create();
		$date = $this->create($date);

		return date('Y-m-d', $date->toUnix(true)) == date('Y-m-d', $now->toUnix(true));
	}

	public function isYesterday($date) {

		// get dates
		$now  = $this->create();
		$date = $this->create($date);

		return date('Y-m-d', $date->toUnix(true)) == date('Y-m-d', $now->toUnix(true) - 86400);
	}

	public function getDeltaOrWeekdayText($date) {

		// get dates
		$now   = $this->create();
		$date  = $this->create($date);
		$delta = $now->toUnix(true) - $date->toUnix(true);

		if ($this->isToday($date->toMySQL())) {
			$hours = intval($delta / 3600);
			$hours = $hours > 0 ? $hours.JText::_('hr') : '';
			$mins  = intval(($delta % 3600) / 60);
			$mins  = $mins > 0 ? ' '.$mins.JText::_('min') : '';
			$delta = $hours.$mins ? JText::sprintf('%s ago', $hours.$mins) : JText::_('1min ago');
		} else {
			$delta = JHTML::_('date', $date->toMySQL(true), JText::_('DATE_FORMAT_LC3').' %H:%M');
		}

		return $delta;
	}
	
}