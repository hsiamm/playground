<?php
/**
* @package   com_zoo Component
* @file      country.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
   Class: ElementCountry
       The country element class
*/
class ElementCountry extends Element implements iSubmittable {

	/*
		Function: getSearchData
			Get elements search data.
					
		Returns:
			String - Search data
	*/	
	public function getSearchData() {
		$countries = $this->_data->get('country', array());
		$keys = array_flip($countries);
		$countries = array_intersect_key($this->app->country->getIsoToNameMapping(), $keys);
		
		return implode (' ', $countries);
	}

	/*
		Function: hasValue
			Checks if the element's value is set.

	   Parameters:
			$params - render parameter

		Returns:
			Boolean - true, on success
	*/		
	public function hasValue($params = array()) {
		foreach ($this->_data->get('country', array()) as $country) {
            if (!empty($country)) {
                return true;
            }
        }
        return false;
	}
	
	/*
		Function: render
			Renders the element.

	   Parameters:
            $params - render parameter

		Returns:
			String - html
	*/
	public function render($params = array()) {

		$countries = $this->_data->get('country', array());
		$keys = array_flip($countries);
		$countries = array_intersect_key($this->app->country->getIsoToNameMapping(), $keys);

		$countries = array_map(create_function('$a', 'return JText::_($a);'), $countries);

		return $this->app->element->applySeparators($params['separated_by'], $countries);
	}

	/*
	   Function: edit
	       Renders the edit form field.

	   Returns:
	       String - html
	*/
	public function edit(){
		
		//init vars
		$selectable_countries = $this->_config->get('selectable_countries', array());
		
		if (count($selectable_countries)) {
			
			$multiselect = $this->_config->get('multiselect', array());
	
			$countries = $this->app->country->getIsoToNameMapping();
			$keys = array_flip($selectable_countries);
			$countries = array_intersect_key($countries, $keys);
	
			return $this->app->html->_('zoo.countryselectlist', $countries, 'elements[' . $this->identifier . '][country][]', $this->_data->get('country', array()), $multiselect);
		}
		
		return JText::_("There are no countries to choose from.");
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

        $options     = array('required' => $params->get('required'));
		$multiselect = $this->_config->get('multiselect');
		$messages    = ($multiselect) ? array('required' => 'Please select at least one country.') : array('required' => 'Please select a country.');
		
        $validator = $this->app->validator->create('foreach', $this->app->validator->create('string', $options, $messages), $options, $messages);
        $clean = $validator->clean($value->get('country'));

        foreach ($clean as $country) {
            if (!empty($country) && !in_array($country, $this->_config->get('selectable_countries', array()))) {
                throw new AppValidatorException('Please choose a correct country.');
            }
        }

		return array('country' => $clean);
	}

	/*
	   Function: loadConfig
	       Converts the XML to a data array and calls the bind method.

	   Parameters:
	      XML - The XML for this Element
	*/
	public function loadConfig($xml) {

		parent::loadConfig($xml);
		
		if (isset($xml->selectable_country)) {
			$countries = array();
			
			foreach ($xml->selectable_country as $selectable_country) {
				$countries[] = (string) $selectable_country->attributes()->value;
			}
			
			$this->_config->set('selectable_countries', $countries);
		}
	}

	/*
		Function: getConfigForm
			Get parameter form object to render input form.

		Returns:
			Parameter Object
	*/
	public function getConfigForm() {
		
		$form = parent::getConfigForm();
		$form->addElementPath(dirname(__FILE__));

		return $form;
	}
			
	/*
	   Function: getConfigXML
   	      Get elements XML.

	   Returns:
	      Object - AppXMLElement
	*/
	public function getConfigXML($ignore = array()) {

		$xml = parent::getConfigXML(array('selectable_countries'));
		
		foreach ($this->_config->get('selectable_countries', array()) as $selectable_country) {		
			if ($selectable_country['value'] != '') {
				$xml->addChild('selectable_country')->addAttribute('value', $selectable_country);	
			}
		}
		
		return $xml;
	}

}

class ElementCountryData extends ElementData{

	public function encodeData() {		
		$xml = $this->app->xml->create($this->_element->getElementType());
		$xml->addAttribute('identifier', $this->_element->identifier);
		foreach($this->_data->get('country', array()) as $country) {
			$xml->addChild('country', $country, null, true);
		}

		return $xml;			
	}

	public function decodeXML(AppXMLElement $element_xml) {
		$data = array();
		if (isset($element_xml->country)) {
			$countries = array();
			foreach ($element_xml->country as $country) {
				$countries[] = (string) $country;
			}
			$this->set('country', $countries);
		}
		return $data;
	}	
	
}