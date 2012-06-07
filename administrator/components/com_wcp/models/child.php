<?php
/**
 * @version   $Id: child.php 426 2009-08-19 14:30:31Z edo888 $
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
 * Child model class
 *
 */
class WCPModelChild extends JModel {

    var $_data = null;

    function __construct() {
        parent::__construct();
    }

    /**
     * Build query to get child data
     *
     * @access public
     * @return string
     */
    function _buildQuery() {
        $where = $this->_buildWhere();
        $query = 'SELECT w.* FROM #__wcp AS w'
            . $where;
        return $query;
    }

    /**
     * Build where clause
     *
     * @access public
     * @return string
     */
    function _buildWhere() {
        $cid = JRequest::getVar('cid', array(0), '', 'array');
        $where = ' WHERE id = ' . (JRequest::getCmd('task', 'add') == 'add' ? 0 : (int) $cid[0]);
        return $where;
    }

    /**
     * Load child data
     *
     * @access public
     * @return array
     */
    function getData() {
        // Lets load the data if it doesn't already exist
        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            @list($this->_data) = $this->_getList($query);
        }

        return $this->_data;
    }
}