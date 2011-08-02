<?php
/**
* @package   com_zoo Component
* @file      xml.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

class XMLHelper extends AppHelper{

	/*
		Function: loadFile
			Interprets an XML file into an object.

		Parameters:
			$file - filename string

		Returns:
			AppXMLElement, false on failure
	*/
	public function loadFile($file) {

        if (JFile::exists($file)) {
            $data = JFile::read($file);

            return $this->loadString($data);

        }

		return null;

	}

	/*
		Function: loadString
			Interprets a string of XML into an object.

		Parameters:
			$string - data string

		Returns:
			AppXMLElement, false on failure
	*/
	public function loadString($string) {

		libxml_use_internal_errors(true);

        $string = $this->stripInvalidXMLCharacters($string);

		return simplexml_load_string($string, 'AppXMLElement');

	}

	/*
		Function: stripInvalidXMLCharacters
			Strips invalid xml characters from a string (according to http://www.w3.org/TR/REC-xml/#charsets)

		Parameters:
			$string - data string

		Returns:
			String, cleaned string
	*/
    public function stripInvalidXMLCharacters($string = '') {
        return preg_replace('/[^\x09\x0A\x0D\x20-\xD7FF\xE000-\xFFFD]/', '', $string);
    }

	/*
		Function: create
			Creates an AppXMLElement.

		Parameters:
			$name - The name of the element to create.
			$value - If specified, the value of the element to create.
			$cdata - Wrap value in cdata section

		Returns:
			AppXMLElement
	*/
	public function create($name, $value = null, $cdata = false) {
		return simplexml_import_dom(DOMImplementation::createDocument()->createElement($name), 'AppXMLElement')->setData($value, $cdata);
	}

}

class AppXMLElement extends SimpleXMLElement {

	/*
		Function: addChild
			Adds a AppXMLElement child

		Parameters:
			$nodeName - The name of the child element to add.
	        $value - If specified, the value of the child element.
	        $nameSpace - If specified, the namespace to which the child element belongs.
	        $cdata - Wrap value in cdata section

		Returns:
			AppXMLElement child
	*/
    public function addChild($nodeName, $value = null, $nameSpace = null, $cdata = false) {
	    return parent::addChild($nodeName, null, $nameSpace)->setData($value, $cdata);
    }

   	/*
		Function: setData
			Sets the nodes data.

		Parameters:
			$data - Nodes data
	        $cdata - Wrap value in cdata section

		Returns:
			AppXMLElement
	*/
	public function setData($data, $cdata = false) {

		if (!empty($data)) {
			$node = dom_import_simplexml($this);

			// remove children and text content
			while($node->hasChildNodes()) {
				$node->removeChild($node->childNodes->item(0));
			}

			$doc  = $node->ownerDocument;
            $data = preg_replace('/[^\x09\x0A\x0D\x20-\xD7FF\xE000-\xFFFD]/', '', $data); // stripInvalidXMLCharacters
			if ($cdata) {
				$node->appendChild($doc->createCDATASection($data));
			} else {
				$node->appendChild($doc->createTextNode($data));
			}
		}

		return $this;
	}

	/*
		Function: appendChild
			Appends a AppXMLElement to the elements children.

		Parameters:
			$child - AppXMLElement child

		Returns:
			AppXMLElement
	*/
	public function appendChild(SimpleXMLElement $child) {
		list($node, $_child) = $this->_getDomNodes($this, $child);
		$node->appendChild($_child);
		$child = simplexml_import_dom($_child);
		return $this;
	}

	/*
		Function: replaceChild
			Replaces a AppXMLElement child.

		Parameters:
			$newChild - AppXMLElement new child
			$oldChild - AppXMLElement old child

		Returns:
			AppXMLElement
	*/
	public function replaceChild(SimpleXmlElement $newChild, SimpleXmlElement $oldChild){
		list($oldChild, $_newChild) = $this->_getDomNodes($oldChild, $newChild);
		$oldChild->parentNode->replaceChild($_newChild, $oldChild);
		$newChild = simplexml_import_dom($_newChild);
		return $this;
	}

	/*
		Function: removeChild
			Removes a AppXMLElement child.

		Parameters:
			$child - AppXMLElement child to remove

		Returns:
			AppXMLElement
	*/
	public function removeChild(SimpleXmlElement $child) {
		$dom = dom_import_simplexml($child);
		$dom->parentNode->removeChild($dom);
		return $this;
	}

	/*
		Function: insertBefore
			This function inserts a new node right before the reference node.

		Parameters:
			$newnode - The new node.
	        $refnode - The reference node. If not supplied, newnode  is appended to the children.

		Returns:
			AppXMLElement
	*/
	public function insertBefore(SimpleXMLElement $newnode, SimpleXMLElement $refnode) {
		list($node, $_newnode, $_refnode) = $this->_getDomNodes($this, $newnode, $refnode);
		$node->insertBefore($_newnode, $_refnode);
		$newnode = simplexml_import_dom($_newnode);
		return $this;
	}

	/*
		Function: addAttribute
			Sets an attribute with name to the given value. If the attribute does not exist, it will be created.

		Parameters:
			$string - The qualified name of the attribute.
	        $value - The value of the attribute.
			$nameSpace - The namespace URI.
		Returns:
			AppXMLElement
	*/
	public function addAttribute($string, $value, $nameSpace = null) {
		if ($this->attributes()->$string) {
			if ($nameSpace) {
				dom_import_simplexml($this)->setAttributeNS($nameSpace, $string, $value);
			} else {
				dom_import_simplexml($this)->setAttribute($string, $value);
			}
		} else {
			parent::addAttribute($string, $value, $nameSpace);
		}
		return $this;
	}

	/*
		Function: removeAttribute
			Removes attribute from the element.

		Parameters:
			$name - The name of the attribute.

		Returns:
			AppXMLElement
	*/
	public function removeAttribute($name) {
		$node = dom_import_simplexml($this);
		$node->removeAttribute($name);
		return $this;
	}

	/*
		Function: firstChild
			Returns the first child of the element.

		Returns:
			AppXMLElement
	*/
	public function firstChild() {
		$children = $this->children();
		return $children[0];
	}

	/*
		Function: map
			Traverses the tree calling the callback on every child.

		Parameters:
			$callback - Callback function
			$args - Callback function arguments

		Returns:
			AppXMLElement
	*/
	function map($callback, $args = array()) {

		// init level
		if (!isset($args['level'])) {
			$args['level'] = 0;
		}

		// call function
		call_user_func($callback, $this, $args);

		// raise level
		$args['level']++;

		// map to all children
		$children = $this->children();
		if ($n = count($children)) {
			for ($i = 0; $i < $n; $i++) {
				$children[$i]->map($callback, $args);
			}
		}
		return $this;
	}

	/*
		Function: getElementByPath
			Gets the first element that matches the path.

		Parameters:
			$path - path to element

		Returns:
			AppXMLElement, null if no element is found
	*/
	public function getElementByPath($path) {
		$array = $this->xpath($path);
		return isset($array[0]) ? $array[0] : null;
	}

	/*
		Function: getElementsByPath
			Get elements that match the path.

		Parameters:
			$path - path to element

		Returns:
			Array with AppXMLElements
	*/
	public function getElementsByPath($path) {
		$array = $this->xpath($path);
		return empty($array) ? array() : $array;
	}

	/*
		Function: asXML
			Return a well-formed XML string based on SimpleXML element

		Parameters:
			$format - The output will be formatted
	        $includeXMLDeclaration - The XML string will contain a xml header
	        $version - The XML version of the header
	        $encoding - The encoding of the xml

		Returns:
			Element
	*/
	public function asXML($format = false, $includeXMLDeclaration = false, $version = '1.0', $encoding = 'UTF-8') {

		if ($format) {
			$dom = new DOMDocument();
			$dom->preserveWhiteSpace = false;
			$dom->formatOutput = true;
			$dom->loadXML($this->asXML());
            $dom->xmlVersion = $version;
            $dom->encoding = $encoding;

			return $includeXMLDeclaration ? $dom->saveXML() : $dom->saveXML($dom->firstChild);
		}

		$xml = parent::asXML();
		return $includeXMLDeclaration ? '<?xml version="'.$version.'" encoding="'.$encoding.'"?>' . $xml : $xml;
	}

	/*
		Function: asXML
			Converts passed in elements to DOMElements and makes sure they have the same document root

		Parameters:
			$first - SimpleXMLElement
			$second - SimpleXMLElement
			... - SimpleXMLElement

		Returns:
			array DOMElements
	*/
	protected static function _getDomNodes($first, $second){

		$nodes = func_get_args();
		$nodes[0] = dom_import_simplexml($nodes[0]);

		for ($i = 1; $i < count($nodes); $i++) {
			$node = dom_import_simplexml($nodes[$i]);
			if (!$nodes[0]->ownerDocument->isSameNode($node->ownerDocument)) {
				$nodes[$i] = $nodes[0]->ownerDocument->importNode($node, true);
			}
		}

		return $nodes;
	}

}

/*
	Class: AppXMLException
*/
class AppXMLException extends AppException {}