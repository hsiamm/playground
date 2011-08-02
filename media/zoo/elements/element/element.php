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
	Class: Element
		The Element abstract class
*/
abstract class Element {

    /*
       Variable: $identifier
         Element identifier.
    */
	public $identifier;

    /*
		Variable: app
			App instance.
    */
	public $app;

    /*
       Variable: $_type
         Elements related type object.
    */
	protected $_type;

    /*
       Variable: $_item
         Elements related item object.
    */
	protected $_item;

    /*
       Variable: $_callbacks
         Element callbacks.
    */
	protected $_callbacks = array();

    /*
       Variable: $_config
         Config parameter object.
    */
	protected $_config;
	
	/*
       Variable: $_metaxml
         Element meta xml.
    */
	protected $_metaxml;

	/*
       Variable: $_data
         Element data.
    */
	protected $_data;
	
	/*
       Variable: $_path
         Element file path.
    */
	protected $_path;	
		
	/*
	   Function: Constructor
	*/
	public function __construct() {

		// set app
		$this->app = App::getInstance('zoo');

		$this->_config = $this->app->parameter->create();
		
		// initialize data
		$this->_data = $this->app->element->createData($this);
	}	
	
	/*
		Function: getElementType
			Gets the elements type.

		Returns:
			string - the elements type
	*/
	public function getElementType() {
		return strtolower(str_replace('Element', '', get_class($this)));
	}
	
	/*
		Function: getElementData
			Gets the elements data.

		Returns:
			ElementData - the elements data
	*/	
	public function getElementData() {
		return $this->_data;
	}
	
	/*
		Function: setData
			Set data through xml string.

		Parameters:
			$xml - string

		Returns:
			Void
	*/	
	public function setData($xml) {
		$this->_data = $this->app->element->createData($this);

		if (!empty($xml) && ($xml = $this->app->xml->loadString($xml))) {
			foreach ($xml->children() as $xml_element) {
				if ((string) $xml_element->attributes()->identifier == $this->identifier) {
					$this->_data->decodeXML($xml_element);
					break;
				}
			}
		}

        return $this;
	}

	/*
    	Function: unsetData
    	  Unsets element data

	   Returns:
	      Void
 	*/
	public function unsetData() {
		if (isset($this->_data)) {
			$this->_data->unsetData();
		}
		return $this;
	}
	
	/*
		Function: bindData
			Set data through data array.

		Parameters:
			$data - array

		Returns:
			Void
	*/	
	public function bindData($data = array()) {
		$this->_data = $this->app->element->createData($this);
		foreach ($data as $key => $value) {
			$this->_data->set($key, $value);
		}
	}

	/*
	   Function: toXML
	       Get elements XML representation.

	   Returns:
	       string - XML representation
	*/
	public function toXML() {
		return $this->_data->encodeData()->asXML(true);
	}
	
	/*
		Function: getLayout
			Get element layout path and use override if exists.
		
		Returns:
			String - Layout path
	*/
	public function getLayout($layout = null) {

		// init vars
		$type = $this->getElementType();

		// set default
		if ($layout == null) {
			$layout = "{$type}.php";
		}

		// find layout
		return $this->app->path->path("elements:{$type}/tmpl/{$layout}");

	}

	/*
		Function: getSearchData
			Get elements search data.
					
		Returns:
			String - Search data
	*/
	public function getSearchData() {
		return null;	
	}

	/*
		Function: getItem
			Get related item object.
		
		Returns:
			Item - item object
	*/
	public function getItem() {
		return $this->_item;
	}

	/*
		Function: getType
			Get related type object.

		Returns:
			Type - type object
	*/
	public function getType() {
		return $this->_type;
	}

	/*
		Function: getGroup
			Get element group.

		Returns:
			string - group
	*/
	public function getGroup() {
		$metadata = $this->getMetadata();
		return $metadata['group'];
	}	
	
	/*
		Function: setItem
			Set related item object.

		Parameters:
			$item - item object

		Returns:
			Void
	*/
	public function setItem($item) {
		$this->_item = $item;
	}

	/*
		Function: setType
			Set related type object.

		Parameters:
			$type - type object

		Returns:
			Void
	*/
	public function setType($type) {
		$this->_type = $type;
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
		$value = $this->_data->get('value');
		return !empty($value);
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
		
		// render layout
		if ($layout = $this->getLayout()) {
			return $this->renderLayout($layout, array('value' => $this->_data->get('value')));
		}
		
		return $this->_data->get('value');		
	}

	/*
		Function: renderLayout
			Renders the element using template layout file.

	   Parameters:
            $__layout - layouts template file
	        $__args - layouts template file args

		Returns:
			String - html
	*/
	protected function renderLayout($__layout, $__args = array()) {
				
		// init vars
		if (is_array($__args)) {
			foreach ($__args as $__var => $__value) {
				$$__var = $__value;
			}
		}
	
		// render layout
		$__html = '';
		ob_start();
		include($__layout);
		$__html = ob_get_contents();
		ob_end_clean();
		
		return $__html;
	}

	/*
	   Function: edit
	       Renders the edit form field.
		   Must be overloaded by the child class.

	   Returns:
	       String - html
	*/
	abstract public function edit();

	/*
		Function: loadAssets
			Load elements css/js assets.

		Returns:
			Void
	*/
	public function loadAssets() {}

	/*
		Function: registerCallback
			Register a callback function.

		Returns:
			Void
	*/
	public function registerCallback($method) {
		if (!in_array(strtolower($method), $this->_callbacks)) {
			$this->_callbacks[] = strtolower($method);
		}
	}

	/*
		Function: callback
			Execute elements callback function.

		Returns:
			Mixed
	*/
	public function callback($method, $args = array()) {

		// try to call a elements class method
		if (in_array(strtolower($method), $this->_callbacks) && method_exists($this, $method)) {
			// call method
			$res = call_user_func_array(array($this, $method), $args);
			// output if method returns a string
			if (is_string($res)) {
				echo $res;
			}
		}
	}

	/*
		Function: getConfig
			Retrieve element configuration.

		Returns:
			AppParameter
	*/
	public function getConfig() {
		return $this->_config;
	}

	/*
		Function: bindConfig
			Binds the data array.

		Parameters:
			$data - data array
	        $ignore - An array or space separated list of fields not to bind

		Returns:
			Element
	*/
	public function bindConfig($data, $ignore = array()) {

		$this->_config = $this->app->parameter->create()->set('identifier', $this->identifier);
		
		// convert space separated list
		if (!is_array($ignore)) {
			$ignore = explode(' ', $ignore);
		}

		// bind data array
		if (is_array($data)) {
			foreach ($data as $name => $value) {
				if (!in_array($name, $ignore)) {
					$this->_config->set($name, $value);
				}
			}
		}

		return $this;
	}

	/*
   		Function: loadConfig
       		Load xml element configuration.

		Parameters:
			$xml - XML for this element

		Returns:
			Element
	*/	
	public function loadConfig(AppXMLElement $xml) {

		// bind xml data
		foreach ($xml->attributes() as $name => $value) {
			$this->_config->set($name, (string) $value);
		}
		
		// set identifier
		$this->identifier = $this->_config->get('identifier');
		
		return $this;
	}

	/*
		Function: getConfigForm
			Get parameter form object to render input form.

		Returns:
			Parameter Object
	*/
	public function getConfigForm() {

		$xml = $this->getPath().'/'.$this->getElementType().'.xml';

		// get parameter xml file
		if (JFile::exists($xml)) {

			// get form
			$form = $this->app->parameterform->create($xml);
			$form->addElementPath($this->app->path->path('joomla:elements'));
			$form->setValues($this->_config);
			$form->element = $this; // add reference to element

			// trigger configform event
			$this->app->event->dispatcher->notify($this->app->event->create($this, 'element:configform', compact('form')));

			return $form;
		}
		
		return null;
	}
	
	/*
		Function: getConfigXML
			Get element configuration as xml formatted string.

		Returns:
			String
	*/
	public function getConfigXML($ignore = array()) {
		
		$xml = $this->app->xml->create('param')
			->addAttribute('type', $this->getElementType())
			->addAttribute('identifier', $this->_config->get('identifier'))
			->addAttribute('name', $this->_config->get('name'));
		
		if ($xmlfile = $this->getMetaXML()) {
			$params = $xmlfile->xpath('params/param');
			if ($params) {
				foreach ($params as $param) {
					$name  = (string) $param->attributes()->name;
					$value = $this->_config->get($name);
					if (isset($value) && !in_array($name, $ignore)) {
						$xml->addAttribute($name, $value);
					}
				}
			}
		}

		// trigger configxml event
		$this->app->event->dispatcher->notify($this->app->event->create($this, 'element:configxml', compact('xml')));
		
		return $xml;
	}
	
	/*
		Function: loadConfigAssets
			Load elements css/js config assets.

		Returns:
			Element
	*/
	public function loadConfigAssets() {
		return $this;
	}	
	
	/*
		Function: getMetaData
			Get elements xml meta data.

		Returns:
			Array - Meta information
	*/
	public function getMetaData() {

		$data = array();
		$xml  = $this->getMetaXML();

		if (!$xml) {
			return false;
		}

		$data['type'] 		  = $xml->attributes()->type ? (string) $xml->attributes()->type : 'Unknown';
		$data['group'] 		  = $xml->attributes()->group ? (string) $xml->attributes()->group : 'Unknown';
		$data['hidden'] 	  = $xml->attributes()->hidden ? (string) $xml->attributes()->hidden : 'false';
        $data['trusted'] 	  = $xml->attributes()->trusted ? (string) $xml->attributes()->trusted : 'false';
		$data['name'] 		  = (string) $xml->name;
		$data['creationdate'] = $xml->creationDate ? (string) $xml->creationDate : 'Unknown';
		$data['author'] 	  = $xml->author ? (string) $xml->author : 'Unknown';
		$data['copyright'] 	  = (string) $xml->copyright;
		$data['authorEmail']  = (string) $xml->authorEmail;
		$data['authorUrl'] 	  = (string) $xml->authorUrl;
		$data['version'] 	  = (string) $xml->version;
		$data['description']  = (string) $xml->description;

		return $data;
	}
	
	/*
		Function: getMetaXML
			Get elements xml meta file.

		Returns:
			Object - AppXMLElement
	*/
	public function getMetaXML() {

		if (empty($this->_metaxml)) {
			$this->_metaxml = $this->app->xml->loadFile($this->getPath().'/'.$this->getElementType().'.xml');
		}
		
		return $this->_metaxml;
	}	
	
	/*
		Function: getPath
			Get path to element's base directory.

		Returns:
			String - Path
	*/
	public function getPath() {
		if (empty($this->_path)) {
			$rc = new ReflectionClass(get_class($this));
			$this->_path = dirname($rc->getFileName());
		}
		return $this->_path;
	}

	/*
    	Function: canAccess
    	  Check if element is accessible with users access rights.

	   Returns:
	      Boolean - True, if access granted
 	*/
	public function canAccess($user = null) {
		return $this->app->user->canAccess($user, $this->_config->get('access', $this->app->joomla->getDefaultAccess()));
	}

}

class ElementData {

	public    $app;

	protected $_data;
	protected $_element;

	public function __construct($element) {
		$this->_element = $element;
		$this->app		= $element->app;
		$this->_data	= $this->app->data->create();
	}

	public function getElement() {
		return $this->_element;
	}

	public function getParams() {
		return $this->_data;
	}

	public function set($name, $value) {
		$this->_data->set($name, $value);
	}

	public function get($name, $default = null) {
		return $this->_data->get($name, $default);
	}

	public function encodeData() {
		$xml = $this->app->xml->create($this->getElement()->getElementType())->addAttribute('identifier', $this->_element->identifier);
		foreach ($this->_data as $key => $value) {
			$xml->addChild($key, $value, null, true);
		}

		return $xml;
	}

	public function decodeXML(AppXMLElement $element_xml) {
		foreach ($element_xml->children() as $child) {
			$this->set($child->getName(), (string) $child);
		}
		return $this;
	}	

	public function unsetData() {
		$this->_data->exchangeArray(array());
		$this->_element = null;
	}
	
}

// Declare the interface 'iSubmittable'
interface iSubmittable {

	/*
		Function: renderSubmission
			Renders the element in submission.

	   Parameters:
            $params - submission parameters

		Returns:
			String - html
	*/
    public function renderSubmission($params = array());

    /*
		Function: validateSubmission
			Validates the submitted element

	   Parameters:
            $value  - AppData value
            $params - AppData submission parameters

		Returns:
			Array - cleaned value
	*/
    public function validateSubmission($value, $params);
}

interface iSubmissionUpload {

    /*
		Function: doUpload
			Does the actual upload during submission

		Returns:
			void
	*/
    public function doUpload();
}

/*
	Class: ElementException
*/
class ElementException extends AppException {}