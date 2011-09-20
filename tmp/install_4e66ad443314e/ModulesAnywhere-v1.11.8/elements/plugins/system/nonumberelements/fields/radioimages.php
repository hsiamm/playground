<?php
/**
 * Element: Radio Images
 * Displays a list of radio items and the images you can chose from
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
 * Radio Images Element
 */
class nnElementRadioImages
{
	var $_version = '2.9.1';

	function getInput( $name, $id, $value, $params, $children, $j15 = 0 )
	{
		$this->params = $params;

		jimport( 'joomla.filesystem.folder' );
		jimport( 'joomla.filesystem.file' );

		// path to images directory
		$path = JPATH_ROOT.DS.str_replace( '/', DS, $this->def( 'directory' ) );
		$filter = $this->def( 'filter' );
		$exclude = $this->def( 'exclude' );
		$stripExt = $this->def( 'stripext' );
		$files = JFolder::files( $path, $filter );
		$rowcount = $this->def( 'rowcount' );

		$options = array ();

		if ( !$this->def( 'hide_none' ) ) {
			$options[] = JHTML::_( 'select.option', '-1', JText::_( 'Do not use' ).'<br />' );
		}

		if ( !$this->def( 'hide_default' ) ) {
			$options[] = JHTML::_( 'select.option', '', JText::_( 'Use default' ).'<br />' );
		}

		if ( is_array( $files ) ) {
			$count = 0;
			foreach ( $files as $file ) {
				if ( $exclude ) {
					if ( preg_match( chr( 1 ) . $exclude . chr( 1 ), $file ) ) {
						continue;
					}
				}
				$count++;
				if ( $stripExt ) {
					$file = JFile::stripExt( $file );
				}
				$image = '<img src="../'.$this->def( 'directory' ).'/'.$file.'" style="padding-right: 10px;" title="'.$file.'" alt="'.$file.'" />';
				if ( $rowcount && $count >= $rowcount ) {
					$image .= '<br />';
					$count = 0;
				}
				$options[] = JHTML::_( 'select.option', $file, $image );
			}
		}

		$list = JHTML::_( 'select.radiolist', $options, ''.$name.'', '', 'value', 'text', $value, $id );

		$list = '<div style="float:left;">'.str_replace( '<input type="radio"', '</div><div style="float:left;"><input type="radio"', $list ).'</div>';
		$list = preg_replace( '#</label>(\s*)</div>#', '</label></div>\1', $list );
		$list = str_replace( '<br /></label></div>', '<br /></label></div><div style="clear:both;"></div>', $list );

		return $list;

	}

	private function def( $val, $default = '' )
	{
		return ( isset( $this->params[$val] ) && (string) $this->params[$val] != '' ) ? (string) $this->params[$val] : $default;
	}
}

if ( version_compare( JVERSION, '1.6.0', 'l' ) ) {
	// For Joomla 1.5
	class JElementNN_RadioImages extends JElement
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var $_name = 'RadioImages';

		function fetchElement( $name, $value, &$node, $control_name )
		{
			$this->_nnelement = new nnElementRadioImages();
			return $this->_nnelement->getInput( $control_name.'['.$name.']', $control_name.$name, $value, $node->attributes(), $node->children(), 1 );
		}
	}
} else {
	// For Joomla 1.6
	class JFormFieldNN_RadioImages extends JFormField
	{
		/**
		 * The form field type
		 *
		 * @var		string
		 */
		public $type = 'RadioImages';

		protected function getInput()
		{
			$this->_nnelement = new nnElementRadioImages();
			return $this->_nnelement->getInput( $this->name, $this->id, $this->value, $this->element->attributes(), $this->element->children() );
		}
	}
}