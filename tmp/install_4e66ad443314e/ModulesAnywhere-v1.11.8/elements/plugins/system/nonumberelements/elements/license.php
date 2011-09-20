<?php
/**
 * Element: License
 * Displays the License state
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
 * License Element
 *
 * Available extra parameters:
 * xml			The title
 * description		The description
 */
class nnElementLicense
{
	var $_version = '2.9.1';

	function getInput( $name, $id, $value, $params, $children, $j15 = 0 )
	{
		$this->params = $params;

		$extension = $this->def( 'extension' );

		if( !strlen( $extension ) ) {
			return;
		}

		// Import library dependencies
		require_once JPATH_PLUGINS.DS.'system'.DS.'nonumberelements'.DS.'helpers'.DS.'licenses.php';
		$licenses = NNLicenses::instance();

		return $licenses->getMessage( $extension );

	}

	private function def( $val, $default = '' )
	{
		return ( isset( $this->params[$val] ) && (string) $this->params[$val] != '' ) ? (string) $this->params[$val] : $default;
	}
}

/* For backward compatibility */
if( !function_exists( 'NoNumber_License_outputState' ) ) {
	function NoNumber_License_outputState( $extension )
	{
		require_once JPATH_PLUGINS.DS.'system'.DS.'nonumberelements'.DS.'helpers'.DS.'licenses.php';
		$licenses = NNLicenses::instance();

		return $licenses->getMessage( $extension, 1 );
	}
}
if( !function_exists( 'NoNumber_License_getState' ) ) {
	function NoNumber_License_getState( $extension )
	{
		require_once JPATH_PLUGINS.DS.'system'.DS.'nonumberelements'.DS.'helpers'.DS.'licenses.php';
		$licenses = NNLicenses::instance();

		return $licenses->getState( $extension );
	}
}

if ( version_compare( JVERSION, '1.6.0', 'l' ) ) {
	// For Joomla 1.5
	class JElementLicense extends JElement
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var $_name = 'License';

		function fetchTooltip( $label, $description, &$node, $control_name, $name )
		{
			return;
		}

		function fetchElement( $name, $value, &$node, $control_name )
		{
			$this->_nnelement = new nnElementLicense();
			return $this->_nnelement->getInput( $control_name.'['.$name.']', $control_name.$name, $value, $node->attributes(), $node->children(), 1 );
		}
	}
} else {
	// For Joomla 1.6
	class JFormFieldLicense extends JFormField
	{
		/**
		 * The form field type
		 *
		 * @var		string
		 */
		public $type = 'License';

		protected function getLabel()
		{
			return;
		}

		protected function getInput()
		{
			$this->_nnelement = new nnElementLicense();
			return $this->_nnelement->getInput( $this->name, $this->id, $this->value, $this->element->attributes(), $this->element->children() );
		}
	}
}