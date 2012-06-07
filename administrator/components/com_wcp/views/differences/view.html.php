<?php
/**
 * @version   $Id: view.html.php 412 2009-07-31 02:57:34Z edo888 $
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

jimport('joomla.application.component.view');

/**
 * Working Copy Differences View class
 *
 */
class WCPViewDifferences extends JView {

    /**
     * Display differences
     *
     * @access public
     */
	function display($tpl = null) {
        JToolBarHelper::title(JText::_('WCP Manager') . ': <small><small>[ ' . JText::_('Differences') . ' ]</small></small>', 'generic.png');
        JToolBarHelper::custom('createPatch', 'new.png', 'new.png', 'Create Patch');
        JToolBarHelper::custom('syncChild', 'sync.png', 'sync.png', 'Synchronize', '', false);
        JToolBarHelper::custom('commit', 'commit.png', 'commit.png', 'Commit');
        JToolBarHelper::custom('revertChild', 'revert.png', 'revert.png', 'Revert');
        JToolBarHelper::custom('refreshDiff', 'refresh.png', 'refresh.png', 'Refresh', '', false);
        JToolBarHelper::custom('cancel', 'back.png', 'back.png', 'Back', '', false);
        JToolBarHelper::help('screen.wcp.differences', true);

        $cache =& JFactory::getCache('com_wcp', 'callback', 'file');
        $cache->setCaching(true);

	    // Get data from the cache
	    $items = $cache->call(array('WCPHelper', 'getDifferences'));
	    $db_items = $cache->call(array('WCPHelper', 'getDatabaseDifferences'));
	    $table_items = $cache->call(array('WCPHelper', 'getTableDifferences'));

        $this->assignRef('items', $items);
        $this->assignRef('db_items', $db_items);
        $this->assignRef('table_items', $table_items);
        parent::display($tpl);
	}

}