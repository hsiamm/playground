<?php
/**
 * Main Plugin File
 * Does all the magic!
 *
 * @package     NoNumber! Elements
 * @version     2.9.1
 *
 * @author      Peter van Westen <peter@nonumber.nl>
 * @link        http://www.nonumber.nl
 * @copyright   Copyright © 2011 NoNumber! All Rights Reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die();

$mainframe =& JFactory::getApplication();
if( $mainframe->isAdmin() ) {
	// load the NoNumber! Elements language file
	$lang =& JFactory::getLanguage();
	if ( $lang->getTag() != 'en-GB' ) {
		// Loads English language file as fallback (for undefined stuff in other language file)
		$lang->load( 'plg_system_nonumberelements', JPATH_ADMINISTRATOR, 'en-GB' );
	}
	$lang->load( 'plg_system_nonumberelements', JPATH_ADMINISTRATOR, null, 1 );
}

/**
* Plugin that loads Elements
*/
class plgSystemNoNumberElements extends JPlugin
{
	var $_version = '2.9.1';

	function __construct( &$subject, $config )
	{
		parent::__construct( $subject, $config );

		$mainframe =& JFactory::getApplication();
		if( $mainframe->isSite() ) {
			return;
		}

		$template = $mainframe->getTemplate();
		if( $template == 'adminpraise3' ) {
			$document =& JFactory::getDocument();
			$document->addStyleSheet( JURI::root( true ).'/plugins/system/nonumberelements/css/ap3.css?v='.$this->_version );
		}
	}

	function onAfterRoute()
	{
		$mainframe =& JFactory::getApplication();
		if ( $mainframe->isSite() && JRequest::getCmd( 'option' ) == 'com_search' ) {
			$classes = get_declared_classes();
			if ( !in_array( 'SearchModelSearch', $classes ) && !in_array( 'SearchModelSearch', $classes ) ) {
				require_once JPATH_PLUGINS.DS.'system'.DS.'nonumberelements'.DS.'helpers'.DS.'search.php';
			}
		}

		if( !JRequest::getInt( 'nn_qp' ) ) {
			return;
		}

		// Include the Helper
		require_once JPATH_PLUGINS.DS.'system'.DS.'nonumberelements'.DS.'helper.php';
		$this->helper = new plgSystemNoNumberElementsHelper;
	}
}