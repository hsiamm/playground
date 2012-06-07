<?php
/**
 * @version   $Id: admin.wcp.php 412 2009-07-31 02:57:34Z edo888 $
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

// Make sure the user is authorized to view this page
$acl =& JFactory::getACL();
$acl->addACL('com_wcp', 'manage', 'users', 'super administrator');
$user =& JFactory::getUser();
if(!$user->authorize('com_wcp', 'manage'))
    $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

// Require the base controller
require_once(JPATH_COMPONENT.DS.'controller.php');

// Create the controller
$controller = new WCPController();

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();