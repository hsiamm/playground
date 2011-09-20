<?php
/**
 * NoNumber! Elements Helper File: Assignments: URL
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
* Assignments: URL
*/
class NoNumberElementsAssignmentsURL
{
	var $_version = '2.9.1';

	/**
	 * passURL
	 * @param <object> $params
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passURL( &$main, &$params, $selection = array(), $assignment = 'all' )
	{
		$url = JFactory::getURI();
		$url = $url->_uri;

		if ( !is_array( $selection ) ) {
			$selection = explode( "\n", $selection );
		}

		$pass = 0;
		foreach ( $selection as $url_part ) {
			if ( $url_part !== '' ) {
				$url_part = str_replace( '&amp;', '(&amp;|&)', $url_part );
				$s = '#'.$url_part.'#si';
				if (	@preg_match( $s.'u', $url )
					||	@preg_match( $s.'u', html_entity_decode( $url, ENT_COMPAT, 'UTF-8' ) )
					||	@preg_match( $s, $url )
					||	@preg_match( $s, html_entity_decode( $url, ENT_COMPAT, 'UTF-8' ) )
				) {
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