<?php
/**
 * Element: Templates
 * Displays a select box of templates
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
class nnElementTemplates
{
	var $_version = '2.9.1';

	function getInput( $name, $id, $value, $params, $children, $j15 = 0 )
	{
		$this->params = $params;

		$size = (int) $this->def( 'size' );
		$multiple = $this->def( 'multiple' );
		$subtemplates = $this->def( 'subtemplates', 1 );
		$show_system = $this->def( 'show_system', 1 );

		if ( $j15 ) {
			require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_templates'.DS.'helpers'.DS.'template.php';
			$rows = TemplatesHelper::parseXMLTemplateFiles( JPATH_ROOT.DS.'templates' );
			$options = $this->createList15( $rows, JPATH_ROOT.DS.'templates', $subtemplates, $show_system );
		} else {
			require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_templates'.DS.'helpers'.DS.'templates.php';
			$rows = TemplatesHelper::getTemplateOptions( '0' );
			$options = $this->createList( $rows, JPATH_ROOT.DS.'templates', $subtemplates, $show_system );
		}

		require_once JPATH_PLUGINS.DS.'system'.DS.'nonumberelements'.DS.'helpers'.DS.'html.php';
		return nnHTML::selectlist( $options, $name, $value, $id, $size, $multiple, 0, $j15 );
	}

	function createList( $rows, $templateBaseDir, $subtemplates = 1, $show_system = 1 )
	{
		$options = array();

		if ( $show_system ) {
			$options[] = JHTML::_( 'select.option', 'system:component', JText::_( 'None' ).' (System - component)' );
		}

		foreach ( $rows as $option ) {
			$options[] = $option;

			if ( $subtemplates ) {
				$options_sub = $this->getSubTemplates( $option, $templateBaseDir );
				$options = array_merge( $options, $options_sub );
			}
		}
		return $options;
	}


	function createList15( $rows, $templateBaseDir, $subtemplates = 1, $show_system = 1 )
	{
		$options = array();

		if ( $show_system ) {
			$options[] = JHTML::_( 'select.option', 'system:component', JText::_( 'None' ).' (System - component)' );
		}
		foreach ( $rows as $row ) {
			$options[] = JHTML::_( 'select.option', $row->directory, $row->name );

			if ( $subtemplates ) {
				$option = new stdClass();
				$option->value = $row->directory;
				$option->text = $row->name;
				$options_sub = $this->getSubTemplates( $option, $templateBaseDir );
				$options = array_merge( $options, $options_sub );
			}
		}
		return $options;
	}

	function getSubTemplates( $option, $templateBaseDir )
	{
		$options = array();
		$templateDir = dir( $templateBaseDir.DS.$option->value );
		while ( false !== ( $file = $templateDir->read() ) ) {
		  	if ( is_file( $templateDir->path.DS.$file ) ) {
				if ( !( strpos( $file, '.php' ) === false ) && $file != 'index.php' ) {
					$file_name = str_replace( '.php', '', $file );
					if ( $file_name != 'index' && $file_name != 'editor' && $file_name != 'error' ) {
						$options[] = JHTML::_( 'select.option', $option->value.':'.$file_name, '&nbsp;&nbsp;'.$file_name );
					}
				}
			}
		}
		$templateDir->close();

		return $options;
	}

	private function def( $val, $default = '' )
	{
		return ( isset( $this->params[$val] ) && (string) $this->params[$val] != '' ) ? (string) $this->params[$val] : $default;
	}
}

if ( version_compare( JVERSION, '1.6.0', 'l' ) ) {
	// For Joomla 1.5
	class JElementTemplates extends JElement
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var $_name = 'Templates';

		function fetchElement( $name, $value, &$node, $control_name )
		{
			$this->_nnelement = new nnElementTemplates();
			return $this->_nnelement->getInput( $control_name.'['.$name.']', $control_name.$name, $value, $node->attributes(), $node->children(), 1 );
		}
	}
} else {
	// For Joomla 1.6
	class JFormFieldTemplates extends JFormField
	{
		/**
		 * The form field type
		 *
		 * @var		string
		 */
		public $type = 'Templates';

		protected function getInput()
		{
			$this->_nnelement = new nnElementTemplates();
			return $this->_nnelement->getInput( $this->name, $this->id, $this->value, $this->element->attributes(), $this->element->children() );
		}
	}
}