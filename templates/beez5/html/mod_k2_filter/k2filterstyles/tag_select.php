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

	<div class="k2filter-field-tag-select k2filter-field-<?php echo $i; ?>">
	
		<?php if($showtitles) : ?>
		<h3>
			<?php echo JText::_('MOD_K2_FILTER_FIELD_TAG'); ?>
		</h3>
		<?php endif; ?>
		
		<select name="ftag" <?php if($onchange) : ?>onchange="document.K2Filter.submit()"<?php endif; ?>>
			<option value=""><?php echo JText::_('MOD_K2_FILTER_FIELD_TAG_DEFAULT'); ?></option>
			<?php
				foreach ($tags as $tag) {
					echo '<option ';
					if (JRequest::getVar('ftag') == $tag->tag) { echo 'selected="selected"'; }
					echo '>'.$tag->tag.'</option>';
				}
			?>
		</select>
    </div>