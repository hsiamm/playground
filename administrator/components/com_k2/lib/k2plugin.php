<?php
/**
 * @version		$Id: k2plugin.php 1545 2012-04-17 13:09:26Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

JLoader::register('K2Parameter', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'lib'.DS.'k2parameter.php');

class K2Plugin extends JPlugin {

	/**
	 * Below we list all available BACKEND events, to trigger K2 plugins and generate additional fields in the item, category and user forms.
	 */

	/* ------------ Functions to render plugin parameters in the backend - no need to change anything ------------ */
	function onRenderAdminForm( & $item, $type, $tab='') {
	
		$mainframe = &JFactory::getApplication();
		$manifest = (K2_JVERSION == '15')? JPATH_SITE.DS.'plugins'.DS.'k2'.DS.$this->pluginName.'.xml': JPATH_SITE.DS.'plugins'.DS.'k2'.DS.$this->pluginName.DS.$this->pluginName.'.xml';
		if ( !empty ($tab)) {
			$path = $type.'-'.$tab;
		}
		else {
			$path = $type;
		}
		if(!isset($item->plugins))
		{
			$item->plugins = NULL;
		}
		$form = new K2Parameter($item->plugins, $manifest, $this->pluginName);
		$fields = $form->render('plugins', $path);
		if ($fields){
			$plugin = new JObject;
			$plugin->set('name', $this->pluginNameHumanReadable);
			$plugin->set('fields', $fields);
			return $plugin;	
		}
	}

}
