<?php
/**
* @package   com_zoo Component
* @file      update.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: UpdateHelper
		The general helper Class for updates
*/
class UpdateHelper extends AppHelper {

    /*
		Function: requiresUpdate
			Checks if ZOO needs to be updated.

		Returns:
			bool - true if ZOO needs to be updated
	*/
	public function required() {

		// get and sort update files
		$files = $this->getUpdateFiles();

		// check if there are update files
		if (empty($files)) {
			return false;
		}

		// get version from latest update file
		$version = basename(array_pop($files), ".php");

		// compare latest update file version against current ZOO version
		return version_compare($this->getCurrentVersion(), $version) < 0;

	}

    /*
		Function: run
			Performs the next update.

		Returns:
			Array - response array
	*/
	public function run() {

		// check if update is required
		if (!$this->required()) {
			return $this->_createResponse('No update required.', false, false);
		}

		// get current version
		$current_version = $this->getCurrentVersion();

		// find and run the next update
		foreach ($this->getUpdateFiles() as $file) {
			$version = basename($file, '.php');
			if ((version_compare($version, $current_version) > 0)) {
				$class = 'Update'.str_replace('.', '', $version);
				if ($this->app->loader->register($class, 'updates:'.$file)) {

					// make sure class implemnts iUpdate interface
					$r = new ReflectionClass($class);
					if ($r->isSubclassOf('iUpdate') && !$r->isAbstract()) {
						
						try {

							// run the update
							$r->newInstance()->run($this->app);
							
						} catch (Exception $e) {

							return $this->_createResponse("Error during update! ($e)", true, false);

						}

						// set current version
						$this->setVersion($version);
						return $this->_createResponse('Successfully updated to version '.$version, false, $this->required());
					}
				}
			}
		}

		return $this->_createResponse('No update found.', false, false);
		
	}

    /*
		Function: refreshDBTableIndexes
			Drops and recreates all ZOO database table indexes.

		Returns:
			void
	*/
	public function refreshDBTableIndexes() {

		// sanatize table indexes
		if ($this->app->path->path('component.admin:installation/index.sql')) {

			$db = $this->app->database;

			// read index.sql
			$buffer = JFile::read($this->app->path->path('component.admin:installation/index.sql'));

			// Create an array of queries from the sql file
			jimport('joomla.installer.helper');
			$queries = JInstallerHelper::splitSql($buffer);
			if (!empty($queries)) {

				foreach($queries as $query) {

					// replace table prefixes
					$query = $db->replacePrefix($query);

					// parse table name
					preg_match('/ALTER\s*TABLE\s*`(.*)`/i', $query, $result);

					if (count($result) < 2) {
						continue;
					}

					$table = $result[1];

					// check if table exists
					if (!$db->queryResult('SHOW TABLES LIKE ' . $db->Quote($table))) {
						continue;
					}

					// get existing indexes
					$indexes = $db->queryObjectList('SHOW INDEX FROM ' . $table);

					// drop existing indexes
					$removed = array();
					foreach ($indexes as $index) {
						if (in_array($index->Key_name, $removed)) {
							continue;
						}
						if ($index->Key_name != 'PRIMARY') {
							$db->query('DROP INDEX ' . $index->Key_name . ' ON ' . $table);
							$removed[] = $index->Key_name;
						}
					}

					// add new indexes
					$db->query($query);
				}
			}
		}
	}

    /*
		Function: getUpdateFiles
			Returns all update files.

		Returns:
			Array - update files
	*/
	public function getUpdateFiles() {
		// get and sort update files
		$files = $this->app->path->files('updates:', false, '/^\d+.*\.php$/');
		usort($files, create_function('$a, $b', 'return version_compare(basename($a, ".php"), basename($b, ".php"));'));
		return $files;
	}

    /*
		Function: setVersion
			Writes the current version in versions table.

		Returns:
			void
	*/
	public function setVersion($version) {

		// remove previous versions
		$this->app->database->query('TRUNCATE TABLE ' . ZOO_TABLE_VERSION);

		// set version
		$this->app->database->query('INSERT INTO '.ZOO_TABLE_VERSION.' SET version=' . $this->app->database->Quote($version));
	}

    /*
		Function: getCurrentVersion
			Gets the current update version from versions table.

		Returns:
			String - version
	*/
	public function getCurrentVersion() {

		// make sure versions table is present
		$this->app->database->query('CREATE TABLE IF NOT EXISTS '.ZOO_TABLE_VERSION.' (version varchar(255) NOT NULL) ENGINE=MyISAM;');

		return $this->app->database->queryResult('SELECT version FROM '.ZOO_TABLE_VERSION);
	}

	protected function _createResponse($message, $error, $continue) {
		$message = JText::_($message);
		return compact ('message', 'error', 'continue');
	}

}

interface iUpdate {

    /*
		Function: run
			Performs the update.

		Returns:
			bool - true if updated successful
	*/
	public function run($app);
	
}

/*
	Class: UpdateAppException
*/
class UpdateAppException extends AppException {}