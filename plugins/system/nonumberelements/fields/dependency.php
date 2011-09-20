<?php
/**
 * Element: Dependency
 * Displays an error if given file is not found
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
 * Dependency Element
 *
 * Available extra parameters:
 * label	The name of the extension that is needed
 * file		The file to check (from the root)
 */
class nnElementDependency
{
	var $_version = '2.9.1';

	function getInput( $name, $id, $value, $params, $children, $j15 = 0 )
	{
		$this->params = $params;

		JHTML::_( 'behavior.mootools' );
		$document =& JFactory::getDocument();
		$document->addScript( JURI::root(true).'/plugins/system/nonumberelements/js/script.js?v='.$this->_version );

		$file = $this->def( 'file' );
		if ( !$file ) {
			$path = ( $this->def( 'path' ) == 'site' ) ? '' : DS.'administrator' ;
			$label = $this->def( 'label' );
			$file = $this->def( 'alias', $label );
			$file = preg_replace( '#[^a-z-]#', '', strtolower( $file ) );
			$extension = $this->def( 'extension' );
			switch( $extension ) {
				case 'com';
					$file = $path.DS.'components'.DS.'com_'.$file.DS.'com_'.$file.'.xml';
					break;
				case 'mod';
					$file = $path.DS.'modules'.DS.'mod_'.$file.DS.'mod_'.$file.'.xml';
					break;
				case 'plg_editors-xtd';
					$file = DS.'plugins'.DS.'editors-xtd'.DS.$file.'.xml';
					break;
				default:
					$file = DS.'plugins'.DS.'system'.DS.$file.'.xml';
					break;
			}
			$label = JText::_( $label ).' ('.JText::_( 'NN_'.strtoupper( $extension ) ).')';
		} else {
			$label = $this->def( 'label', 'the main extension' );
			$file = str_replace( '/', DS, $file );
		}

		$this->setMessage( $file, $label );

		if ( $j15 ) {
			$random = rand( 100000, 999999 );
			return '<div id="end-'.$random.'"></div><script type="text/javascript">NoNumberElementsHideTD( "end-'.$random.'" );</script>';
		} else {
			return;
		}
	}

	function setMessage( $file, $name )
	{
		jimport( 'joomla.filesystem.file' );

		if ( strpos( $file, '/administrator' ) === 0 ) {
			$file = str_replace( '/', DS, str_replace( '/administrator', JPATH_ADMINISTRATOR, $file ) );
		} else {
			$file = JPATH_SITE.str_replace( '/', DS, $file );
		}

		$file_alt = preg_replace( '#(com|mod)_([a-z-_]+\.)#', '\2', $file );

		if ( !JFile::exists( $file ) && !JFile::exists( $file_alt ) ) {
			$mainframe =& JFactory::getApplication();
			$msg = JText::sprintf( 'NN_THIS_EXTENSION_NEEDS_THE_MAIN_EXTENSION_TO_FUNCTION', JText::_( $name ) );
			$message_set = 0;
			$messageQueue = $mainframe->getMessageQueue();
			foreach ( $messageQueue as $queue_message ) {
				if ( $queue_message['type'] == 'error' && $queue_message['message'] == $msg ) {
					$message_set = 1;
					break;
				}
			}
			if ( !$message_set ) {
				$mainframe->enqueueMessage( $msg, 'error' );
			}
		}
	}

	private function def( $val, $default = '' )
	{
		return ( isset( $this->params[$val] ) && (string) $this->params[$val] != '' ) ? (string) $this->params[$val] : $default;
	}
}

if ( version_compare( JVERSION, '1.6.0', 'l' ) ) {
	// For Joomla 1.5
	class JElementNN_Dependency extends JElement
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var $_name = 'Dependency';

		function fetchTooltip( $label, $description, &$node, $control_name, $name )
		{
			return;
		}

		function fetchElement( $name, $value, &$node, $control_name )
		{
			$this->_nnelement = new nnElementDependency();
			return $this->_nnelement->getInput( $control_name.'['.$name.']', $control_name.$name, $value, $node->attributes(), $node->children(), 1 );
		}

		function setMessage( $file, $name )
		{
			$this->_nnelement = new nnElementDependency();
			return $this->_nnelement->setMessage( $file, $name );
		}
	}
} else {
	// For Joomla 1.6
	class JFormFieldNN_Dependency extends JFormField
	{
		/**
		 * The form field type
		 *
		 * @var		string
		 */
		public $type = 'Dependency';

		protected function getLabel()
		{
			return;
		}

		protected function getInput()
		{
			$this->_nnelement = new nnElementDependency();
			return $this->_nnelement->getInput( $this->name, $this->id, $this->value, $this->element->attributes(), $this->element->children() );
		}
	}
}