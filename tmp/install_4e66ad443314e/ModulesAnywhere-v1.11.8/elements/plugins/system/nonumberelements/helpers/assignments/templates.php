<?php
/**
 * NoNumber! Elements Helper File: Assignments: Templates
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
* Assignments: Templates
*/
class NoNumberElementsAssignmentsTemplates
{
	var $_version = '2.9.1';

	/**
	 * passTemplates
	 * @param <object> $params
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passTemplates( &$main, &$params, $selection = array(), $assignment = 'all' )
	{
		$mainframe =& JFactory::getApplication();
		$template =& $mainframe->getTemplate();

		return $main->passSimple( $template, $selection, $assignment );
	}
}