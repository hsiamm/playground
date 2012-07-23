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

jimport( 'joomla.application.component.controller' );


/**
 * Multi category Controller
 *
 * @package Joomla
 * @subpackage Multi category
 */
class k2multicatController extends JController {
    /**
     * Constructor
     * @access private
     * @subpackage Multi category
     */
    function __construct() {
        //Get View
        if(JRequest::getCmd('view') == '') {
            JRequest::setVar('view', 'default');
        }
        $this->item_type = 'Default';
        parent::__construct();
    }
    
}
?>