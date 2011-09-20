<?php
/**
 * Main Plugin File
 * Does all the magic!
 *
 * @package     Modules Anywhere
 * @version     1.11.8
 *
 * @author      Peter van Westen <peter@nonumber.nl>
 * @link        http://www.nonumber.nl
 * @copyright   Copyright © 2011 NoNumber! All Rights Reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die();

// Import library dependencies
jimport( 'joomla.plugin.plugin' );

/**
* Button Plugin that places Editor Buttons
*/
class plgButtonModulesAnywhere extends JPlugin
{
	/**
	* Display the button
	*
	* @return array A two element array of ( imageName, textToInsert )
	*/
	function onDisplay( $name )
	{
		jimport( 'joomla.filesystem.file' );

		// return if system plugin is not installed
		if ( !JFile::exists( JPATH_PLUGINS.DS.'system'.DS.$this->_name.DS.$this->_name.'.php' ) ) {
			return;
		}

		// return if NoNumber! Elements plugin is not installed
		if ( !JFile::exists( JPATH_PLUGINS.DS.'system'.DS.'nonumberelements'.DS.'nonumberelements.php' ) ) {
			return;
		}

		// load the admin language file
		$lang =& JFactory::getLanguage();
		if ( $lang->getTag() != 'en-GB' ) {
			// Loads English language file as fallback (for undefined stuff in other language file)
			$lang->load( 'plg_'.$this->_type.'_'.$this->_name, JPATH_ADMINISTRATOR, 'en-GB' );
		}
		$lang->load( 'plg_'.$this->_type.'_'.$this->_name, JPATH_ADMINISTRATOR, null, 1 );

		// Load plugin parameters
		require_once JPATH_PLUGINS.DS.'system'.DS.'nonumberelements'.DS.'helpers'.DS.'parameters.php';
		$parameters =& NNParameters::getParameters();
		$params = $parameters->getParams( $this->params->_raw, JPATH_PLUGINS.DS.$this->_type.DS.$this->_name.'.xml' );

		// Include the Helper
		require_once JPATH_PLUGINS.DS.$this->_type.DS.$this->_name.DS.'helper.php';
		$class = get_class( $this ).'Helper';
		$this->helper = new $class( $params );

		return $this->helper->render( $name );
	}
}