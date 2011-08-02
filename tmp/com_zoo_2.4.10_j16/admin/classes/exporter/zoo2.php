<?php
/**
* @package   com_zoo Component
* @file      zoo2.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

class AppExporterZoo2 extends AppExporter {
	
	protected $_application;
	
	public function __construct() {
		parent::__construct();
		$this->_name = 'Zoo v2';
	}
	
	public function export() {

		if (!$this->_application = $this->app->zoo->getApplication()) {
			throw new AppExporterException('No application selected.');
		}
		
		// export frontpage
		$frontpage = $this->app->object->create('Category');
		$frontpage->name  = 'Root';
		$frontpage->alias = '_root';
		$frontpage->description = $this->_application->description;
		
		// export categories
		$categories = $this->_application->getCategories();
		$categories[0] = $frontpage;
		foreach ($categories as $category) {
			$this->_addCategory($this->_categoryToXML($category, $categories));
		}

		$this->categories = $categories;

		// export items
		$types = $this->_application->getTypes();
		$item_table = $this->app->table->item;
		foreach ($types as $type) {
			$items = $item_table->getByType($type->id, $this->_application->id);
			foreach ($items as $key => $item) {
				$this->_addItem($type->name, $this->_itemToXML($item));
				$item->unsetElementData();
				unset($items[$key]);				
			}
		}

		return parent::export();
		
	}
	
	protected function _categoryToXML(Category $category, $categories) {
		
		// store category attributes
		$attributes = array();
		foreach ($this->category_attributes as $attribute) {
			if (isset($category->$attribute)) {
				$attributes[$attribute] = $category->$attribute;
			}
		}

		// store category parent
		if (isset($categories[$category->parent])) {
			$attributes['parent'] = $categories[$category->parent]->alias;
		}

		// build category
		$category_xml = $this->_buildCategory($category->alias, $category->name, $attributes);

		// store category params
		if ($category->alias == '_root') {									   
			$params = $this->_application->getMetaXML()->xpath('params[@group="application-content"]/param');
			$category_params = $this->_application->getParams();
		} else {
			$params = $this->_application->getMetaXML()->xpath('params[@group="category-content"]/param');
			$category_params = $category->getParams();
		}
		
		foreach ($params as $param) {
			$type = (string) $param->attributes()->type;
			$name = (string) $param->attributes()->name;
			
			switch ($type) {
				case 'zooimage':
					if ($path = $category_params->get('content.'.$name, '')) {
						$this->_attachCategoryImage($category_xml, $path, (string) $param->attributes()->label, $category_params->get('content.'.$name . '_width'), $category_params->get('content.'.$name . '_height'));
					}
					break;
				case 'text':
				case 'textarea':
					if ($value = $category_params->get('content.'.$name, '')) {
						$this->_attachCategoryParam($category_xml, $type, $value, (string) $param->attributes()->label);
					}
					break;
			}
		}
		return $category_xml;	
	}
	
	protected function _itemToXML(Item $item) {
		
		$attributes = array();
		foreach ($this->item_attributes as $attribute) {
			if (isset($item->$attribute)) {
				$attributes[$attribute] = $item->$attribute;
			}
		}		
		$attributes['author'] = $this->app->user->get($item->created_by)->username;				

		$item_xml = $this->_buildItem($item->alias, $item->name, $attributes);

		foreach ($item->getRelatedCategoryIds() as $category_id) {
			$alias = '';
			if (empty($category_id)) {
				$alias = '_root';
			} else if (isset($this->categories[$category_id])) {				
				$alias = $this->categories[$category_id]->alias;
			}
			if (!empty($alias)) {
				$this->_addItemCategory($item_xml, $alias);
			}
		}

		foreach ($item->getTags() as $tag) {
			$this->_addItemTag($item_xml, $tag);
		}

		foreach ($item->getElements() as $element) {
			$xml = $this->app->xml->loadString('<wrapper>'.$element->toXML().'</wrapper>');
			foreach ($xml->children() as $element_xml) {
				$element_xml->addAttribute('name', $element->getConfig()->get('name'));
				$this->_addItemData($item_xml, $element_xml);
			}
		}

		$metadata = array();
		foreach ($item->getParams()->get('metadata.', array()) as $key => $value) {
			$metadata[preg_replace('/^metadata\./', '', $key)] = $value;
		}
		if (!empty($metadata)) {
			$this->_addItemMetadata($item_xml, $metadata);
		}

		// sanitize relateditems elements
		$related_items_xmls = $item_xml->xpath('data/relateditems/item');
		if ($related_items_xmls) {
			foreach ($related_items_xmls as $related_items_xml) {
				$item_xml->replaceChild($this->app->xml->create('item', $this->app->item->translateIDToAlias((string)$related_items_xml), true), $related_items_xml);
			}
		}

		// sanitize relatedcategories elements
		$related_categories_xmls = $item_xml->xpath('data/relatedcategories/category');
		if ($related_categories_xmls) {
			foreach ($related_categories_xmls as $related_categories_xml) {
				$item_xml->replaceChild($this->app->xml->create('category', $this->app->category->translateIDToAlias((string)$related_categories_xml), true), $related_categories_xml);
			}
		}

		return $item_xml;
	}
	
}