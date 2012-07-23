<?php
/**
 * @version		$Id: user.php 1492 2012-02-22 17:40:09Z joomlaworks@gmail.com $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class K2ControllerUser extends JController
{

	function display() {
		JRequest::setVar('view', 'user');
		parent::display();
	}

	function save() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('user');
		$model->save();
	}

	function apply() {
		$this->save();
	}

	function cancel() {
		$mainframe = &JFactory::getApplication();
		$mainframe->redirect('index.php?option=com_k2&view=users');
	}
	
	function report() {
		$model = JModel::getInstance('User', 'K2Model');
		$model->setState('id', JRequest::getInt('id'));
		$model->reportSpammer();
		$this->setRedirect('index.php?option=com_k2&view=users');
	}

}
