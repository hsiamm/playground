<?php
/**
 * Main File
 *
 * @package     Modules Anywhere
 * @version     1.11.8
 *
 * @author      Peter van Westen <peter@nonumber.nl>
 * @link        http://www.nonumber.nl
 * @copyright   Copyright © 2011 NoNumber! All Rights Reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die();

if (
		JRequest::getCmd( 'disable_modulesanywhere' )
	||	JRequest::getCmd( 'format' ) == 'raw'
	||	JRequest::getCmd( 'option' ) == 'com_joomfishplus'
) {
	return;
}

require_once dirname( __FILE__ ).DS.'modulesanywhere'.DS.'modulesanywhere.php';