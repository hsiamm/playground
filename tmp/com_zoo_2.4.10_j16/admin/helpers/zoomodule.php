<?php
/**
* @package   com_zoo Component
* @file      zoomodule.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
   Class: ZooModuleHelper
   	  A class that contains zoo module helper functions
*/
class ZooModuleHelper extends AppHelper {

	public function getItems($params) {

		$items = array();
		if ($application = $this->app->table->application->get($params->get('application', 0))) {

			// set one or multiple categories
			$category = $params->get('category', 0);
			if ($params->get('subcategories')) {
				$categories = $application->getCategoryTree(true);
				if (isset($categories[$category])) {
					$category = array_merge(array($category), array_keys($categories[$category]->getChildren(true)));
				}
			}
			
			// get items
			if ($params->get('mode') == 'item') {
				$items = $this->getItem($params->get('item_id'));
			} else if ($params->get('mode') == 'types') {
				$items = $this->getItemsByType($application, $params->get('type'), $params->get('order', 'rdate'), $params->get('count', 4));
			} else {
				$items = $this->getItemsByCategory($application, $category, $params->get('order', 'rdate'), $params->get('count', 4));
			}

		}
		
		return $items;
	}
	
	public function getItem($id) {

		// init vars
		$table = $this->app->table->item;

		// get database
		$db = $table->database;

		// get date
		$date = $this->app->date->create();
		$now  = $db->Quote($date->toMySQL());
		$null = $db->Quote($db->getNullDate());

		// set query options
		$conditions =
		     "a.id = ".(int) $id
			." AND a.".$this->app->user->getDBAccessString()
			." AND a.state = 1"
			." AND (a.publish_up = ".$null." OR a.publish_up <= ".$now.")"
			." AND (a.publish_down = ".$null." OR a.publish_down >= ".$now.")";

		$options = array(
			'select' => 'a.*',
			'from' => ZOO_TABLE_ITEM.' AS a',
			'conditions' => array($conditions)
		);

		return $table->all($options);

	}
	
	public function getItemsByCategory($application, $categories, $ordering, $limit) {

		// init vars
		$order = $this->getOrder($ordering);

		return $this->app->table->item->getFromCategory($application->id, $categories, true, null, $order, 0, $limit);
	}

	public function getItemsByType($application, $type, $ordering, $limit) {

		// get database
		$db = $this->app->database;

		// get date
		$date = $this->app->date->create();
		$now  = $db->Quote($date->toMySQL());
		$null = $db->Quote($db->getNullDate());
		
		// set query options
		$conditions = 
		     "a.application_id = ".(int) $application->id
			." AND a.".$this->app->user->getDBAccessString()
			." AND a.state = 1"		
			." AND a.type = '?'"		
			." AND (a.publish_up = ".$null." OR a.publish_up <= ".$now.")"
			." AND (a.publish_down = ".$null." OR a.publish_down >= ".$now.")";

		$options = array(
			'select' => 'a.*',
			'from' => ZOO_TABLE_ITEM.' AS a',
			'conditions' => array($conditions, $type),
			'order' => $this->getOrder($ordering),
			'limit' => $limit);

		return $this->app->table->item->all($options);
	}
	
	public function getOrder($order) {
		$orders = array(
			'date'   => 'a.priority DESC, a.created ASC',
			'rdate'  => 'a.priority DESC, a.created DESC',
			'alpha'  => 'a.priority DESC, a.name ASC',
			'ralpha' => 'a.priority DESC, a.name DESC',
			'hits'   => 'a.priority DESC, a.hits DESC',
			'rhits'  => 'a.priority DESC, a.hits ASC',
			'random' => 'RAND()');

		return isset($orders[$order]) ? $orders[$order] : $orders['rdate'];
	}
}