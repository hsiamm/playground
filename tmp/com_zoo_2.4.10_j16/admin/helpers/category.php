<?php
/**
* @package   com_zoo Component
* @file      category.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
   Class: CategoryHelper
   The Helper Class for category
*/
class CategoryHelper extends AppHelper {

	/*
		Function: translateIDToAlias
			Translate category id to alias.

		Parameters:
			$id - Category id

		Returns:
			Mixed - Null or Category alias string
	*/
	public function translateIDToAlias($id){
		if ($category = $this->app->table->category->get($id)) {
			return $category->alias;
		}
		
		return null;
	}

	/*
		Function: translateAliasToID
			Translate category alias to id.
		
		Return:
			Int - The category id or 0 if not found
	*/
	public function translateAliasToID($alias) {

		// init vars
		$db = $this->app->database;

		// search alias
		$query = 'SELECT id'
			    .' FROM '.ZOO_TABLE_CATEGORY
			    .' WHERE alias = '.$db->Quote($alias)
				.' LIMIT 1';

		return $db->queryResult($query);
	}

	/*
		Function: getAlias
			Get unique category alias.

		Parameters:
			$id - Category id
			$alias - Category alias

		Returns:
			Mixed - Null or Category alias string
	*/	
	public function getUniqueAlias($id, $alias = '') {

		if (empty($alias) && $id) {
			$alias = JFilterOutput::stringURLSafe($this->app->table->category->get($id)->name);
		}
				
		if (!empty($alias)) {
			$i = 2;
			$new_alias = $alias;
			while ($this->checkAliasExists($new_alias, $id)) {
				$new_alias = $alias . '-' . $i++;
			}
			return $new_alias;
		}
		
		return $alias;
	}	
	
	/*
 		Function: checkAliasExists
 			Method to check if a alias already exists.
	*/
	public function checkAliasExists($alias, $id = 0) {

		$xid = intval($this->translateAliasToID($alias));
		if ($xid && $xid != intval($id)) {
			return true;
		}
		
		return false;
	}

	/*
		Function: getItemsRelatedCategoryIds
			Method to retrieve item's related category id's.

		Returns:
			Array - category id's
	*/
	public function getItemsRelatedCategoryIds($item_id, $published = false) {
		// select item to category relations
		$query = 'SELECT b.id'
		        .' FROM '.ZOO_TABLE_CATEGORY_ITEM.' AS a'
		        .' JOIN '.ZOO_TABLE_CATEGORY.' AS b ON a.category_id = b.id'
			    .' WHERE a.item_id='.(int) $item_id
			    .($published == true ? ' AND b.published = 1' : '')
				.' UNION SELECT 0'
				.' FROM '.ZOO_TABLE_CATEGORY_ITEM.' AS a'
				.' WHERE a.item_id='.(int) $item_id .' AND a.category_id = 0';

		return $this->app->database->queryResultArray($query);
	}

	/*
		Function: saveCategoryItemRelations
			Method to add category related item's.

		Returns:
			Boolean - true on succes
	*/
	public function saveCategoryItemRelations($item_id, $categories){

		//init vars
		$db = $this->app->database;

		if (!is_array($categories)) {
			$categories = array($categories);
		}

		$categories = array_unique($categories);

		// delete category to item relations
		$query = "DELETE FROM ".ZOO_TABLE_CATEGORY_ITEM
			    ." WHERE item_id=".(int) $item_id;

		// execute database query
		$db->query($query);

		$query_string = '(%s,' . (int) $item_id.')';
		$category_strings = array();
		foreach ($categories as $category) {
			if ($category !== '' && $category !== null) {
				$category_strings[] = sprintf($query_string, $category);
			}
		}

		// add category to item relations
		// insert relation to database
		if (!empty($category_strings)) {
			$query = "INSERT INTO ".ZOO_TABLE_CATEGORY_ITEM
					." (category_id, item_id) VALUES " . implode(',', $category_strings);

			// execute database query
			$db->query($query);
		}
		
		return true;
	}

	/*
		Function: deleteCategoryItemRelations
			Method to delete category related item's.

		Returns:
			Boolean - true on succes
	*/
	public function deleteCategoryItemRelations($category_id){

		// delete category to item relations
		$query = "DELETE FROM ".ZOO_TABLE_CATEGORY_ITEM
			    ." WHERE category_id = ".(int) $category_id;

		// execute database query
		return $this->app->database->query($query);

	}

}