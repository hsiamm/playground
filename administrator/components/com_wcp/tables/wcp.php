<?php
/**
 * @version   $Id: wcp.php 412 2009-07-31 02:57:34Z edo888 $
 * @copyright Copyright (C) 2009 Edvard Ananyan. All rights reserved.
 * @author    Edvard Ananyan <edo888@gmail.com>
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Working Copy Table class
 *
 */
class TableWCP extends JTable {
	/** @var int */
	var $id				= null;
	/** @var string */
	var $name			= '';
	/** @var string */
	var $sid            = '';
	/** @var string */
	var $parent_sid     = '';
	/** @var string */
	var $path           = '';
	/** @var string */
	var $params			= '';

	function __construct(&$_db) {
		parent::__construct('#__wcp', 'id', $_db);

		$config = new JConfig;
		$this->set('parent_sid', $config->secret);
	}

	/**
	 * Overloaded check function
	 *
	 * @access public
	 * @return boolean
	 * @see JTable::check
	 * @since 1.5
	 */
	function check() {
		return true;
	}
}