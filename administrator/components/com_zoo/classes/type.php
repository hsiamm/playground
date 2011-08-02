<?php
/**
* @package   com_zoo Component
* @file      type.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: Type
		Type related attributes and functions.
*/
class Type {

    /*
       Variable: id
         Type id.
    */
	public $id;

    /*
       Variable: identifier
         Type unique identifier.
    */
	public $identifier;

    /*
       Variable: name
         Type name.
    */
	public $name;

    /*
		Variable: app
			App instance.
    */
	public $app;

    /*
       Variable: _application
         Related application.
    */
	protected $_application;

    /*
       Variable: _elements
         Element objects.
    */
	protected $_elements;

    /*
       Variable: _core_elements
         Core element objects.
    */
	protected $_core_elements;

	/*
       Variable: _xml_element
         xml as AppXMLElement.
    */
	protected $_xml_element;
	
    /*
       Variable: _core_xml_element
         core elements xml as AppXMLElement.
    */
	protected $_core_xml_element;

    /*
       Variable: _xml
         Type elements xml.
    */
	protected $_xml;

	/*
    	Function: __construct
    	  Default Constructor

		Parameters:
	      id - Type id
	      basepath - Type base path

		Returns:
		  Type
 	*/
	public function __construct($id, $application = null) {

		// init vars
		$this->app = App::getInstance('zoo');

		// init vars
		$this->id = $id;
		$this->identifier = $id;
		$this->_application = $application;

		if ($data = $this->getXML()) {
			$this->name = (string) $this->app->xml->loadString($data)->attributes()->name;
		}
	}

	/*
		Function: getApplication
			Retrieve related application object.

		Returns:
			Application
	*/
	public function getApplication() {
		return $this->_application;
	}

	/*
    	Function: getElement
    	  Get element object by name.

	   	  Returns:
	        Object
 	*/
	public function getElement($identifier, $item = null) {

		// has element already been loaded?
		if (!$element = isset($this->_elements[$identifier]) ? $this->_elements[$identifier] : (isset($this->_core_elements[$identifier]) ? $this->_core_elements[$identifier] : null)) {

			// try to get element from xml
			if (($xml = $this->getXMLElement()) && ($element_xml = $xml->xpath('params/param[@identifier="'.$identifier.'"]')) && count($element_xml)) {
				 if ($element = $this->app->element->getElementFromXMLNode($element_xml[0], $this)) {
					$this->_elements[$identifier] = $element;
				 }

			// try to get element from core elements
			} else if (($xml = $this->getCoreXMLElement()) && ($element_xml = $xml->xpath('params/param[@identifier="'.$identifier.'"]')) && count($element_xml)) {
				 if ($element = $this->app->element->getElementFromXMLNode($element_xml[0], $this)) {
					$this->_core_elements[$identifier] = $element;
				 }
			}
		}

		if ($element) {
			$element = clone($element);
			$element->setType($this);
			if ($item != null) {
				$element->setItem($item);
			}
			return $element;
		}

		return null;
	}

	/*
    	Function: getXMLElement
    	  Gets the types xml as AppXMLElement.

	   	  Returns:
	        AppXMLElement - the types xml as AppXMLElement
 	*/
	public function getXMLElement() {
		if (empty($this->_xml_element)) {
			$this->_xml_element = $this->app->xml->loadString($this->getXML());
		}

		return $this->_xml_element;
	}

	/*
    	Function: getCoreXMLElement
    	  Gets the elements core xml as AppXMLElement.

	   	  Returns:
	        AppXMLElement - the elements core xml as AppXMLElement
 	*/
	public function getCoreXMLElement() {
		if (empty($this->_core_xml_element)) {
			$this->_core_xml_element = $this->app->xml->loadFile($this->app->path->path('elements:core.xml'));
		}

		return $this->_core_xml_element;

	}

	/*
    	Function: getElements
    	  Get all element objects from the parsed type elements xml.

	   	  Returns:
	        Array - Array of element objects
 	*/
	public function getElements($item = null) {

		if ($xml = $this->getXMLElement()) {
			$elements_xml = $xml->xpath('params/param');
			foreach ($elements_xml as $element_xml) {
				$identifier = (string) $element_xml->attributes()->identifier;
				if (!isset($this->_elements[$identifier])) {
					$this->getElement($identifier);
				}
			}
		}

		$elements = array();

		// set type and item object
		if (count($this->_elements)) {
			foreach ($this->_elements as $identifier => $element) {
				if ($element) {
					$elements[$identifier] = clone($this->_elements[$identifier]);
					$elements[$identifier]->setType($this);
					$elements[$identifier]->setItem($item);
				}
			}
		}

		return $elements;
	}

	/*
    	Function: getCoreElements
    	  Get all core element objects.

	   	  Returns:
	        Array - Array of element objects
 	*/
	public function getCoreElements($item = null) {

		if ($xml = $this->getCoreXMLElement()) {
			$elements_xml = $xml->xpath('params/param');
			foreach ($elements_xml as $element_xml) {
				$identifier = (string) $element_xml->attributes()->identifier;
				if (!isset($this->_core_elements[$identifier])) {
					$this->getElement($identifier);
				}
			}
		}

		$elements = array();

		// set type and item object
		foreach ($this->_core_elements as $identifier => $element) {
			$elements[$identifier] = clone($this->_core_elements[$identifier]);
			$elements[$identifier]->setType($this);
			if ($item != null) {
				$elements[$identifier]->setItem($item);
			}
		}

		return $elements;

	}

	/*
    	Function: getSubmittableElements
    	  Get all submittable element objects from the parsed type elements xml.

	   	  Returns:
	        Array - Array of submittable element objects
 	*/
	public function getSubmittableElements($item = null) {
		return	array_filter($this->getElements($item), create_function('$element', 'return $element instanceof iSubmittable;'));
	}

	/*
    	Function: clearElements
    	  Clear loaded elements object.

	   	  Returns:
	        Type
 	*/
	public function clearElements() {

		$this->_elements = null;

		return $this;
	}

	/*
		Function: getXML
			Retrieve xml and read config file content.

		Returns:
			String
	*/
	public function getXML() {

		if (empty($this->_xml) && ($file = $this->app->path->path($this->_application->getResource()."/types/{$this->id}.xml"))) {
			$this->_xml = JFile::read($file);
		}

		return $this->_xml;
	}

	/*
		Function: getXMLFile
			Retrieve xml config file.

		Returns:
			String
	*/
	public function getXMLFile($id = null) {

		$id = ($id !== null) ? $id : $this->id;

		if ($id && ($path = $this->app->path->path($this->_application->getResource().'types'))) {
			return $path.'/'.$id.'.xml';
		}

		return null;

	}

	/*
		Function: setXML
			Set xml and write config file content.

		Returns:
			Type
	*/
	public function setXML($xml) {
		$this->_xml = $xml;
		return $this;
	}

	/*
		Function: bind
			Bind data array to type.

		Returns:
			Type
	*/
	public function bind($data) {

		if (isset($data['identifier'])) {

			// check identifier
			if ($data['identifier'] == '' || $data['identifier'] != $this->app->string->sluggify($data['identifier'])) {
				throw new TypeException('Invalid identifier');
			}

			$this->identifier = $data['identifier'];
		}

		if (isset($data['name'])) {

			// check name
			if ($data['name'] == '') {
				throw new TypeException('Invalid name');
			}

			$this->name = $data['name'];
		}

		return $this;
	}

	/*
		Function: save
			Save type data.

		Returns:
			Type
	*/
	public function save() {

		$old_identifier = $this->id;
		$rename = false;

		if (empty($this->id)) {

			// check identifier
			if (file_exists($this->getXMLFile($this->identifier))) {
				throw new TypeException('Identifier already exists');
			}

			// set xml
			$this->setXML($this->app->xml->create('type')
				->addAttribute('version', '1.0.0')
				->asXML(true, true)
			);

		} else if ($old_identifier != $this->identifier) {

			// check identifier
			if (file_exists($this->getXMLFile($this->identifier))) {
				throw new TypeException('Identifier already exists');
			}

			// rename xml file
			if (!JFile::move($this->getXMLFile(), $this->getXMLFile($this->identifier))) {
				throw new TypeException('Renaming xml file failed');
			}

			$rename = true;

		}

		// update id
		$this->id = $this->identifier;

		// set data
		$this->setXML($this->app->xml->loadString($this->getXML())
			->addAttribute('name', $this->name)
			->asXML(true, true)
		);

		// save xml file
		if ($file = $this->getXMLFile()) {
			if (!JFile::write($file, $this->getXML())) {
				throw new TypeException('Writing type xml file failed');
			}
		}

		// rename related items
		if ($rename) {

			// get database
			$db = $this->app->database;

			$group = $this->getApplication()->getGroup();

			// update childrens parent category
			$query = "UPDATE ".ZOO_TABLE_ITEM." as a, ".ZOO_TABLE_APPLICATION." as b"
			    	." SET a.type=".$db->quote($this->identifier)
				    ." WHERE a.type=".$db->quote($old_identifier)
					." AND a.application_id=b.id"
					." AND b.application_group=".$db->quote($group);
			$db->query($query);
		}

		return $this;
	}

	/*
		Function: delete
			Delete type data.

		Returns:
			Type
	*/
	public function delete() {

		// check if type has items
		if ($this->app->table->item->getTypeItemCount($this)) {
			throw new TypeException('Cannot delete type, please delete the items related first');
		}

		// delete xml file
		if (!JFile::delete($this->getXMLFile())) {
			throw new TypeException('Deleting xml file failed');
		}

		return $this;
	}

}

/*
	Class: TypeException
*/
class TypeException extends AppException {}