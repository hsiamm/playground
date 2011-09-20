<?php
/**
 * Element: Custom Field Value
 * Displays a custom key field ( use in combination with customfieldkey)
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
 * Custom Field Value Element
 */
class nnElementCustomFieldValue
{
	function getLabel( $name, $id, $label, $description, $params, $j15 = 0 )
	{
		$this->params = $params;

		$html = '<span id="span_'.$id.'"></span>';
		return $html;
	}

	function getInput( $name, $id, $value, $params, $children, $j15 = 0 )
	{
		$this->params = $params;

		$size = ( $this->def( 'size' ) ? 'size="'.$this->def( 'size' ).'"' : '' );
		$class = ( $this->def( 'class' ) ? 'class="'.$this->def( 'class' ).'"' : 'class="text_area"' );
		$value = htmlspecialchars( html_entity_decode( $value, ENT_QUOTES ), ENT_QUOTES );

		return '<input type="text" name="'.$name.'" id="'.$id.'" value="'.$value.'" '.$class.' '.$size.' />';
	}

	private function def( $val, $default = '' )
	{
		return ( isset( $this->params[$val] ) && (string) $this->params[$val] != '' ) ? (string) $this->params[$val] : $default;
	}
}

if ( version_compare( JVERSION, '1.6.0', 'l' ) ) {
	// For Joomla 1.5
	class JElementCustomFieldValue extends JElement
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var $_name = 'CustomFieldValue';

		function fetchTooltip( $label, $description, &$node, $control_name, $name )
		{
			$this->_nnelement = new nnElementCustomFieldValue();
			return $this->_nnelement->getLabel( $control_name.'['.$name.']', $control_name.$name, $label, $description, $node->attributes(), 1 );
		}

		function fetchElement( $name, $value, &$node, $control_name )
		{
			return $this->_nnelement->getInput( $control_name.'['.$name.']', $control_name.$name, $value, $node->attributes(), $node->children(), 1 );
		}
	}
} else {
	// For Joomla 1.6
	class JFormFieldCustomFieldValue extends JFormField
	{
		/**
		 * The form field type
		 *
		 * @var		string
		 */
		public $type = 'CustomFieldValue';

		protected function getLabel()
		{
			$this->_nnelement = new nnElementCustomFieldValue();
			return $this->_nnelement->getLabel( $this->name, $this->id, (string) $this->getTitle(), $this->description, $this->element->attributes() );
		}

		protected function getInput()
		{
			return $this->_nnelement->getInput( $this->name, $this->id, $this->value, $this->element->attributes(), $this->element->children() );
		}
	}
}