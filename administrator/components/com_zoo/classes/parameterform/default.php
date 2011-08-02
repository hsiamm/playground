<?php
/**
* @package   com_zoo Component
* @file      default.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: AppParameterFormDefault
		Default parameter form class.
*/
class AppParameterFormDefault extends AppParameterFormXml {

	/*
		Function: render
			Render parameter HTML form

		Parameters:
			name - The name of the control, or the default text area if a setup file is not found
			group - Parameter group 

		Returns:
			String - HTML
	*/	
	public function render($name = 'params', $group = '_default') {
		if (!isset($this->_xml[$group])) {
			return false;
		}

		$params = $this->getParams($name, $group);

		$html[] = '<ul class="parameter-form">';

		// add group description
		if ($description = $this->_xml[$group]->attributes('description')) {
			$html[]	= '<li class="description">'.JText::_($description).'</li>';
		}

		// add params
		foreach ($params as $param) {
			$html[] = '<li class="parameter">';

			if ($param[0]) {
				$html[] = '<div class="label">'.$param[0].'</div>';
			}
			
			$html[] = '<div class="field">'.$param[1].'</div>';
			$html[] = '</li>';
		}

		$html[] = '</ul>';

		return implode("\n", $html);
	}

}