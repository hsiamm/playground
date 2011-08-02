<?php
/**
* @package   ZOO Category
* @file      helper.php
* @version   2.4.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class CategoryModuleHelper extends AppHelper {

    public function render($category, $params, $level) {

		// init vars
		$max_depth = $params->get('depth', 0);

		if ($menu_item = $params->get('menu_item')) {
			$url = $this->app->link(array('task' => 'category', 'category_id' => $category->id, 'Itemid' => $menu_item));
		} else {
			$url = JRoute::_($this->app->route->category($category));
		}

		$result   = array();
		$result[] = '<li>';
		$result[] = '<a href="'.$url.'">'.$category->name.'</a>';

		if ((!$max_depth || $max_depth >= $level) && ($children = $category->getChildren()) && !empty($children)) {
			$result[] = '<ul class="level'.$level.'">';
			foreach ($children as $child) {
				$result[] = $this->render($child, $params, $level+1);
			}
			$result[] = '</ul>';
		}

		$result[] = '</li>';

		return implode("\n", $result);
	}

}