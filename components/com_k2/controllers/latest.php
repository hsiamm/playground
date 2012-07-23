<?php
/**
 * @version		$Id: latest.php 1492 2012-02-22 17:40:09Z joomlaworks@gmail.com $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class K2ControllerLatest extends JController {
	function display() {
		$view = &$this->getView('latest', 'html');
		$model=&$this->getModel('itemlist');
		$view->setModel($model);
		$itemModel=&$this->getModel('item');
		$view->setModel($itemModel);
	    $user = &JFactory::getUser();
        if ($user->guest){
            $cache = true;
        }
        else {
            $cache = false;
        }
		parent::display($cache);
	}

}
