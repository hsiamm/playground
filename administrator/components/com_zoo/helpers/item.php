<?php
/**
* @package   com_zoo Component
* @file      item.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/*
   Class: ItemHelper
   The Helper Class for item
*/
class ItemHelper extends AppHelper {

	/*
		Function: translateIDToAlias
			Translate item id to alias.

		Parameters:
			$id - Item id

		Returns:
			Mixed - Null or Item alias string
	*/
	public function translateIDToAlias($id){
		$item = $this->app->table->item->get($id);

		if ($item) {
			return $item->alias;
		}
		
		return null;
	}
	
	/*
		Function: translateAliasToID
			Translate item alias to id.
		
		Return:
			Int - The item id or 0 if not found
	*/
	public function translateAliasToID($alias) {
		// search alias
		$query = 'SELECT id'
			    .' FROM '.ZOO_TABLE_ITEM
			    .' WHERE alias = '.$this->app->database->Quote($alias)
				.' LIMIT 1';

		return $this->app->database->queryResult($query);
	}

	/*
		Function: getUniqueAlias
			Get unique item alias.

		Parameters:
			$id - Item id
			$alias - Item alias

		Returns:
			Mixed - Null or Item alias string
	*/	
	public function getUniqueAlias($id, $alias = '') {

		if (empty($alias) && $id) {
			$alias = JFilterOutput::stringURLSafe($this->app->table->item->get($id)->name);
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

		$xid = (int)($this->app->item->translateAliasToID($alias));
		if ($xid && $xid != (int)($id)) {
			return true;
		}
		
		return false;
	}

}