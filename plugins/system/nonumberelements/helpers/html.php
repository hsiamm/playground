<?php
/**
 * nnHTML
 * extra JHTML functions
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

/**
 * nnHTML
 */
class nnHTML
{
	var $_version = '2.9.1';

	function selectlist( &$options, $name, $value, $id, $size = 0, $multiple = 0, $attribs = '', $j15 = 0 )
	{
		if( !$size ) {
			$size = ( ( count( $options ) > 10 ) ? 10 : count( $options ) );
		}
		$attribs .= ' size="'.$size.'"';
		if( $multiple ) {
			if ( !is_array( $value ) ) {
				$value = explode( ',', $value );
			}
			$attribs .= ' multiple="multiple"';
			if ( substr( $name, -2 ) != '[]' ) {
				$name .= '[]';
			}
		}

		foreach( $options as $i => $option ) {
			$option = (object) $option;
			if ( isset( $option->text ) ) {
				$option->text = str_replace( array( '&nbsp;', '&#160;' ), '___', $option->text );
				$options[$i] = $option;
			}
		}
		$html = JHTML::_( 'select.genericlist', $options, $name, 'class="inputbox" '.$attribs, 'value', 'text', $value, $id );
		$html = str_replace( '___', '&nbsp;', $html );

		$links = array();
		if ( $multiple ) {
			$links[] = '<a href="javascript://" onclick="nnScripts.toggleSelectListSelection(\''.$id.'\');">'
				.JText::_( 'NN_INVERT_SELECTION' )
				.'</a>';
		}
		if ( $size && count( $options ) > $size ) {
			$links[] = '<a href="javascript://" onclick="nnScripts.toggleSelectListSize(\''.$id.'\');" id="toggle_'.$id.'">'
				.'<span class="show">'.JText::_( 'NN_MAXIMIZE' ).'</span>'
				.'<span class="hide" style="display:none";>'.JText::_( 'NN_MINIMIZE' ).'</span>'
				.'</a>';
		}
		if ( !empty( $links ) ) {
			JHTML::_( 'behavior.mootools' );
			$document =& JFactory::getDocument();
			$document->addScript( JURI::root(true).'/plugins/system/nonumberelements/js/script.js?v='.$this->_version );
			$html = implode( ' - ', $links ).'<br />'.$html;
			if ( !$j15 ) {
				$html = '<fieldset class="radio" id="'.$id.'_fieldset">'.$html.'</fieldset>';
			}
		}

		return preg_replace( '#>\[\[\:(.*?)\:\]\]#si', ' style="\1">', $html );
	}
}