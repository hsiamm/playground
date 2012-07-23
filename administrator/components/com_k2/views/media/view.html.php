<?php
/**
 * @version		$Id: view.html.php 1549 2012-04-18 18:57:05Z joomlaworks $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class K2ViewMedia extends JView {

	function display($tpl = null) {
		$mainframe = &JFactory::getApplication();
		$user = &JFactory::getUser();
		$document = &JFactory::getDocument();
		$document->addStyleSheet('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/themes/smoothness/jquery-ui.css');
		$document->addStyleSheet(JURI::root(true).'/media/k2/assets/css/theme.css?v=2.5.7');
		$document->addStyleSheet(JURI::root(true).'/media/k2/assets/css/elfinder.full.css?v=2.5.7');
		$document->addScript(JURI::root(true).'/media/k2/assets/js/elfinder.min.js?v=2.5.7');
		$type = JRequest::getCmd('type');
		$fieldID = JRequest::getCmd('fieldID');
		if($type=='video'){
			$mimes = "'video','audio'";
		}
		elseif ($type == 'image'){
			$mimes = "'image'";
		}
		else {
			$mimes = '';
		}
		$this->assignRef('mimes', $mimes);
		$this->assignRef('type', $type);
		$this->assignRef('fieldID', $fieldID);
		if($mainframe->isAdmin()) {
			$toolbar=& JToolBar::getInstance('toolbar');
			if(K2_JVERSION == '16'){
				JToolBarHelper::preferences('com_k2', 550, 875, 'K2_PARAMETERS');
			}
			else {
				$toolbar->appendButton('Popup', 'config', 'K2_PARAMETERS', 'index.php?option=com_k2&view=settings');
			}
			JToolBarHelper::title(JText::_('K2_MEDIA_MANAGER'), 'k2.png');
			$this->loadHelper('html');
			K2HelperHTML::subMenu();
		}
		parent::display($tpl);

	}

}
