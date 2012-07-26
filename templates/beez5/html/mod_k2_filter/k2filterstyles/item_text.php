<?php
/*
// "K2 Tools" Module by JoomlaWorks for Joomla! 1.5.x - Version 2.1
// Copyright (c) 2006 - 2009 JoomlaWorks Ltd. All rights reserved.
// Released under the GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
// More info at http://www.joomlaworks.gr and http://k2.joomlaworks.gr
// Designed and developed by the JoomlaWorks team
// *** Last update: September 9th, 2009 ***
*/

/*
// mod for K2 Extra fields Filter and Search module by Piotr Konieczny
// piotr@smartwebstudio.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>
	<div class="k2filter-field-text k2filter-field-<?php echo $i; ?>">
	
		<h3>
			<?php echo JText::_('MOD_K2_FILTER_FIELD_ITEM_TEXT'); ?>
		</h3>
		
		<input class="inputbox" name="ftext" type="text" <?php if (JRequest::getVar('ftext')) echo ' value="'.JRequest::getVar('ftext').'"'; ?> />
	</div>

