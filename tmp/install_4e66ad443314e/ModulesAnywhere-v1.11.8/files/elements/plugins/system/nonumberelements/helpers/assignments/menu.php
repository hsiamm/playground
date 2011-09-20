<?php
/**
 * NoNumber! Elements Helper File: Assignments: Menu
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
* Assignments: Menu
*/
class NoNumberElementsAssignmentsMenu
{
	var $_version = '2.9.1';

	/**
	 * passMenuItems
	 * @param <object> $params
	 * inc_children
	 * inc_noItemid
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passMenuItem( &$main, &$params, $selection = array(), $assignment = 'all' )
	{
		$pass = 0;

		if ( $main->_params->Itemid ) {
			$selection = $main->makeArray( $selection );
			$pass = in_array( $main->_params->Itemid, $selection );
			if ( $pass && $params->inc_children == 2 ) {
				$pass = 0;
			} else if ( !$pass && $params->inc_children ) {
				$parentids = NoNumberElementsAssignmentsMenu::getParentIds( $main, $main->_params->Itemid );
				$parentids = array_diff( $parentids, array( '1' ) );
				foreach ( $parentids as $parent ) {
					if ( in_array( $parent, $selection ) ) {
						$pass = 1;
						break;
					}
				}
				unset( $parentids );
			}
		} else if ( $params->inc_noItemid ) {
			$pass = 1;
		}

		if ( $pass ) {
			return ( $assignment == 'include' );
		} else {
			return ( $assignment == 'exclude' );
		}

	}

	/**
	 * passHomePage
	 * @param <object> $params
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passHomePage( &$main, &$params, $selection = array(), $assignment = 'all' )
	{
		$pass = 0;

		$url = JFactory::getURI();
		$url = str_replace( '&amp;', '&', $url->toString() );
		// remove trailing nonsense
		$url = trim( preg_replace( '#/?\??&?$#', '', $url ) );
		// so also passes on urls with trailing /, ?, &, /?, etc...
		$root = preg_replace( '#(Itemid=[0-9]*).*^#', '\1', JURI::root() );
		// remove trailing /
		$root = trim( preg_replace( '#/$#', '', $root ) );


		if ( !$pass ) {
			/* Pass urls:
			 * [root]
			 * [root]/index.php
			 */
			$regex = '#^'.$root
				.'(/index.php)?'
				.'$#';
			$pass = preg_match( $regex, $url );
		}

		if ( !$pass ) {
			$menu =& JSite::getMenu();
			$menu_def = $menu->getDefault();
			/* Pass urls:
			 * [root]?Itemid=[menu-id]
			 * [root]/?Itemid=[menu-id]
			 * [root]/index.php?Itemid=[menu-id]
			 * [root]/index.php/?Itemid=[menu-id]
			 * [root]/[menu-alias]
			 * [root]/[menu-alias]?Itemid=[menu-id]
			 * [root]/index.php?[menu-alias]
			 * [root]/index.php?[menu-alias]?Itemid=[menu-id]
			 * [root]/index.php/[menu-alias]
			 * [root]/index.php/[menu-alias]?Itemid=[menu-id]
			 * [root]/[menu-link]
			 * [root]/[menu-link]&Itemid=[menu-id]
			 */
			$regex = '#^'.$root
				.'(/('
					.'index\.php'
					.'|'
					.'(index\.php[\?/])?'.preg_quote( $menu_def->alias, '#' )
					.'|'
					.preg_quote( $menu_def->link, '#' )
				.')?)?'
				.'(/?[\?&]Itemid='.(int) $menu_def->id.')?'
				.'$#';
			$pass = preg_match( $regex, $url );
		}

		if ( $pass ) {
			return ( $assignment == 'include' );
		} else {
			return ( $assignment == 'exclude' );
		}
	}

	function getParentIds( &$main, $id = 0 )
	{
		return $main->getParentIds( $id, 'menu' );
	}
}