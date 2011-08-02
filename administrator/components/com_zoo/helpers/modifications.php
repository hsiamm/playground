<?php
/**
* @package   com_zoo Component
* @file      modifications.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: ModificationsHelper
		The general helper Class for modifications
*/
class ModificationsHelper extends AppHelper {

	protected $_results = array();
	protected $_paths = array();
	protected $_ignore = array();

    /*
		Function: verify
			Verifies the ZOO installation.

		Returns:
			bool - true if ZOO hasn't been modified
	*/
	public function verify($ignore = array()) {
		$result = $this->check($ignore);
		return empty($result);
	}

    /*
		Function: check
			Checks for ZOO modifications.

		Returns:
			Array - modified files
	*/
	public function check($ignore = array()) {

		// init vars
		$ignore = (array) $ignore;
		$this->_results = array();
		$this->_paths = array(
			'site' => str_replace($this->app->path->path('root:'), '', $this->app->path->path('component.site:')),
			'admin' => str_replace($this->app->path->path('root:'), '', $this->app->path->path('component.admin:'))
		);
		$this->_ignore = array_merge($ignore, array('checksums', 'en-GB.com_zoo.ini', 'en-GB.com_zoo.sys.ini'));

		$files = array();
		foreach($this->app->path->files('component.admin:', true) as $file) {
			if (!in_array(basename($file), $this->_ignore)) {
				$files[] = $this->_paths['admin'] .'/'. $file;
			}
		}

		foreach($this->app->path->files('component.site:', true) as $file) {
			if (!in_array(basename($file), $this->_ignore)) {
				$files[] = $this->_paths['site'] .'/'. $file;
			}
		}

		if ($this->app->path->path('component.admin:checksums')) {
			$this->app->checksums->verify($this->_results, $this->app->path->path('component.admin:checksums'), array($this, 'modifyPath'), $files);
		} else {
			throw new AppModificationsException(JText::_('Unable to locate checksums file in ' . $this->app->path->path('component.admin:')));
		}

		return $this->_results;
	}

    /*
		Function: modifyPath
			Callback function for checksums verification.

		Parameters:
	      $path - Path to modify.

		Returns:
			void
	*/
	public function modifyPath($path) {

		if ($path) {

			if (in_array(basename($path), $this->_ignore)) {
				return false;
			}

			// redirect manifest file
			foreach (array('zoo.xml', 'file.script.php') as $file) {
				if ($path == $file) {
					$path = 'admin/' . $file;
				}
			}

			foreach ($this->_paths as $old_path => $new_path) {
				if (preg_match("/^$old_path/", $path)) {
					return preg_replace("/^$old_path/", $new_path, $path);
				}
			}
		}

		return false;
	}

    /*
		Function: clean
			Cleans any modifications from the filessystem.

		Returns:
			bool - true on success
	*/
	public function clean($ignore = array()) {

		// check for modifications
		$results = $this->check($ignore);
		if (isset($results['unknown'])) {
			foreach ($results['unknown'] as $file) {
				if (!empty($file) && JFile::exists(JPATH_ROOT . $file)) {
					// remove unknown file
					if (!JFile::delete(JPATH_ROOT . $file)) {
						$this->app->error->raiseWarning(0, sprintf(JText::_('Could not remove file (%s)'), $file));
					}
				}
			}
		}

		// remove empty sub folders
		$this->removeEmptySubFolders($this->app->path->path('component.admin:'));
		$this->removeEmptySubFolders($this->app->path->path('component.site:'));

		return true;

	}

    /*
		Function: removeEmptySubFolders
			Recursevly removes empty subfolders.

		Parameters:
	      $path - Path to folder.

		Returns:
			void
	*/
	public function removeEmptySubFolders($path) {
		$empty=true;
		foreach ($this->app->filesystem->readDirectory($path) as $file) {
			$empty &= is_dir($file) && $this->removeEmptySubFolders($file);
		}
		return $empty && JFolder::delete($path);
	}

}

/*
	Class: AppModificationsException
*/
class AppModificationsException extends AppException {}