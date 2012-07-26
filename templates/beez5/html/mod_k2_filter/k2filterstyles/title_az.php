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
	
<script>
	jQuery(document).ready(function() {
		var ftitle_az = jQuery("input[name=ftitle_az]").val();
		jQuery("a.title_az").each(function() {
			if(ftitle_az == jQuery(this).html()) {
				jQuery(this).css("font-weight", "bold").addClass("active");
			}
		});
	
		jQuery("a.title_az").click(function() {
			jQuery("a.title_az").css("font-weight", "normal");

			if(jQuery(this).hasClass("active") == 0) {
				jQuery(this).css("font-weight", "bold").addClass("active");
				jQuery("input[name=ftitle_az]").val(jQuery(this).html());
			}
			else {
				jQuery(this).css("font-weight", "normal").removeClass("active");
				jQuery("input[name=ftitle_az]").val("");
			}
			return false;
		});
	});
</script>

	<div class="k2filter-field-title-az k2filter-field-<?php echo $i; ?>">
	
		<?php if($showtitles) : ?>
		<h3>
			<?php echo JText::_('MOD_K2_FILTER_FIELD_TITLE_AZ'); ?>
		</h3>
		<?php endif; ?>
		
		<?php foreach(range('a', 'z') as $letter) : ?>
			<a class="title_az" href="#"><?php echo $letter; ?></a>
		<?php endforeach; ?>
		
		<input name="ftitle_az" type="hidden" <?php if (JRequest::getVar('ftitle_az')) echo ' value="'.JRequest::getVar('ftitle_az').'"'; ?> />
	</div>

