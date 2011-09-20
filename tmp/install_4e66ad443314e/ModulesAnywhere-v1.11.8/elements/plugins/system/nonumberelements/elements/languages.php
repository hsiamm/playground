	<?php
/**
 * Element: Languages
 * Displays a select box of languages
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
 * Templates Element
 */
class nnElementLanguages
{
	var $_version = '2.9.1';

	function getInput( $name, $id, $value, $params, $children, $j15 = 0 )
	{
		$this->params = $params;

		$size = (int) $this->def( 'size' );
		$multiple = $this->def( 'multiple' );
		$client = $this->def( 'client', 'SITE' );

		jimport('joomla.language.helper');
		$options = JLanguageHelper::createLanguageList( $value, constant( 'JPATH_'.strtoupper( $client ) ), true );
		foreach ( $options as $i => $option ) {
			if ( $option['value'] ) {
				$options[$i]['text'] = $option['text'].' ['.$option['value'].']';
			}
		}

		require_once JPATH_PLUGINS.DS.'system'.DS.'nonumberelements'.DS.'helpers'.DS.'html.php';
		return nnHTML::selectlist( $options, $name, $value, $id, $size, $multiple, 0, $j15 );
	}

	private function def( $val, $default = '' )
	{
		return ( isset( $this->params[$val] ) && (string) $this->params[$val] != '' ) ? (string) $this->params[$val] : $default;
	}
}

if ( version_compare( JVERSION, '1.6.0', 'l' ) ) {
	// For Joomla 1.5
	class JElementLanguages extends JElement
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var $_name = 'Languages';

		function fetchElement( $name, $value, &$node, $control_name )
		{
			$this->_nnelement = new nnElementLanguages();
			return $this->_nnelement->getInput( $control_name.'['.$name.']', $control_name.$name, $value, $node->attributes(), $node->children(), 1 );
		}
	}
} else {
	// For Joomla 1.6
	class JFormFieldLanguages extends JFormField
	{
		/**
		 * The form field type
		 *
		 * @var		string
		 */
		public $type = 'Languages';

		protected function getInput()
		{
			$this->_nnelement = new nnElementLanguages();
			return $this->_nnelement->getInput( $this->name, $this->id, $this->value, $this->element->attributes(), $this->element->children() );
		}
	}
}