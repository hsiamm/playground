<?php
/**
 * @version   $Id: controller.php 430 2009-09-17 17:44:05Z edo888 $
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

jimport('joomla.application.component.controller');
require_once JPATH_ROOT.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'database'.DS.'mysql.php';

/**
 * Working Copy Controller class
 *
 */
class WCPController extends JController {

    /**
     * Constructor
     *
     * @params array Controller configuration array
     */
    function __construct($config = array()) {
        parent::__construct($config);

        require_once(JPATH_COMPONENT.DS.'helper.php');
        require_once(JPATH_COMPONENT.DS.'tables'.DS.'wcp.php');
        jimport('joomla.filesystem.file');

        // Register tasks
        $this->registerTask('add', 'edit');
        $this->registerTask('edit', 'edit');
        $this->registerTask('save', 'save');
        $this->registerTask('apply', 'save');
        $this->registerTask('cancel', 'cancel');
        $this->registerTask('remove', 'remove');
        $this->registerTask('diff', 'differences');
        $this->registerTask('refreshDiff', 'refreshDiff');
        $this->registerTask('createPatch', 'createPatch');
        $this->registerTask('applyPatch', 'applyPatch');
        $this->registerTask('commit', 'commit');
        $this->registerTask('merge', 'merge');
        $this->registerTask('revertChild', 'revertChild');
        $this->registerTask('syncChild', 'syncChild');
    }


    /**
     * Displays the childs view
     */
    function display() {
        JRequest::setVar('view', 'childs');
	    parent::display();
	}

	/**
	 * Displays the child view
	 */
    function edit() {
        JRequest::setVar('view', 'child');
        parent::display();
    }

    /**
     * Creates/Saves the child
     */
    function save() {
        list($cid) = JRequest::getVar('cid', array(''));
        if($cid == '') {
            WCPHelper::createChild();
            $this->setRedirect('index.php?option=com_wcp', JText::_('Child created successfully'));
        } else {
            WCPHelper::applyChild();
            if(JRequest::getVar('task') == 'save')
                $this->setRedirect('index.php?option=com_wcp', JText::_('Child info saved successfully'));
            else
                $this->setRedirect('index.php?option=com_wcp&task=edit&cid[]='.$cid, JText::_('Child info saved successfully'));
        }
    }

    /**
     * Cancels the edit operation
     */
    function cancel() {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');
        $this->setRedirect('index.php?option=com_wcp');
    }

    /**
     * Removes the child(s)
     */
    function remove() {
        WCPHelper::removeChild();
        $this->setRedirect('index.php?option=com_wcp', JText::_('Child(s) deleted successfully'));
    }

    /**
     * Displays the differences view
     */
    function differences() {
        JRequest::setVar('view', 'differences');
        parent::display();
    }

    /**
     * Refreshes the cache
     */
    function refreshDiff() {
        $cache =& JFactory::getCache('com_wcp', 'callback', 'file');
        $cache->clean('com_wcp', 'group');

        WCPHelper::setInternalTime();

        $this->setRedirect('index.php?option=com_wcp&task=differences', JText::_('List Refreshed'));
    }

    /**
     * Creates a patch
     */
    function createPatch() {
        WCPHelper::createPatch();

        //$this->setRedirect('index.php?option=com_wcp&task=differences', JText::_('Patch Created'));
    }

    /**
     * Applies a patch to the site
     */
    function applyPatch() {
        if(!JRequest::getVar('submitted', false)) {
            JRequest::setVar('view', 'applyPatch');
            parent::display();
        } else {
            // Check for request forgeries
            JRequest::checkToken() or jexit('Invalid Token');

            if(WCPHelper::applyPatch())
                $this->setRedirect('index.php?option=com_wcp', JText::_('Patch Applied Successfully'));
            else
                $this->setRedirect('index.php?option=com_wcp&task=applyPatch');
        }
    }

    /**
     * Commits changes to the master
     */
    function commit() {
        WCPHelper::commit();

        $cache =& JFactory::getCache('com_wcp', 'callback', 'file');
        $cache->clean('com_wcp', 'group');

        $this->setRedirect('index.php?option=com_wcp&task=differences', JText::_('Commit completed'));
    }

    /**
     * Merges 2 children
     */
    function merge() {
        WCPHelper::merge();
        $this->setRedirect('index.php?option=com_wcp', JText::_('Childs merged successfully'));
    }

    /**
     * Reverts made changes
     */
    function revertChild() {
        WCPHelper::revertChild();

        $cache =& JFactory::getCache('com_wcp', 'callback', 'file');
        $cache->clean('com_wcp', 'group');

        $this->setRedirect('index.php?option=com_wcp&task=differences', JText::_('Revert completed'));
    }

    /**
     * Synchronizes the child with the master
     */
    function syncChild() {
        WCPHelper::syncChild();

        $cache =& JFactory::getCache('com_wcp', 'callback', 'file');
        $cache->clean('com_wcp', 'group');

        $this->setRedirect('index.php?option=com_wcp&task=differences', JText::_('Synchronization proccess completed'));
    }

}
