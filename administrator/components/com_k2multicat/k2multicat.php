<?php
/*------------------------------------------------------------------------
 # com_k2multicat - Assign k2 item to multiple categories
 # ------------------------------------------------------------------------
 # author    US Joomla Pros
 # copyright Copyright (C) 2010 USJoomlaPros.com. All Rights Reserved.
 # @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 # Websites: http://www.USJoomlaPros.com
 # Technical Support:  Forum - http://www.USJoomlaPros.com/forum.html
 -------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/*
 * Define constants for all pages
 */
 
require_once JPATH_COMPONENT.DS.'controller.php';

// Require the base controller


// Initialize the controller
$controller = new k2multicatController( );

// Perform the Request task
$controller->execute( JRequest::getCmd('task'));
$controller->redirect();
?>