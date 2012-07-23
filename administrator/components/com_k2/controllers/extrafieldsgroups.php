<?php
/**
 * @version		$Id: extrafieldsgroups.php 1492 2012-02-22 17:40:09Z joomlaworks@gmail.com $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class K2ControllerExtraFieldsGroups extends JController
{

	function display() {
		JRequest::setVar('view', 'extrafieldsgroups');
		$model = & $this->getModel('extraFields');
		$view = & $this->getView('extrafieldsgroups', 'html');
		$view->setModel($model, true);
		parent::display();
	}
	
	function add() {
		$mainframe = &JFactory::getApplication();
		$mainframe->redirect('index.php?option=com_k2&view=extrafieldsgroup');
	}

	function edit() {
		$mainframe = &JFactory::getApplication();
		$cid = JRequest::getVar('cid');
		$mainframe->redirect('index.php?option=com_k2&view=extrafieldsgroup&cid='.$cid[0]);
	}
	
	function remove() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('extraFields');
		$model->removeGroups();
	}

}
