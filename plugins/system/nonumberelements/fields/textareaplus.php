<?php
/**
 * Element: Text Area Plus
 * Displays a text area with extra options
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
 * Text Area Plus Element
 */
class nnElementTextAreaPlus
{
	var $_version = '2.9.1';

	function getLabel( $name, $id, $label, $description, $params, $j15 = 0 )
	{
		$this->params = $params;

		$example = $this->def( 'example' );

		$html = '<label id="'.$id.'-lbl" for="'.$id.'"';
		if ( $description ) {
			$html .= ' class="hasTip" title="'.JText::_( $label ).'::'.JText::_( $description ).'">';
		} else {
			$html .= '>';
		}
		$html .= JText::_( $label ).'</label>';

		if( $example ) {
			$el = 'document.getElementById( \''.$id.'\' )';
			$onclick = $el.'.value = \''.str_replace( "'", "\'", $example ).'\n\'+'.$el.'.value;'
				.'this.blur();return false;';
			$html .= '<br clear="all" />';
			$html .= '<div class="button2-left" style="float:right;margin-top:5px;"><div class="blank"><a href="javascript://;" onclick="'.$onclick.'">'.JText::_( 'Example' ).'</a></div></div>'."\n";
		}

		return $html;
	}

	function getInput( $name, $id, $value, $params, $children, $j15 = 0 )
	{
		$this->params = $params;

		$resize = $this->def( 'resize', 1 );
		$width = $this->def( 'width', 400 );
		$minwidth = $this->def( 'minwidth', 200 );
		$minwidth = min( $width, $minwidth );
		$maxwidth = $this->def( 'maxwidth', 1200 );
		$maxwidth = max( $width, $maxwidth );
		$height = $this->def( 'height', 80 );
		$minheight = $this->def( 'minheight', 40 );
		$minheight = min( $height, $minheight );
		$maxheight = $this->def( 'maxheight', 600 );
		$maxheight = max( $height, $maxheight );
		$class = $this->def( 'class', 'text_area' );
		$class = 'class="'.$class.'"';
		$type = $this->def( 'texttype' );

		if( $resize ) {
			$document =& JFactory::getDocument();
			$document->addScript( JURI::root(true).'/plugins/system/nonumberelements/fields/textareaplus/textareaplus.js?v='.$this->_version );
			$document->addStyleSheet( JURI::root(true).'/plugins/system/nonumberelements/fields/textareaplus/textareaplus.css?v='.$this->_version );
			// not for Safari (and other webkit browsers) because it has its own resize option
			$script = 'window.addEvent( \'domready\', function() {'
				.' if ( !window.webkit ) {'
					.' new TextAreaResizer( \''.$id.'\', { \'min_x\':'.$minwidth.', \'max_x\':'.$maxwidth.', \'min_y\':'.$minheight.', \'max_y\':'.$maxheight.' } );'
				." }"
			." });";
			$document->addScriptDeclaration( $script );
		}

		if ( $type == 'html' ) {
			// Convert <br /> tags so they are not visible when editing
			$value = str_replace( '<br />', "\n", $value );
		} else if ( $type == 'regex' ) {
			// Protects the special characters
			$value = str_replace( '[:REGEX_ENTER:]', '\n', $value );
		}

		return '<textarea name="'.$name.'" cols="'.( round( $width / 7.5 ) ).'" rows="'.( round( $height / 15 ) ).'" style="width:'.$width.'px;height:'.$height.'px" '.$class.' id="'.$id.'" >'.$value.'</textarea>';
	}

	private function def( $val, $default = '' )
	{
		return ( isset( $this->params[$val] ) && (string) $this->params[$val] != '' ) ? (string) $this->params[$val] : $default;
	}
}


if ( version_compare( JVERSION, '1.6.0', 'l' ) ) {
	// For Joomla 1.5
	class JElementNN_TextAreaPlus extends JElement
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var $_name = 'TextAreaPlus';

		function fetchTooltip( $label, $description, &$node, $control_name, $name )
		{
			$this->_nnelement = new nnElementTextAreaPlus();
			return $this->_nnelement->getLabel( $control_name.'['.$name.']', $control_name.$name, $label, $description, $node->attributes(), 1 );
		}

		function fetchElement( $name, $value, &$node, $control_name )
		{
			return $this->_nnelement->getInput( $control_name.'['.$name.']', $control_name.$name, $value, $node->attributes(), $node->children(), 1 );
		}
	}
} else {
	// For Joomla 1.6
	class JFormFieldNN_TextAreaPlus extends JFormField
	{
		/**
		 * The form field type
		 *
		 * @var		string
		 */
		public $type = 'TextAreaPlus';

		protected function getLabel()
		{
			$this->_nnelement = new nnElementTextAreaPlus();
			return $this->_nnelement->getLabel( $this->name, $this->id, (string) $this->getTitle(), $this->description, $this->element->attributes() );
		}

		protected function getInput()
		{
			return $this->_nnelement->getInput( $this->name, $this->id, $this->value, $this->element->attributes(), $this->element->children() );
		}
	}
}