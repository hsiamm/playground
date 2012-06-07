<?php
/**
 * @version   $Id: childs.php 412 2009-07-31 02:57:34Z edo888 $
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
 * Childs model class
 *
 */
class WCPModelChilds extends JModel {

    var $_data = null;

    function __construct() {
        parent::__construct();
    }

    /**
     * Build query to get childs
     *
     * @access public
     * @return string
     */
    function _buildQuery() {
        $where   = $this->_buildWhere();
        $orderby = $this->_buildOrderBy();

        $query = 'SELECT w.* FROM #__wcp AS w'
            . $where
            . $orderby;
        return $query;
    }

    /**
     * Build where clause
     *
     * @access public
     * @return string
     */
    function _buildWhere() {
        $config = new JConfig();
        $where = ' WHERE parent_sid = "' . $config->secret .'"';
        return $where;
    }

    /**
     * Build ordering
     *
     * @access public
     * @return string
     */
    function _buildOrderBy() {
        global $mainframe, $option;

        $filter_order     = $mainframe->getUserStateFromRequest($option.'.filter_order',     'filter_order',     'w.id', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option.'.filter_order_Dir', 'filter_order_Dir', '',     'word');

        $orderby = ' ORDER BY '. $filter_order .' '. $filter_order_Dir;
        return $orderby;
    }

    /**
     * Load childs data
     *
     * @access public
     * @return array
     */
    function getData() {
        // Lets load the data if it doesn't already exist
        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query);
        }

        return $this->_data;
    }
}