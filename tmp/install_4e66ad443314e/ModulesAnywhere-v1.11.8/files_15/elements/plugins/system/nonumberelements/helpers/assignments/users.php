<?php
/**
 * NoNumber! Elements Helper File: Assignments: Users
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
* Assignments: Users
*/
class NoNumberElementsAssignmentsUsers
{
	var $_version = '2.9.1';

	/**
	 * passUserGroupLevels
	 * @param <object> $params
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passUserGroupLevels( &$main, &$params, $selection = array(), $assignment = 'all' )
	{
		$user =& JFactory::getUser();

		$selection = $main->makeArray( $selection );

		if ( in_array( 29, $selection ) ) {
			$selection[] = 18;
			$selection[] = 19;
			$selection[] = 20;
			$selection[] = 21;
		}
		if ( in_array( 30, $selection ) ) {
			$selection[] = 23;
			$selection[] = 24;
			$selection[] = 25;
		}

		return $main->passSimple( $user->get( 'gid' ), $selection, $assignment );
	}

	/**
	 * passUsers
	 * @param <object> $params
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passUsers( &$main, &$params, $selection = array(), $assignment = 'all' )
	{
		$user =& JFactory::getUser();

		return $main->passSimple( $user->get( 'id' ), $selection, $assignment );
	}
}