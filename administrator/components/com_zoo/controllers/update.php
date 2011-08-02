<?php
/**
* @package   com_zoo Component
* @file      update.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: UpdateController
		The controller class for updates
*/
class UpdateController extends AppController {

	public function __construct($default = array()) {
		parent::__construct($default);

		// set base url
		$this->baseurl = $this->app->link(array('controller' => $this->controller), false);

	}

	public function display() {

		// set toolbar items
		$this->app->toolbar->title(JText::_('ZOO Update'), ZOO_ICON);
		$this->app->zoo->toolbarHelp();

		$this->app->html->_('behavior.tooltip');

		if (!$this->update = $this->app->update->required()) {
			$this->app->system->application->redirect($this->app->link());
		}

		// display view
		$this->getView()->display();
	}

	public function step() {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');

		$response = $this->app->update->run();

		echo json_encode($response);
	}
	
}

/*
	Class: UpdateAppControllerException
*/
class UpdateAppControllerException extends AppException {}