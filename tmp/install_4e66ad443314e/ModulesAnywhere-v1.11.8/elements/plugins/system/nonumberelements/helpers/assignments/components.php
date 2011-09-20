<?php
/**
 * NoNumber! Elements Helper File: Assignments: Components
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
* Assignments: Components
*/
class NoNumberElementsAssignmentsComponents
{
	var $_version = '2.9.1';

	/**
	 * passComponents
	 * @param <object> $params
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passComponents( &$main, &$params, $selection = array(), $assignment = 'all' )
	{
		return $main->passSimple( $main->_params->option, $selection, $assignment );
	}
}