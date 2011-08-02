<?php
/**
* @package   com_zoo Component
* @file      2.4.8.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

class Update248 implements iUpdate {

    /*
		Function: run
			Performs the update.

		Returns:
			bool - true if updated successful
	*/
	public function run($app) {
		
		// change elements field to type LONGTEXT
		$fields = $app->database->getTableFields(ZOO_TABLE_ITEM);
		if (isset($fields[ZOO_TABLE_ITEM]) && array_key_exists('elements', $fields[ZOO_TABLE_ITEM])) {
			if($fields[ZOO_TABLE_ITEM]['elements'] != 'longtext') {
				$app->database->query('ALTER TABLE '.ZOO_TABLE_ITEM.' MODIFY elements LONGTEXT NOT NULL');
			}
		}		
	}

}