<?php
/**
* @package   com_zoo Component
* @file      facebookilike.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
   Class: ElementFacebookilike
       The Facebook I like element class
*/
class ElementFacebookilike extends Element implements iSubmittable {

	/*
		Function: render
			Override. Renders the element.

	   Parameters:
            $params - render parameter

		Returns:
			String - html
	*/
	public function render($params) {

		// init vars
		$enabled	= $this->_data->get('value');
		$params		= $this->app->data->create($params);
		$height		= $params->get('height');
		$width		= $params->get('width');
		$show_faces = $params->get('show_faces');
		$action		= $params->get('action');
		$layout		= $params->get('layout');
		$colorscheme = $params->get('colorscheme');
		$ref		= $params->get('ref') ? '&amp;ref='.$params->get('ref') : '';
		$locale		= $params->get('locale') ? '&amp;locale='.$params->get('locale') : '';
		
		// render html
		if ($width && $height && $enabled) {

			$permalink = urlencode(JRoute::_($this->app->route->item($this->_item), true, -1));

			$href = "http://www.facebook.com/plugins/like.php?href=$permalink&amp;layout=$layout&amp;show_faces=$show_faces&amp;width=$width&amp;action=$action&amp;colorscheme=$colorscheme&amp;height=$height$ref$locale";
			$html = '<iframe src="'.$href.'" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width: '.$width.'px; height: '.$height.'px;" ></iframe>';
			
			return $html;
		}

		return null;
	}

	/*
	   Function: edit
	       Renders the edit form field.

	   Returns:
	       String - html
	*/
	public function edit() {
		// init vars
		$default = $this->_config->get('default');

		if ($default != '' && $this->_item != null && $this->_item->id == 0) {
			$this->_data->set('value', 1);
		}

		return $this->app->html->_('select.booleanlist', 'elements[' . $this->identifier . '][value]', '', $this->_data->get('value'));
	}

	/*
		Function: renderSubmission
			Renders the element in submission.

	   Parameters:
            $params - submission parameters

		Returns:
			String - html
	*/
	public function renderSubmission($params = array()) {
        return $this->edit();
	}

	/*
		Function: validateSubmission
			Validates the submitted element

	   Parameters:
            $value  - AppData value
            $params - AppData submission parameters

		Returns:
			Array - cleaned value
	*/
	public function validateSubmission($value, $params) {
		return array('value' => $value->get('value'));
	}

}