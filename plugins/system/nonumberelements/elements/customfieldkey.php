<?php
/**
 * Element: Custom Field Key
 * Displays a custom key field ( use in combination with customfieldvalue)
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
 * Radio List Element
 */
class nnElementCustomFieldKey
{
	var $_version = '2.9.1';

	function getInput( $name, $id, $value, $params, $children, $j15 = 0 )
	{
		$this->params = $params;

		$size = ( $this->def( 'size' ) ? 'size="'.$this->def( 'size' ).'"' : '' );
		$class = ( $this->def( 'class' ) ? 'class="'.$this->def( 'class' ).'"' : 'class="text_area"' );
		$value = htmlspecialchars( html_entity_decode( $value, ENT_QUOTES ), ENT_QUOTES );

		JHTML::_( 'behavior.mootools' );
		$document =& JFactory::getDocument();
		$document->addScript( JURI::root(true).'/plugins/system/nonumberelements/js/script.js?v='.$this->_version );

		$val_id = str_replace( '_key', '_value', $id );
		$script = "
			window.addEvent( 'domready', function() {
				if ( $( 'span_".$val_id."' ) ) {
					$( 'span_".$id."' ).injectInside( $( 'span_".$val_id."' ) );
				}
			});
		";
		$document->addScriptDeclaration( $script );

		$html = '<input type="text" name="'.$name.'" id="'.$id.'" value="'.$value.'" '.$class.' '.$size.' />';
		$html .= '<span id="span_'.$id.'">'.$html.'</span>';
		$random = rand( 100000, 999999 );
		$html .= '<div id="end-'.$random.'"></div><script type="text/javascript">NoNumberElementsHideTD( "end-'.$random.'" );</script>';
		return $html;
	}

	private function def( $val, $default = '' )
	{
		return ( isset( $this->params[$val] ) && (string) $this->params[$val] != '' ) ? (string) $this->params[$val] : $default;
	}
}

if ( version_compare( JVERSION, '1.6.0', 'l' ) ) {
	// For Joomla 1.5
	class JElementCustomFieldKey extends JElement
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var $_name = 'CustomFieldKey';

		function fetchTooltip( $label, $description, &$node, $control_name, $name )
		{
			return;
		}

		function fetchElement( $name, $value, &$node, $control_name )
		{
			$this->_nnelement = new nnElementCustomFieldKey();
			return $this->_nnelement->getInput( $control_name.'['.$name.']', $control_name.$name, $value, $node->attributes(), $node->children(), 1 );
		}
	}
} else {
	// For Joomla 1.6
	class JFormFieldCustomFieldKey extends JFormField
	{
		/**
		 * The form field type
		 *
		 * @var		string
		 */
		public $type = 'CustomFieldKey';

		protected function getLabel()
		{
			return;
		}

		protected function getInput()
		{
			$this->_nnelement = new nnElementCustomFieldKey();
			return $this->_nnelement->getInput( $this->name, $this->id, $this->value, $this->element->attributes(), $this->element->children() );
		}
	}
}