<?php
/**
 * @version   $Id: view.html.php 413 2009-07-31 05:06:44Z edo888 $
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
 * Working Copy Childs View class
 *
 */
class WCPViewChilds extends JView {

    /**
     * Display childs
     *
     * @access public
     */
	function display($tpl = null) {
	    global $mainframe, $option;

	    require_once(JPATH_COMPONENT.DS.'helper.php');

        JToolBarHelper::title(JText::_('WCP Manager') . ': <small><small>[ ' . (WCPHelper::isMaster() ? JText::_('Master') : JText::_('Child')) . ' ]</small></small>', 'generic.png');
        JToolBarHelper::addNew();
        JToolBarHelper::editList();
        JToolBarHelper::deleteList();
        JToolBarHelper::custom('applyPatch', 'apply.png', 'apply.png', 'Apply Patch', '', false);
        JToolBarHelper::custom('merge', 'merge.png', 'merge.png', 'Merge');
        if(!WCPHelper::isMaster())
            JToolBarHelper::custom('diff', 'diff.png', 'diff.png', 'Differences', '', false);
        JToolBarHelper::help('screen.wcp', true);

        $filter_order     = $mainframe->getUserStateFromRequest($option.'.filter_order',     'filter_order',     'w.id', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option.'.filter_order_Dir', 'filter_order_Dir', '',     'word');

	    // Get data from the model
        $items =& $this->get('Data');

        // table ordering
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;

        $this->assignRef('lists', $lists);
        $this->assignRef('items', $items);
        parent::display($tpl);
	}

}