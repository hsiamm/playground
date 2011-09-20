<?php
/**
 * Element: CategoriesZOO
 * Displays a multiselectbox of available ZOO categories
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
 * CategoriesZOO Element
 */
class nnElementCategoriesZOO
{
	var $_version = '2.9.1';

	function getInput( $name, $id, $value, $params, $children, $j15 = 0 )
	{
		$this->params = $params;

		if ( !file_exists( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_zoo'.DS.'zoo.php' ) ) {
			return 'ZOO files not found...';
		}

		$db =& JFactory::getDBO();
		$sql = "SHOW tables like '".$db->getPrefix()."zoo_category'";
		$db->setQuery( $sql );
		$tables = $db->loadObjectList();

		if ( !count( $tables ) ) {
			return 'ZOO category table not found in database...';
		}

		$size = (int) $this->def( 'size' );
		$multiple = $this->def( 'multiple' );

		if ( !is_array( $value ) ) {
			$value = explode( ',', $value );
		}

		$sql = "SELECT id, name FROM #__zoo_application";
		$db->setQuery( $sql );
		$apps = $db->loadObjectList();

		$options = array();
		foreach ( $apps as $i => $app ) {
			$sql = "SELECT id, parent, name FROM #__zoo_category WHERE published = 1 AND application_id = ".(int) $app->id;
			$db->setQuery( $sql );
			$menuItems = $db->loadObjectList();

			if ( $i ) {
				$options[] = JHTML::_( 'select.option', '-', '&nbsp;', 'value', 'text', 1 );
			}

			// establish the hierarchy of the menu
			// TODO: use node model
			$children = array();

			if ( $menuItems) {
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
			$options[] = JHTML::_( 'select.option', 'app'.$app->id, '['.$app->name.']', 'value', 'text', 0 );
			foreach ( $list as $item ) {
				$item_name = '&nbsp;&nbsp;'.preg_replace( '#^((&nbsp;)*)- #', '\1', str_replace( '&#160;', '&nbsp;', $item->treename ) );
				$options[] = JHTML::_( 'select.option', $item->id, $item_name, 'value', 'text', 0 );
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
	class JElementCategoriesZOO extends JElement
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var $_name = 'CategoriesZOO';

		function fetchElement( $name, $value, &$node, $control_name )
		{
			$this->_nnelement = new nnElementCategoriesZOO();
			return $this->_nnelement->getInput( $control_name.'['.$name.']', $control_name.$name, $value, $node->attributes(), $node->children(), 1 );
		}
	}
} else {
	// For Joomla 1.6
	class JFormFieldCategoriesZOO extends JFormField
	{
		/**
		 * The form field type
		 *
		 * @var		string
		 */
		public $type = 'CategoriesZOO';

		protected function getInput()
		{
			$this->_nnelement = new nnElementCategoriesZOO();
			return $this->_nnelement->getInput( $this->name, $this->id, $this->value, $this->element->attributes(), $this->element->children() );
		}
	}
}