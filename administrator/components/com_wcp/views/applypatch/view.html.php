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
 * Working Copy Apply Patch View class
 *
 */
class WCPViewApplyPatch extends JView {

    /**
     * Display Apply Patch form
     *
     * @access public
     */
    function display($tpl = null) {
        JToolBarHelper::title(JText::_('WCP Manager') . ': <small><small>[ ' . JText::_('Apply Patch') . ' ]</small></small>', 'generic.png');
        JToolBarHelper::custom('cancel', 'back.png', 'back.png', 'Back', '', false);
        JToolBarHelper::help('screen.wcp.applypatch', true);

        parent::display($tpl);
    }

}