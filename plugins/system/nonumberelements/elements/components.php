<?php
/**
 * Element: Components
 * Displays a list of components with check boxes
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
 * Components Element
 */
class nnElementComponents
{
	var $_version = '2.9.1';

	function getInput( $name, $id, $value, $params, $children, $j15 = 0 )
	{
		$this->params = $params;

		$frontend = $this->def( 'frontend', 1 );
		$admin = $this->def( 'admin', 1 );
		$show_content = $this->def( 'show_content', 0 );

		$components = $this->getComponents( $frontend, $admin, $show_content, $j15 );

		$html = array();
		$html[] = '<fieldset id="'.$id.'" class="checkboxes">';

		// place a dummy hidden checkbox item in the list, to be able to deselect all (and still have a default)
		$html[] = '<input type="hidden" id="'.$id.'x" name="'.$name.''.'[]" value="x" checked="checked" />';
		if ( !empty( $components ) ) {
			$lang = JFactory::getLanguage();
			foreach ( $components as $component ) {
				if ( !is_array( $value ) ) $value = explode( ',', $value );
				if ( !$j15 ) {
					if ( !empty( $component->name ) ) {
						// Load the core file then
						// Load extension-local file.
							$lang->load( $component->name.'.sys', JPATH_BASE, null, false, false )
						||	$lang->load( $component->name.'.sys', JPATH_ADMINISTRATOR.'/components/'.$component->name, null, false, false)
						||	$lang->load( $component->name.'.sys', JPATH_BASE, $lang->getDefault(), false, false )
						||	$lang->load( $component->name.'.sys', JPATH_ADMINISTRATOR.'/components/'.$component->name, $lang->getDefault(), false, false);
					}
					$component->name = JText::_( strtoupper( $component->name ) );
				}
				$checked = ( in_array( $component->option, $value ) ) ? ' checked="checked"' : '';
				$html[] = '<input type="checkbox" id="'.$id.$component->option.'" name="'.$name.''.'[]" value="'.$component->option.'"'.$checked.' style="clear:left;" />';
				$html[] = '<label for="'.$id.$component->option.'" class="checkboxes">'.$component->name.'</label>';
				if ( $j15 ) {
					$html[] = '<br />';
				}
			}
		} else {
			$html[] = JText::_( 'Component Not Found' );
		}
		$html[] = '</fieldset>';
		return implode( $html );
	}

	function getComponents( $frontend = 1, $admin = 1, $show_content = 0, $j15 = 0 )
	{
		$db =& JFactory::getDBO();

		if ( $j15 ) {
			$from = '#__components';
			$where = 'enabled = 1';
			$select_id = 'id';
			$select_option = $db->nameQuote( 'option' );
		} else {
			$from = '#__extensions';
			$where = 'type = '.$db->quote( 'component' ).' AND enabled = 1';
			$select_id = 'extension_id';
			$select_option = $db->nameQuote( 'element' );
		}

		if ( !$frontend && !$admin ) {
			$query = 'SELECT '.$select_option.' AS '.$db->nameQuote( 'option' ).', name'
				.' FROM '.$from
				.' WHERE '.$where;
			if ( $j15 ) {
				$query .= 'AND parent = 0';
			}
			if ( !$show_content ) {
				$query .= ' AND '.$select_option.' <> "com_content"';
			}
			$query .= ' ORDER BY name';
			$db->setQuery( $query );
			return $db->loadObjectList();
		} else {
			if ( $frontend ) {
				if ( $j15 ) {
					// component subs
					$query = 'SELECT parent'
						.' FROM '.$from
						.' WHERE '.$where
						.' AND parent != 0'
						.' AND link != ""'
						.' GROUP BY parent'
						;
					$db->setQuery( $query );
					$subcomponents = $db->loadResultArray();


					// main components
					$query = 'SELECT '.$select_id.' AS id'
						.' FROM '.$from
						.' WHERE '.$where
						.' AND parent = 0'
						.' AND ( link != ""';
					if ( !empty( $subcomponents ) ) {
						$query .= ' OR id IN ( '.implode( ',', $subcomponents ).' )';
					}
					$query .= ' )'
					.' ORDER BY ordering, name';
					$db->setQuery( $query );
					$component_ids = $db->loadResultArray();
				} else if ( !$admin ) {
					$query = 'SELECT '.$select_id.' AS id, name'
						.' FROM '.$from
						.' WHERE '.$where
						.' ORDER BY ordering, name';
					$db->setQuery( $query );
					$component_ids = $db->loadObjectList('id');
					foreach(  $component_ids as $id => $component ) {
						if ( !is_file( JPATH_SITE.'/components/'.$component->name.'/metadata.xml' ) ) {
							unset( $component_ids[$id] );
						}
					}
					$component_ids = array_keys( $component_ids );
				}
			}

			if ( $admin ) {
				if ( $j15 ) {
					// component subs
					$query = 'SELECT parent'
						.' FROM '.$from
						.' WHERE '.$where
						.' AND parent != 0'
						.' AND admin_menu_link != ""'
						;
					$db->setQuery( $query );
					$subcomponents = $db->loadResultArray();
					$subcomponents = array_unique( $subcomponents );

					// main components
					$query = 'SELECT '.$select_id.' AS id'
						.' FROM '.$from
						.' WHERE '.$where
						.' AND parent = 0'
						.' AND ( admin_menu_link != ""';
						if ( !empty( $subcomponents ) ) {
							$query .= ' OR id IN ( '.implode( ',', $subcomponents ).' )';
						}
						$query .= ' )';
				} else {
					$query = 'SELECT '.$select_id.' AS id'
						.' FROM '.$from
						.' WHERE '.$where;
				}
				$db->setQuery( $query );
				if ( $frontend && isset( $component_ids ) ) {
					$component_ids = array_merge( $component_ids, $db->loadResultArray() );
				} else {
					$component_ids = $db->loadResultArray();
				}
			}

			$component_ids = array_unique( $component_ids );
			$query = 'SELECT '.$select_option.' AS '.$db->nameQuote( 'option' ).', name'
				.' FROM '.$from
				.' WHERE '.$where;
				if ( $j15 ) {
					$query .= ' AND parent = 0';
				}
				if ( !empty( $component_ids ) ) {
					$query .= ' AND '.$select_id.' IN ( '.implode( ',', $component_ids ).' )';
				}
				if ( !$show_content ) {
					$query .= ' AND '.$select_option.' <> "com_content"';
				}
			$query .= ' ORDER BY name';
			$db->setQuery( $query );
			return $db->loadObjectList();
		}
	}

	private function def( $val, $default = '' )
	{
		return ( isset( $this->params[$val] ) && (string) $this->params[$val] != '' ) ? (string) $this->params[$val] : $default;
	}
}

if ( version_compare( JVERSION, '1.6.0', 'l' ) ) {
	// For Joomla 1.5
	class JElementComponents extends JElement
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var $_name = 'Components';

		function fetchElement( $name, $value, &$node, $control_name )
		{
			$this->_nnelement = new nnElementComponents();
			return $this->_nnelement->getInput( $control_name.'['.$name.']', $control_name.$name, $value, $node->attributes(), $node->children(), 1 );
		}
	}
} else {
	// For Joomla 1.6
	class JFormFieldComponents extends JFormField
	{
		/**
		 * The form field type
		 *
		 * @var		string
		 */
		public $type = 'Components';

		protected function getInput()
		{
			$this->_nnelement = new nnElementComponents();
			return $this->_nnelement->getInput( $this->name, $this->id, $this->value, $this->element->attributes(), $this->element->children() );
		}
	}
}