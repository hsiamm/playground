<?php
/**
 * Element: Version
 * Displays the version check
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
 * Version Element
 *
 * Available extra parameters:
 * xml			The title
 * description		The description
 */
class nnElementVersion
{
	var $_version = '2.9.1';

	function getInput( $name, $id, $value, $params, $children, $j15 = 0 )
	{
		$this->params = $params;

		$xml = $this->def( 'xml' );
		$extension = $this->def( 'extension' );

		$user = JFactory::getUser();
		$authorise = $j15 ? ( $user->usertype == 'Super Administrator' || $user->usertype == 'Administrator' ) : $user->authorise( 'core.manage', 'com_installer' );

		if( !strlen( $extension ) || !strlen( $xml ) || !$authorise ) {
			return;
		}

		// Import library dependencies
		require_once JPATH_PLUGINS.DS.'system'.DS.'nonumberelements'.DS.'helpers'.DS.'versions.php';
		$versions = NNVersions::instance();

		return $versions->getMessage( $extension, $xml );
	}

	private function def( $val, $default = '' )
	{
		return ( isset( $this->params[$val] ) && (string) $this->params[$val] != '' ) ? (string) $this->params[$val] : $default;
	}
}

if ( version_compare( JVERSION, '1.6.0', 'l' ) ) {
	// For Joomla 1.5
	class JElementNN_Version extends JElement
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var $_name = 'Version';

		function fetchTooltip( $label, $description, &$node, $control_name, $name )
		{
			return;
		}

		function fetchElement( $name, $value, &$node, $control_name )
		{
			$this->_nnelement = new nnElementVersion();
			return $this->_nnelement->getInput( $control_name.'['.$name.']', $control_name.$name, $value, $node->attributes(), $node->children(), 1 );
		}
	}
} else {
	// For Joomla 1.6
	class JFormFieldNN_Version extends JFormField
	{
		/**
		 * The form field type
		 *
		 * @var		string
		 */
		public $type = 'Version';

		protected function getLabel()
		{
			return;
		}

		protected function getInput()
		{
			$this->_nnelement = new nnElementVersion();
			return $this->_nnelement->getInput( $this->name, $this->id, $this->value, $this->element->attributes(), $this->element->children() );
		}
	}
}