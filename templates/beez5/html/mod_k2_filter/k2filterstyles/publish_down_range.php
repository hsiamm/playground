<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<script type="text/javascript" src="<?php echo JURI::root(); ?>modules/mod_k2_filter/assets/js/jquery-ui-1.8.16.custom.min.js"></script>
<link type="text/css" href="<?php echo JURI::root(); ?>modules/mod_k2_filter/assets/js/jquery-ui-1.8.16.custom.css" rel="stylesheet" />

<script type="text/javascript">
	jQuery(document).ready(function () {
		jQuery("input.datepicker").datepicker({ dateFormat: 'yy-mm-dd' });
	});
</script>
	
	<div class="k2filter-field-publishing k2filter-field-<?php echo $i; ?>">
		<h3>
			<?php echo JText::_('MOD_K2_FILTER_FIELD_PUBLISHING_END'); ?>
		</h3>
		
		<input style="width: 40%;" class="datepicker inputbox" name="publish-down-from" type="text" <?php if (JRequest::getVar('publish-down-from')) echo ' value="'.JRequest::getVar('publish-down-from').'"'; ?> /> - 
		<input style="width: 40%;" class="datepicker inputbox" name="publish-down-to" type="text" <?php if (JRequest::getVar('publish-down-to')) echo ' value="'.JRequest::getVar('publish-down-to').'"'; ?> />
	</div>

