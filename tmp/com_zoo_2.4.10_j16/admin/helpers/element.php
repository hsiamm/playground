<?php
/**
* @package   com_zoo Component
* @file      element.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/*
	Class: ElementHelper
		A class that contains element helper functions
*/
class ElementHelper extends AppHelper{

	/*
		Function: __construct
			Class Constructor.
	*/
	public function __construct($app) {
		parent::__construct($app);

		// load class
		$this->app->loader->register('Element', 'elements:element/element.php');
	}

	public function createData($element) {

		$current_class = get_class($element);
		do {
			$class_name = $current_class.'Data';
			if (class_exists($class_name)) {
				return new $class_name($element);
			}
		} while ($current_class = get_parent_class($current_class));

	}

	/*
	   Function: getAll
	      Returns a XML representation of the element array.

	   Parameters:
	      $application - the application who's elements to retrieve

	   Returns:
	      XML representation of element array
	*/
	public function getAll($application){

		$elements = array();
		$application->registerElementsPath();

		foreach ($this->app->path->dirs('elements:') as $type) {

			if ($type != 'element' && is_file($this->app->path->path("elements:$type/$type.php"))) {
				if ($element = $this->loadElement($type, $application)) {
					$metadata = $element->getMetaData();
					if ($metadata && $metadata['hidden'] != 'true') {
						$elements[] = $element;
					}
				}
			}
		}

		return $elements;
	}

	/*
 		Function: saveElements
 	      Method to save a types elements.

	   Parameters:
	      $post - post data
	      $type - current type data with xml

	   Returns:
	      Boolean. True on success
	*/
	public function saveElements($post, Type $type){

		// init vars
		$elements = array();

		if (isset($post['elements']) && is_array($post['elements'])) {
			foreach ($post['elements'] as $identifier => $data) {
				if (!$element = $type->getElement($identifier)) {
					$element = $this->loadElement($data['type'], $type->getApplication());
					$element->setType($type);
					$element->identifier = $identifier;
				}

				// bind data
				$element->bindConfig($data);

				// add to element array
				$elements[] = $element;
			}
		}

		$type->setXML($this->toXML($elements));
		$type->clearElements();

		return true;
	}

	/*
		@deprecated: acquire single elements instead
	*/
	public function createElementsFromXML($xml_string, Type $type){

		$results = array();
		$xml 	 = $this->app->xml->loadString($xml_string);

		$params = $xml->xpath('params/param');
		if ($params) {
			foreach ($params as $param)  {
				if ($element = $this->getElementFromXMLNode($param, $type)) {
					$results[$element->identifier] = $element;
				}
			}
		}
		return $results;
	}

	/*
		Function: getElementFromXMLNode
			Creates an Element and binds data from XMLNode

		Parameters:
			$xml  - AppXMLElement describes element
			$type - Type

		Returns:
			Object - element object
	*/
	public function getElementFromXMLNode(AppXMLElement $xml, Type $type) {

		// load element
		$element_type = (string) $xml->attributes()->type;
		$element 	  = $this->loadElement($element_type, $type->getApplication());

		// bind element data or set undefined
		if ($element !== false) {
			$element->loadConfig($xml);
			return $element;
		}

		return null;
	}

	/*
		Function: loadElement
			Creates element of $type

		Parameters:
			$type - Type of the element subclass to create
	      	$application - the application

		Returns:
			Object - element object
	*/
	public function loadElement($type, $application = false) {

		// load element class
		$elementClass = 'Element'.$type;
		if (!class_exists($elementClass)) {

			if ($application) {
				$application->registerElementsPath();
			}

			$file = JFilterInput::clean(str_replace('_', DS, strtolower(substr($type, 0, 1)) . substr($type, 1)).'.php', 'path');

			$this->app->loader->register($elementClass, "elements:$type/$file");

		}

		if (!class_exists($elementClass)) {
			return false;
		}

		$testClass = new ReflectionClass($elementClass);

		if ($testClass->isAbstract()) {
			return false;
		}

		return new $elementClass($this->app);

	}

	/*
		Function: toXML
			Returns a XML representation of the element array.

		Parameters:
			$elements - ElementArray

		Returns:
			String - XML representation of element array
	*/
	public function toXML($elements){

		$type   = $this->app->xml->create('type')->addAttribute('version', '1.0.0');
		$params = $this->app->xml->create('params');

		foreach ($elements as $element) {
			$params->appendChild($element->getConfigXML());
		}

		return $type->appendChild($params)->asXML(true, true);
	}

	/*
		Function: applySeparators
			Separates the passed element values with a separator

		Parameters:
			$separated_by - Separator
			$values - Element values

		Returns:
			String
	*/
	public function applySeparators($separated_by, $values) {

		if (!is_array($values)) {
			$values = array($values);
		}

		$separator = '';
		$tag = '';
		$enclosing_tag = '';
		if ($separated_by) {
			if (preg_match('/separator=\[(.*)\]/U', $separated_by, $result)) {
				$separator = $result[1];
			}

			if (preg_match('/tag=\[(.*)\]/U', $separated_by, $result)) {
				$tag = $result[1];
			}

			if (preg_match('/enclosing_tag=\[(.*)\]/U', $separated_by, $result)) {
				$enclosing_tag = $result[1];
			}
		}

		if (empty($separator) && empty($tag) && empty($enclosing_tag)) {
			$separator = ', ';
		}

		if (!empty($tag)) {
			foreach ($values as $key => $value) {
				$values[$key] = sprintf($tag, $values[$key]);
			}
		}

		$value = implode($separator, $values);

		if (!empty($enclosing_tag)) {
			$value = sprintf($enclosing_tag, $value);
		}

		return $value;
	}

}