<?php
/**
 * @version		$Id: helper.php 1492 2012-02-22 17:40:09Z joomlaworks@gmail.com $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class modK2StatsHelper {

	function getLatestItems() {
		JModel::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'models');
		$model = &JModel::getInstance('cpanel', 'K2Model');
		return $model->getLatestItems();
	}

	function getPopularItems() {
		JModel::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'models');
		$model = &JModel::getInstance('cpanel', 'K2Model');
		return $model->getPopularItems();
	}

	function getMostCommentedItems() {
		JModel::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'models');
		$model = &JModel::getInstance('cpanel', 'K2Model');
		return $model->getMostCommentedItems();
	}

	function getLatestComments() {
		JModel::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'models');
		$model = &JModel::getInstance('cpanel', 'K2Model');
		return $model->getLatestComments();
	}

	function getStatistics() {
		JModel::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'models');
		$model = &JModel::getInstance('cpanel', 'K2Model');
		$statistics = new JObject();
		$statistics->numOfItems = $model->countItems();
		$statistics->numOfTrashedItems = $model->countTrashedItems();
		$statistics->numOfFeaturedItems = $model->countFeaturedItems();
		$statistics->numOfComments = $model->countComments();
		$statistics->numOfCategories = $model->countCategories();
		$statistics->numOfTrashedCategories = $model->countTrashedCategories();
		$statistics->numOfUsers = $model->countUsers();
		$statistics->numOfUserGroups = $model->countUserGroups();
		$statistics->numOfTags = $model->countTags();
		return $statistics;
	}

}
