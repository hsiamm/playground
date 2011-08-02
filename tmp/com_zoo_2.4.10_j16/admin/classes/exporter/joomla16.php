<?php
/**
* @package   com_zoo Component
* @file      joomla16.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

class AppExporterJoomla16 extends AppExporter {
	
	public function __construct() {
		parent::__construct();
		$this->_name = 'Joomla 1.6';
	}

	public function isEnabled() {
		return $this->app->joomla->isVersion('1.6');
	}

	public function export() {

		$categories = $this->app->database->queryObjectList('SELECT * FROM #__categories ORDER BY lft ASC', 'id');

		$ordered_categories = array();
		foreach ($categories as $category) {
			$ordered_categories[$category->parent_id][] = $category->id;
		}

    	// get category table
		$category_table = $this->app->table->category;

		// get item table
		$item_table = $this->app->table->item;			

	    foreach($categories as $category) {

			if ($category->alias != 'root' && $category->extension != 'com_content') {
				continue;
			}

			if ($category->alias == 'root') {
				$category->name  = 'Root';
				$category->alias = '_root';
			}

			// store category parent
			if (isset($categories[$category->parent_id])) {
				$category->parent = $categories[$category->parent_id]->alias;
			}

			if (isset($ordered_categories[$category->parent_id]) && is_array($ordered_categories[$category->parent_id])) {
				$category->ordering = array_search($category->id, $ordered_categories[$category->parent_id]);
			}

			$params = $this->app->parameter->create($category->params);

	    	$attributes = array();
			foreach ($this->category_attributes as $attribute) {
				if (isset($category->$attribute)) {
					$attributes[$attribute] = $category->$attribute;
				}
			}
			$category_xml = $this->_buildCategory($category->alias, $category->title, $attributes);
			if ($params->get('image')) {
				$this->_attachCategoryImage($category_xml, $params->get('image'), 'Image');
			}
	    	$this->_addCategory($category_xml);

			$query = "SELECT * FROM #__content WHERE catid =" . $category->id;
			$articles = $this->app->database->queryObjectList($query);

			foreach ($articles as $article) {
				if ($article->state != -2) {
					$this->_addItem(JText::_('Joomla article'), $this->_articleToXML($article, $category->alias));
				}
			}

	    }

		$query = "SELECT * FROM #__content WHERE catid = 0";
		$articles = $this->app->database->queryObjectList($query);

		foreach ($articles as $article) {
			if ($article->state != -2) {
				$this->_addItem(JText::_('Joomla article'), $this->_articleToXML($article, 0));
			}			
		}

		return parent::export();
		
	}
	
	protected function _articleToXML($article, $parent) {

		if ($article->state > 1) {
			$article->state = 0;
		}

		$attributes = array();
		foreach ($this->item_attributes as $attribute) {
			if (isset($article->$attribute)) {
				$attributes[$attribute] = $article->$attribute;
			}
		}		
		$attributes['author'] = $this->app->user->get($article->created_by)->username;
		
		$item_xml = $this->_buildItem($article->alias, $article->title, $attributes);
				
		if ($parent) {
			$this->_addItemCategory($item_xml, $parent);
		}
		
		$this->_addItemData($item_xml, $this->_buildElement('textarea', 0, 'Article', array('value' => $article->introtext)));
		$this->_addItemData($item_xml, $this->_buildElement('textarea', 0, 'Article', array('value' => $article->fulltext)));
		
		return $item_xml;
	}
  	
}