<?php
/**
* @package   com_zoo Component
* @file      module.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: ModuleHelper
		The general helper Class for modules
*/
class ModuleHelper extends AppHelper {

	/*
	   Function: load
	       Load Joomla modules.

	   Returns:
	       Array - modules
	*/
	public function load() {
		static $modules;

		if (isset($modules)) {
			return $modules;
		}

		$db	     = $this->app->database;
		$modules = array();

		$query = "SELECT id, title, module, position, content, showtitle, params, published"
			." FROM #__modules AS m"
			." LEFT JOIN #__modules_menu AS mm ON mm.moduleid = m.id"
			." WHERE "
			."m.".$this->app->user->getDBAccessString()
			." AND m.client_id = 0"
			." ORDER BY position, ordering";

		$db->setQuery($query);

		if (null === ($modules = $db->loadObjectList('id'))) {
			$this->app->error->raiseWarning('SOME_ERROR_CODE', JText::_('Error Loading Modules').$db->getErrorMsg());
			return false;
		}

		foreach ($modules as $i => $module) {
			$file					= $modules[$i]->module;
			$custom 				= $this->app->string->substr($file, 0, 4) == 'mod_' ? 0 : 1;
			$modules[$i]->user  	= $custom;
			$modules[$i]->name		= $custom ? $modules[$i]->title : $this->app->string->substr($file, 4);
			$modules[$i]->style		= null;
			$modules[$i]->position	= $this->app->string->strtolower($modules[$i]->position);
		}

		return $modules;
	}

	/*
	   Function: load
	       Enable Joomla module.

	   Returns:
	       void
	*/
	public function enable($module, $position, $menuid = 0) {

		$query = "UPDATE #__modules, (SELECT MAX(ordering) +1 as ord FROM #__modules WHERE position = '$position') tt"
		." SET published = 1, position = '$position', ordering = tt.ord"
		." WHERE module = '$module'";
		$this->app->database->query($query);

		if (!$this->app->joomla->isVersion('1.5')) {

			$query = "INSERT IGNORE #__modules_menu"
			." SET menuid = $menuid, moduleid = (SELECT id FROM #__modules WHERE module = '$module')";
			$this->app->database->query($query);

			$query = "UPDATE #__extensions, (SELECT MAX(ordering) +1 as ord FROM #__modules WHERE position = '$position') tt"
			." SET enabled = 1, ordering = tt.ord"
			." WHERE element = '$module'";
			$this->app->database->query($query);

		}

	}

}