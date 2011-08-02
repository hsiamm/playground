<?php
/**
* @package   com_zoo Component
* @file      joomla15.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

class AppExporterJoomla15 extends AppExporter {
	
	public function __construct() {
		parent::__construct();
		$this->_name = 'Joomla 1.5';
	}

	public function isEnabled() {
		return $this->app->joomla->isVersion('1.5');
	}

	public function export() {
		
		$db = $this->app->database;
	    $query = "SELECT * FROM #__sections"
	    		." WHERE scope='content'";
	    $sections = $db->queryObjectList($query);
	    
    	// get category table
		$category_table = $this->app->table->category;

		// get item table
		$item_table = $this->app->table->item;			
	    
		// get image path
		$image_path = JComponentHelper::getParams('com_media')->get('image_path');
		
	    foreach($sections as $section) {
	    	$attributes = array();
			foreach ($this->category_attributes as $attribute) {
				if (isset($section->$attribute)) {
					$attributes[$attribute] = $section->$attribute;
				}
			}
			$category_xml = $this->_buildCategory($section->alias, $section->title, $attributes);
			if ($section->image) {
				$this->_attachCategoryImage($category_xml, $image_path.'/'.$section->image, 'Image');
			}
	    	$this->_addCategory($category_xml);

			$query = "SELECT * FROM #__categories WHERE section = " . $section->id;
			$joomla_categories = $db->queryObjectList($query);

			foreach($joomla_categories as $joomla_category) {
				
				$attributes = array();
				foreach ($this->category_attributes as $attribute) {
					if (isset($joomla_category->$attribute)) {
						$attributes[$attribute] = $joomla_category->$attribute;
					}
				}
				$attributes['parent'] = $section->alias;

				$category_xml = $this->_buildCategory($joomla_category->alias, $joomla_category->title, $attributes);
				if ($joomla_category->image) {
					$this->_attachCategoryImage($category_xml, $image_path.'/'.$joomla_category->image, 'Image');
				}
		    	$this->_addCategory($category_xml);

				$query = "SELECT * FROM #__content WHERE catid =" . $joomla_category->id;
				$articles = $db->queryObjectList($query);

				foreach ($articles as $article) {
					if ($article->state != -2) {
						$this->_addItem(JText::_('Joomla article'), $this->_articleToXML($article, $joomla_category->alias));
					}
				}
			}
	    }
	    
		$query = "SELECT * FROM #__content WHERE catid = 0";
		$articles = $db->queryObjectList($query);

		foreach ($articles as $article) {
			if ($article->state != -2) {
				$this->_addItem(JText::_('Joomla article'), $this->_articleToXML($article, 0));
			}			
		}

		return parent::export();
		
	}
	
	protected function _articleToXML($article, $parent) {
		
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