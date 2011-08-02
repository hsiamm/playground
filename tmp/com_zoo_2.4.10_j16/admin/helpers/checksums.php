<?php
/**
* @package   com_zoo Component
* @file      checksums.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
   Class: ChecksumsHelper
   	  Checksum helper class.
*/
class ChecksumsHelper extends AppHelper {

	/*
		Function: verify
			Verify file checksums

		Parameters:
			$log - Log array
			$checksumFile - Checksum file path
			$callback - A callback function, which can be used to modify the path
			$files - Files to check checksum file against

		Returns:
			Boolean
	*/
	public function verify(&$log, $checksumFile = 'checksums', $callback = null, $files = array()) {
		$path = $this->app->path->path('root:');
		$checksum_files = array();

		if ($rows = file($checksumFile)) {

			foreach ($rows as $row) {				
				$parts = explode(' ', trim($row), 2);

				if (count($parts) == 2) {
					list($md5, $file) = $parts;

					if ($callback) {
						if (!($file = call_user_func($callback, $file))) {
							continue;
						}
					}

					$checksum_files[] = $file;
					
					if (!file_exists($path.$file)) {
						$log['missing'][] = $file;
					} elseif (md5_file($path.$file) != $md5) {
						$log['modified'][] = $file;
					}

				}
			}
		}

		foreach ($files as $file) {
			if (!in_array($file, $checksum_files)) {
				$log['unknown'][] = $file;
			}
		}

		return empty($log);
	}

}