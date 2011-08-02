<?php
/**
* @package   com_zoo Component
* @file      new.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: NewController
		The controller class for creating a new application
*/
class NewController extends AppController {

	public $group;
	public $application;
	
	public function __construct($default = array()) {
		parent::__construct($default);

		// get application group
		$this->group = $this->app->request->getString('group');

		// set base url
		$this->baseurl = $this->app->link(array('controller' => $this->controller), false);

		// if group exists
		if ($this->group) {
			
			// add group to base url
			$this->baseurl .= '&group='.$this->group;

			// create application object
			$this->application = $this->app->object->create('Application');
			$this->application->setGroup($this->group);
		}
	}

	public function display() {

		// set toolbar items
		$this->app->toolbar->title(JText::_('New App'), ZOO_ICON);		
		$this->app->zoo->toolbarHelp();
		
		// get applications
		$this->applications = $this->app->zoo->getApplicationGroups();	

		// display view
		$this->getView()->display();
	}

	public function add() {

		// disable menu
		$this->app->request->setVar('hidemainmenu', 1);

		// get application metadata
		$metadata = $this->application->getMetaData();
		
		// set toolbar items
		$this->app->system->application->set('JComponentTitle', $this->application->getToolbarTitle(JText::_('New App').': '.$metadata['name']));
		$this->app->toolbar->save();
		$this->app->toolbar->custom('', 'back', '', 'Back', false);
		$this->app->zoo->toolbarHelp();

		// get params
		$this->params = $this->application->getParams();

		// set default template
		$this->params->set('template', 'default');

		// template select
		$options = array($this->app->html->_('select.option', '', '- '.JText::_('Select Template').' -'));
		foreach ($this->application->getTemplates() as $template) {
			$metadata  = $template->getMetaData(); 
			$options[] = $this->app->html->_('select.option', $template->name, $metadata['name']);
		}
		
		$this->lists['select_template'] = $this->app->html->_('select.genericlist',  $options, 'template', '', 'value', 'text', $this->params->get('template'));

		// display view
		$this->getView()->setLayout('application')->display();		
	}

	public function save() {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');
		
		// init vars
		$post = $this->app->request->get('post:', 'array');
 
		try {

			// bind post
			$this->bind($this->application, $post, array('params'));

			// set params
			$params = $this->application
				->getParams()
				->remove('global.')
				->set('group', @$post['group'])
				->set('template', @$post['template'])
				->set('global.config.', @$post['params']['config'])
				->set('global.template.', @$post['params']['template']);

			if (isset($post['addons']) && is_array($post['addons'])) {
				foreach ($post['addons'] as $addon => $value) {
					$params->set("global.$addon.", $value);
				}
			}

			// save application
			$this->app->table->application->save($this->application);
			
			// set redirect
			$msg  = JText::_('Application Saved');
			$link = $this->app->link(array('changeapp' => $this->application->id), false);

		} catch (AppException $e) {
			
			// raise notice on exception
			$this->app->error->raiseNotice(0, JText::_('Error Saving Application').' ('.$e.')');

			// set redirect
			$msg  = null;
			$link = $this->baseurl.'&task=add';
						
		}

		$this->setRedirect($link, $msg);
	}
	
	public function getApplicationParams() {

		// init vars
		$template     = $this->app->request->getCmd('template');
		$this->params = $this->application->getParams();

		// set template
		$this->params->set('template', $template);

		// display view
		$this->getView()->setLayout('_applicationparams')->display();
	}

}

/*
	Class: NewControllerException
*/
class NewControllerException extends AppException {}