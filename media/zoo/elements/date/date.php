<?php
/**
* @package   com_zoo Component
* @file      date.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// register ElementRepeatable class
App::getInstance('zoo')->loader->register('ElementRepeatable', 'elements:repeatable/repeatable.php');

/*
   Class: ElementDate
   The date element class
*/
class ElementDate extends ElementRepeatable implements iRepeatSubmittable {

	const EDIT_DATE_FORMAT = '%Y-%m-%d %H:%M:%S';

	/*
		Function: render
			Renders the repeatable element.

	   Parameters:
            $params - render parameter

		Returns:
			String - html
	*/
	protected function _render($params = array()) {	
		return $this->app->html->_('date', $this->_data->get('value', ''), $this->app->date->format((($params['date_format'] == 'custom') ? $params['custom_format'] : $params['date_format'])), $this->app->date->getOffset());
	}

	/*
	   Function: _edit
	       Renders the repeatable edit form field.

	   Returns:
	       String - html
	*/		
	protected function _edit(){
		$value = $this->_data->get('value', '');
		$value = !empty($value) ? $this->app->html->_('date', $value, $this->app->date->format(self::EDIT_DATE_FORMAT), $this->app->date->getOffset()) : '';

		return $this->app->html->_('zoo.calendar', $value, 'elements[' . $this->identifier . ']['.$this->index().'][value]', 'elements[' . $this->identifier . ']['.$this->index().']value', array('class' => 'calendar-element'), true);
	}

	/*
		Function: _renderSubmission
			Renders the element in submission.

	   Parameters:
            $params - submission parameters

		Returns:
			String - html
	*/
	public function _renderSubmission($params = array()) {
		return $this->app->html->_('zoo.calendar', $this->_data->get('value', ''), 'elements[' . $this->identifier . ']['.$this->index().'][value]', 'elements[' . $this->identifier . ']['.$this->index().']value', array('class' => 'calendar-element'), true);
	}

	/*
		Function: _validateSubmission
			Validates the submitted element

	   Parameters:
            $value  - AppData value
            $params - AppData submission parameters

		Returns:
			Array - cleaned value
	*/
	public function _validateSubmission($value, $params) {
        $value = $this->app->validator->create('date', array('required' => $params->get('required')), array('required' => 'Please choose a date.'))
				->addOption('date_format', self::EDIT_DATE_FORMAT)
				->clean($value->get('value'));

		return compact('value');
	}

}

class ElementDateData extends ElementData{

	public function encodeData() {
		$xml = $this->app->xml->create($this->_element->getElementType())->addAttribute('identifier', $this->_element->identifier);
		$value = $this->_data->get('value', '');
		if (!empty($value)) {
			$tzoffset = $this->app->date->getOffset();
			$date     = $this->app->date->create($value, $tzoffset);
			$value	  = $date->toMySQL();
		}
		
		$xml->addChild('value', $value, null, true);
		
		return $xml;
	}

}