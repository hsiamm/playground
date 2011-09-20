<?php
/**
 * Element: JSSection
 * Displays a multiselectbox of available JoomSuite Resources categories
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
 * JSSection Element
 */
class nnElementJSSection
{
	var $_version = '2.9.1';

	function getInput( $name, $id, $value, $params, $children, $j15 = 0 )
	{
		$this->params = $params;

		if ( !file_exists( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_resource'.DS.'models'.DS.'record.php' ) ) {
			return 'JoomSuite Resources files not found...';
		}

		$db =& JFactory::getDBO();
		$sql = "SHOW tables like '".$db->getPrefix()."js_res_category'";
		$db->setQuery( $sql );
		$tables = $db->loadObjectList();

		if ( !count( $tables ) ) {
			return 'JoomSuite Resources category table not found in database...';
		}

		$size = (int) $this->def( 'size' );
		$multiple = $this->def( 'multiple' );
		$get_categories = $this->def( 'getcategories', 1 );

		if ( !is_array( $value ) ) {
			$value = explode( ',', $value );
		}

		$where = 'published = 1';
		if ( !$get_categories ) {
			$where .= ' AND parent = 0';
		}

		$sql = "SELECT id, parent, name FROM #__js_res_category WHERE ".$where;
		$db->setQuery( $sql );
		$menuItems = $db->loadObjectList();

		// establish the hierarchy of the menu
		// TODO: use node model
		$children = array();

		if ( $menuItems ) {
			// first pass - collect children
			foreach ( $menuItems as $v ) {
				$pt = $v->parent;
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}

		// second pass - get an indent list of the items
		require_once JPATH_LIBRARIES.DS.'joomla'.DS.'html'.DS.'html'.DS.'menu.php';
		$list = JHTMLMenu::treerecurse( 0, '', array(), $children, 9999, 0, 0 );

		// assemble items to the array
		$options = array();
		foreach ( $list as $item ) {
			$options[] = JHTML::_( 'select.option', $item->id, $item->treename, 'value', 'text', 0 );
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
	class JElementJSSection extends JElement
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var $_name = 'JSSection';

		function fetchElement( $name, $value, &$node, $control_name )
		{
			$this->_nnelement = new nnElementJSSection();
			return $this->_nnelement->getInput( $control_name.'['.$name.']', $control_name.$name, $value, $node->attributes(), $node->children(), 1 );
		}
	}
} else {
	// For Joomla 1.6
	class JFormFieldJSSection extends JFormField
	{
		/**
		 * The form field type
		 *
		 * @var		string
		 */
		public $type = 'JSSection';

		protected function getInput()
		{
			$this->_nnelement = new nnElementJSSection();
			return $this->_nnelement->getInput( $this->name, $this->id, $this->value, $this->element->attributes(), $this->element->children() );
		}
	}
}