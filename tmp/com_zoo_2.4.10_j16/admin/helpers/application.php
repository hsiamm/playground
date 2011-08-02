<?php
/**
* @package   com_zoo Component
* @file      application.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/*
   Class: ApplicationHelper
   The Helper Class for application
*/
class ApplicationHelper extends AppHelper {

	/*
		Function: getApplications
			Get all applications for an application group.

		Parameters:
			$group - Application group

		Returns:
			Array - The applications of the application group
	*/
    public function getApplications($group) {
        // get application instances for selected group
        $applications = array();
        foreach ($this->app->table->application->all(array('order' => 'name')) as $application) {
            if ($application->getGroup() == $group) {
                $applications[$application->id] = $application;
            }
        }
        return $applications;
    }

	/*
		Function: translateIDToAlias
			Translate application id to alias.

		Parameters:
			$id - Application id

		Returns:
			Mixed - Null or Category alias string
	*/
	public function translateIDToAlias($id){
		$application = $this->app->table->application->get($id);

		if ($application) {
			return $application->alias;
		}

		return null;
	}

	/*
		Function: translateAliasToID
			Translate application alias to id.

		Return:
			Int - The application id or 0 if not found
	*/
	public function translateAliasToID($alias) {

		// search alias
		$query = 'SELECT id'
			    .' FROM '.ZOO_TABLE_APPLICATION
			    .' WHERE alias = '.$this->app->database->Quote($alias)
				.' LIMIT 1';

		return $this->app->database->queryResult($query);
	}

	/*
		Function: getAlias
			Get unique application alias.

		Parameters:
			$id - Application id
			$alias - Application alias

		Returns:
			Mixed - Null or Application alias string
	*/
	public function getUniqueAlias($id, $alias = '') {

		if (empty($alias) && $id) {
			$alias = JFilterOutput::stringURLSafe($this->app->table->application->get($id)->name);
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
}
