<?php
/**
* @package   com_zoo Component
* @file      export.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

class ExportHelper extends AppHelper {

	/*
		Function: __construct
			Class Constructor.
	*/
	public function __construct($app) {
		parent::__construct($app);

		// register paths
		$this->app->path->register($this->app->path->path('classes:exporter'), 'exporter');

	}

	/*
		Function: create
			Creates a AppExporter instance

		Parameters:
			$type - AppExporter name

		Returns:
			AppExporter
	*/
	public function create($type) {

		$type	= preg_replace('/[^A-Z0-9_\.-]/i', '', $type);

		// load renderer class
		$class = 'AppExporter' . $type;
		$this->app->loader->register($class, 'exporter:'.strtolower($type).'.php');

		return $this->app->object->create($class);
	}

	/*
		Function: create
			Get all AppExporter

		Parameters:
			$type - AppExporter name

		Returns:
			array - AppExporter instances
	*/
	public function getExporters($ignore = array()) {
		$ignore = (array) $ignore;
		$exporters = array();
		foreach ($this->app->path->files('exporter:', false, '/\.php$/') as $file) {
			if ($instance = $this->create(basename($file, '.php'))) {
				if (!in_array($instance->getName(), $ignore)) {
					$exporters[] = $instance;
				}
			}
		}
		return $exporters;
	}

}

abstract class AppExporter {

	public $app;

	protected $_categories;
	protected $_item_groups;
	protected $_name;
	
	public $category_attributes = array('parent', 'published', 'description', 'ordering');
	public $item_attributes = array('searchable', 'state', 'created', 
										'modified', 'hits', 'author', 
										'access', 'priority', 'metakey', 
										'metadesc', 'metadata', 'publish_up', 
										'publish_down');
	public $element_attributes = array('text' => array('value'),
										'textarea' => array('value'),
										'download' => array('file', 'download_limit', 'hits', 'size'),
										'rating' => array('value', 'votes'),
										'date' => array('value'),
										'email' => array('text', 'value', 'subject', 'body'),
										'link' => array('text', 'value', 'target', 'rel'),
										'gallery' => array('value', 'title'),
										'image' => array('file'),
										'video' => array('file', 'url', 'width', 'height', 'autoplay'),
										'joomlamodule' => array('value'),
										'socialbookmarks' => array('value'),
										'addthis' => array('value'),
										'disqus' => array('value'),										
										'flickr' => array('value', 'flickrid'),
										'googlemaps' => array('location', 'popup'),
										'intensedebate' => array('value'),
										'checkbox' => array('option'),
										'radio' => array('option'),
										'select' => array('option'),
										'country' => array('country'),
										'relatedcategories' => array('category'),
										'relateditems' => array('item')
	);	

	public function __construct() {
		$this->_categories = array();
		$this->_item_groups = array();
	}
	
	/*
		Function: getName
			Get a AppExporter name

		Returns:
			String - name
	*/		
	public function getName() {
		return $this->_name;
	}

	/*
		Function: getName
			Get a AppExporter type

		Returns:
			String - type
	*/		
	public function getType() {
		return strtolower(str_replace('AppExporter', '', get_class($this)));
	}

	/*
		Function: isEnabled
			Is exporter enabled.
			May be overloaded by the child class.
			
		Returns:
			Boolean
	*/		
	public function isEnabled() {
		return true;
	}
	
	/*
		Function: export
			Do the export.
			Must be overloaded by the child class.
			
		Returns:
			String - the export xml
	*/		
	public function export() {

		$export_xml = $this->app->xml->create('export');
		
		$categories_xml = $this->app->xml->create('categories');
		foreach ($this->_categories as $category) {
			$categories_xml->appendChild($category);
		}
		$export_xml->appendChild($categories_xml);

		foreach ($this->_item_groups as $group_title => $group) {
			$item_groups_xml = $this->app->xml->create('items')->addAttribute('name', $group_title);
			foreach ($group as $item) {
				$item_groups_xml->appendChild($item);		
			}
			$export_xml->appendChild($item_groups_xml);
		}

		return $export_xml->asXML(true, true);		
		
	}

	protected function _addCategory(AppXMLElement $category) {
		$id = (string)$category->attributes()->id;
		$name = (string) $category->name;
		if (!empty($name)) {
			if (empty($id)) {
				$id = JFilterOutput::stringURLSafe($name);	
			}
			while ($this->_keyExists($this->_categories, $id)) {
				$id .= '-2';
			}
			$category->addAttribute('id', $id);
			$this->_categories[$id] = $category;
		}		
		return $this;
	}
	
	protected function _buildCategory($id, $name, $attributes = array()) {
		
		$category_xml = $this->app->xml->create('category')->addAttribute('id', $id);
		$category_xml->addChild('name', $name, null, true);

		foreach ($attributes as $attribute => $data) {
			if (in_array($attribute, $this->category_attributes)) {
				$category_xml->addChild($attribute, $data, null, true);
			}
		}

		return $category_xml;
	}
	
	protected function _attachCategoryImage(AppXMLElement $category, $path, $name = null, $width = null, $height = null) {
		if (!$category->content) {
			$category->addChild('content');
		}		

		$image_xml = $category->content[0]->addChild('image')->addAttribute('name', $name);
		$image_xml->addChild('path', $path, null, true);
		if ($width) {
			$image_xml->addChild('width', $width, null, true);
		}
		if ($height) {
			$image_xml->addChild('height', $height, null, true);
		}
	}

	protected function _attachCategoryParam(AppXMLElement $category, $type, $value, $name = null) {
		if (!$category->content) {
			$category->addChild('content');
		}		

		$category->content[0]->addChild($type, $value, null, true)->addAttribute('name', $name);
	}	
	
	protected function _addItem($group, AppXMLElement $item) {
		$id = (string)$item->attributes()->id;
		$name = (string) $item->name;
		if (!empty($name)) {
			if (empty($id)) {
				$id = JFilterOutput::stringURLSafe($name);	
			}
			while ($this->_keyExists($this->_item_groups, $id)) {
				$id .= '-2';
			}
			$item->addAttribute('id', $id);
			$this->_item_groups[$group][$id] = $item;
		}
		return $this;
	}
	
	protected function _buildItem($id, $name, $attributes = array()) {
		
		$item_xml = $this->app->xml->create('item')->addAttribute('id', $id);
		$item_xml->addChild('name', $name, null, true);

		foreach ($attributes as $attribute => $data) {
			if (in_array($attribute, $this->item_attributes)) {
				$item_xml->addChild($attribute, $data, null, true);
			}
		}

		return $item_xml;									
	
	}
	
	protected function _addItemCategory(AppXMLElement $item, $category) {
		if (!$item->categories) {
			$item->addChild('categories');
		}		

		$item->categories[0]->addChild('category', $category, null, true);
	}
	
	protected function _addItemTag(AppXMLElement $item, $tag) {
		if (!$item->tags) {
			$item->addChild('tags');
		}		
		
		$item->tags[0]->addChild('tag', $tag, null, true);
	}	
	
	protected function _addItemData(AppXMLElement $item, AppXMLElement $element) {
		if (!$item->data) {
			$item->addChild('data');
		}
		$item->data[0]->appendChild($element);
	}
	
	protected function _addItemMetadata(AppXMLElement $item, $metadata = array()) {
		if (!$item->metadata) {
			$item->addChild('metadata');
		}
		
		foreach ($metadata as $key => $data) {
			$item->metadata[0]->addChild($key, $data, null, true);
		}
	}
			
	protected function _buildElement($name, $alias, $element_name, array $values = array()) {
		$elem_xml = $this->app->xml->create($name)
					->addAttribute('identifier', $alias)
					->addAttribute('name', $element_name);
		foreach ($values as $key => $value) {
			if (is_array($value)) {
				foreach ($value as $single_value) {
					$elem_xml->addChild($key, $single_value, null, true);
				}
			} else {
				$elem_xml->addChild($key, $value, null, true);
			}
		}		

		return $elem_xml;
	}
	
	private function _keyExists($haystack, $needle) {
		foreach ($haystack as $key => $value) {
			if (is_array($value)) {
				if ($this->_keyExists($value, $needle)) {
					return true;
				}
			}
			if ($key === $needle) {
				return true;
			}
		}
		return false;
	}	
	
}

/*
	Class: AppExporterException
*/
class AppExporterException extends AppException {}