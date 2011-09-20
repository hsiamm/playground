<?php
/**
 * NoNumber! Elements Helper File: Assignments: PHP
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
* Assignments: PHP
*/
class NoNumberElementsAssignmentsPHP
{
	var $_version = '2.9.1';

	/**
	 * passPHP
	 * @param <object> $params
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passPHP( &$main, &$params, $selection = array(), $assignment = 'all' )
	{
		if ( !is_array( $selection ) ) {
			$selection = array( $selection );
		}

		$pass = 0;
		foreach ( $selection as $php ) {
			// replace \n with newline and other fix stuff
			$php = str_replace( '\|', '|', $php );
			$php = preg_replace( '#(?<!\\\)\\\n#', "\n", $php );
			$php = str_replace( '[:REGEX_ENTER:]', '\n', $php );

			if ( trim( $php ) == '' ) {
				$pass = 1;
				break;
			}

			$val = '$temp_PHP_Val = create_function( \'\', $php.\';\' );';
			$val .= ' $pass = ( $temp_PHP_Val() ) ? 1 : 0; unset( $temp_PHP_Val );';
			@eval( $val );

			if ( $pass ) {
				break;
			}
		}

		if ( $pass ) {
			return ( $assignment == 'include' );
		} else {
			return ( $assignment == 'exclude' );
		}
	}
}