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

// Import Joomla! libraries
jimport( 'joomla.application.component.view');
class k2multicatViewDefault extends JView {
    function display($tpl = null) {
	JToolBarHelper::title(   JText::_( 'K2 multicategory' ), '' );
        parent::display($tpl);
    }
}
?>