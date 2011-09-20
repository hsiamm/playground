<?php
/**
 * Plugin Helper File
 *
 * @package     Modules Anywhere
 * @version     1.4.2b
 *
 * @author      Peter van Westen <peter@nonumber.nl>
 * @link        http://www.nonumber.nl
 * @copyright   Copyright © 2011 NoNumber! All Rights Reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die();

/**
** Plugin that places the button
*/
class plgButtonModulesAnywhereHelper
{
	function __construct( &$params )
	{
		$this->params = $params;
	}

	/**
	* Display the button
	*
	* @return array A two element array of ( imageName, textToInsert )
	*/
	function render( $name )
	{
		$mainframe =& JFactory::getApplication();

		$button = new JObject();

		if ( $mainframe->isSite() ) {
			$enable_frontend = $this->params->enable_frontend;
			if ( !$enable_frontend ) {
				return $button;
			}
		}

		JHTML::_( 'behavior.modal' );

		$document =& JFactory::getDocument();

		$button_style = 'modulesanywhere';
		if ( !$this->params->button_icon ) {
			$button_style = 'blank blank_modulesanywhere';
		}
		$document->addStyleSheet( JURI::root( true ).'/plugins/editors-xtd/modulesanywhere/css/style.css' );

		$link = 'index.php?nn_qp=1'
			.'&folder=plugins.editors-xtd.modulesanywhere'
			.'&file=modulesanywhere.inc.php'
			.'&name='.$name;

		$text = JText::_( str_replace( ' ', '_', $this->params->button_text ) );
		if ( $text == str_replace( ' ', '_', $this->params->button_text ) ) {
			$text = JText::_( $this->params->button_text );
		}

		$button->set( 'modal', true );
		$button->set( 'link', $link );
		$button->set( 'text', $text );
		$button->set( 'name', $button_style );
		$button->set( 'options', "{handler: 'iframe', size: {x:window.getSize().x-100, y: window.getSize().y-100}}" );

		return $button;
	}
}