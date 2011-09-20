<?php
/**
 * NoNumber! Elements Helper File: Assignments: Browsers
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
* Assignments: Browsers
*/
class NoNumberElementsAssignmentsBrowsers
{
	var $_version = '2.9.1';

	/**
	 * passBrowsers
	 * @param <object> $params
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passBrowsers( &$main, &$params, $selection = array(), $assignment = 'all' )
	{
		$pass = 0;

		$selection = $main->makeArray( $selection );

		if ( !empty( $selection ) ) {
			jimport( 'joomla.environment.browser' );
			$browser =& JBrowser::getInstance();
			$b = $browser->_agent;
			if ( !( strpos( $browser->_agent, 'Chrome' ) === false ) ) {
				$b = preg_replace( '#(Chrome/.*)Safari/[0-9\.]*#s', '\1', $b );
			} else if ( !( strpos( $browser->_agent, 'Opera' ) === false ) ) {
				$b = preg_replace( '#(Opera/.*)Version/#s', '\1Opera/', $b );
			}
			foreach ( $selection as $sel ) {
				if ( $sel && !( strpos( $b, $sel ) === false ) ) {
					$pass = 1;
					break;
				}
			}
		}

		if ( $pass ) {
			return ( $assignment == 'include' );
		} else {
			return ( $assignment == 'exclude' );
		}

	}
}