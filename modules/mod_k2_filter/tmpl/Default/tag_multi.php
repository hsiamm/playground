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

$search2 = JRequest::getVar('taga', null);
$search = array();

(is_array($search2) == false) ?
	$search[] = $search2 :
	$search = $search2 ;
?>

	<?php if($elems > 0) : ?>
	<script type="text/javascript">
	
		jQuery(document).ready(function () {
			jQuery("div.filter_tag_hidden").hide();
			jQuery("a.expand_filter_tag").click(function() {
				jQuery("div.filter_tag_hidden").slideToggle("fast");
				return false;
			});
		});
	
	</script>
	<?php endif; ?>
	
	<div class="k2filter-field-tag-multi k2filter-field-<?php echo $i; ?>">
		<h3>
			<?php echo JText::_('MOD_K2_FILTER_FIELD_TAG'); ?>
		</h3>
		<div>
		<?php
			$switch = 0;
			foreach ($tags as $which=>$tag) {
				if($elems > 0 && ($which+1) > $elems && $switch != 1) {
					echo "<div class='filter_tag_hidden'>";
					$switch = 1;
				}
				echo '<input name="taga[]" type="checkbox" value="'.$tag->tag.'" id="'.str_replace(" ", "_", $tag->tag).'_id"';
				foreach ($search as $searchword) {
					if ($searchword == $tag->tag) echo 'checked="checked"';
				}
				echo ' /><label for="'.str_replace(" ", "_", $tag->tag).'_id">'.$tag->tag.'</label><br />';
			}
			if($elems > 0) echo "</div>";
		?>
		</div>
		<?php if($elems > 0 && count($tags) > $elems) : ?>
		<p>
			<a href="#" class="button expand expand_filter_tag"><?php echo JText::_("MOD_K2_FILTER_MORE"); ?></a>
		</p>			
		<?php endif; ?>
	</div>

