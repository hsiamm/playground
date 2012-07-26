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

$search2 = JRequest::getVar('array'.$field_id[$j], null);
$search = array();

(is_array($search2) == false) ?
	$search[] = $search2 :
	$search = $search2 ;
?>

	<?php if($elems > 0) : ?>
	<script type="text/javascript">
	
		jQuery(document).ready(function () {
			jQuery("div.filter<?php echo $field_id[$j]; ?>_hidden").hide();
			jQuery("a.expand_filter<?php echo $field_id[$j]; ?>").click(function() {
				jQuery("div.filter<?php echo $field_id[$j]; ?>_hidden").slideToggle("fast");
				return false;
			});
		});
	
	</script>
	<?php endif; ?>

	<div class="k2filter-field-multi k2filter-field-<?php echo $i; ?>">
		<h3>
			<?php echo $extra_fields_name[$j]; ?>
		</h3>
		<div>
			<?php
				$switch = 0;
				foreach ($extra_fields_content[$j] as $which=>$field) {
					if($elems > 0 && ($which+1) > $elems && $switch != 1) {
						echo "<div class='filter".$field_id[$j]."_hidden'>";
						$switch = 1;
					}
					echo '<input name="array'.$field_id[$j].'[]" type="checkbox" value="'.$field.'" id="'.$field.'"';
					foreach ($search as $searchword) {
						if ($searchword == $field) echo 'checked="checked"';
					}
					echo ' /><label for="'.$field.'">'.$field.'</label><br />';
				}
				if($elems > 0) echo "</div>";
			?>
		</div>
		<?php if($elems > 0 && count($extra_fields_content[$j]) > $elems) : ?>
		<p>
			<a href="#" class="button expand expand_filter<?php echo $field_id[$j]; ?>"><?php echo JText::_("MOD_K2_FILTER_MORE"); ?></a>
		</p>			
		<?php endif; ?>
	</div>

