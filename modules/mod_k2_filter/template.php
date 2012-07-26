<?php 

/*
// K2 Multiple Extra fields Filter and Search module by Andrey M
// molotow11@gmail.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>
<script type="text/javascript">
	if (typeof jQuery == 'undefined') {
		document.write('<scr'+'ipt type="text/javascript" src="<?php echo JURI::root(); ?>modules/mod_k2_filter/assets/js/jquery-1.7.1.min.js"></scr'+'ipt>');
		document.write('<scr'+'ipt>jQuery.noConflict();</scr'+'ipt>');
	}
</script>

<div id="K2FilterBox<?php echo $module->id; ?>" class="K2FilterBlock <?php echo $params->get('moduleclass_sfx'); ?>">
	<?php if($params->get('descr') != "") : ?>
	<p><?php echo $params->get('descr'); ?></p>
	<?php endif; ?>
	<form action="<?php echo JRoute::_('index.php?option=com_k2&view=itemlist&task=filter'); ?>" name="K2Filter" method="get">
  		<?php $app =& JFactory::getApplication(); if (!$app->getCfg('sef')): ?>
		<input type="hidden" name="option" value="com_k2" />
		<input type="hidden" name="view" value="itemlist" />
		<input type="hidden" name="task" value="filter" />
		<?php endif; ?>
		
	  <div class="k2filter-table">
		<div class="k2filter-row">
			<div class="k2filter-cell">
<?php

for($i=1; $i<($count+1); $i++) {

		for ($j = 0; $j<($count); $j++) {
			if(!is_array($field_id)) {
				$field_tmp = $field_id;
				$field_id = Array();
				$field_id[$j] = $field_tmp;
			}
		
			$k = $j;
			if(($field_type[$j] == 'text') && ($order[$k] == $i)) {
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'text'));
			}
			
			else if(($field_type[$j] == 'text_range') && ($order[$k] == $i)) {
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'text_range'));
			}			
			
			else if(($field_type[$j] == 'text_date') && ($order[$k] == $i)) {
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'text_date'));
			}			
			
			else if(($field_type[$j] == 'text_date_range') && ($order[$k] == $i)) {
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'text_date_range'));
			}
			
			else if(($field_type[$j] == 'select') && ($order[$k] == $i)) {
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'select'));
			}
	
			else if(($field_type[$j] == 'multi') && ($order[$k] == $i)) {
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'multi'));
			}
	
			else if(($field_type[$j] == 'slider') && ($order[$k] == $i)) {
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'slider'));
			}
			
			else if(($field_type[$j] == 'slider_range') && ($order[$k] == $i)) {
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'slider_range'));
			}
	
			else if(($field_type[$j] == 'radio') && ($order[$k] == $i)) {
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'radio'));
			}	
			
			else if(($field_type[$j] == 'label') && ($order[$k] == $i)) {
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'label'));
			}			
			
			else if(($field_type[$j] == 'tag_text') && ($order[$k] == $i)) {
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'tag_text'));
			}			
			
			else if(($field_type[$j] == 'tag_select') && ($order[$k] == $i)) {
				$restcata = 0;
				
				if($restmode == 1) {	
					$view = JRequest::getVar("view");
					$task = JRequest::getVar("task");
					
					if($view == "itemlist" && $task == "category") 
						$restcata = JRequest::getInt("id");
					else if($view == "item") {
						$id = JRequest::getInt("id");
						$restcata = modK2FilterHelper::getParent($id);
					}
					else {
						$restcata = JRequest::getVar("restcata");
					}
				}
				
				$tags = modK2FilterHelper::getTags($params, $restcata);
				
				if(count($tags)) {
					require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'tag_select'));
				}
			}
			
			else if(($field_type[$j] == 'tag_multi') && ($order[$k] == $i)) {
				$restcata = 0;
				
				if($restmode == 1) {	
					$view = JRequest::getVar("view");
					$task = JRequest::getVar("task");
					
					if($view == "itemlist" && $task == "category") 
						$restcata = JRequest::getInt("id");
					else if($view == "item") {
						$id = JRequest::getInt("id");
						$restcata = modK2FilterHelper::getParent($id);
					}
					else {
						$restcata = JRequest::getVar("restcata");
					}
				}
				
				$tags = modK2FilterHelper::getTags($params, $restcata);
				
				if(count($tags)) {
					require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'tag_multi'));
				}
			}
			
			else if(($field_type[$j] == 'title') && ($order[$k] == $i)) {				
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'title'));
			}
			
			else if(($field_type[$j] == 'title_az') && ($order[$k] == $i)) {				
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'title_az'));
			}			
			
			else if(($field_type[$j] == 'item_text') && ($order[$k] == $i)) {				
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'item_text'));
			}

			else if(($field_type[$j] == 'item_all') && ($order[$k] == $i)) {				
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'item_all'));
			}
			
			else if(($field_type[$j] == 'category_select') && ($order[$k] == $i)) {
				modK2FilterHelper::treeselectbox($params, 0, 0, $i);
			}			
			
			else if(($field_type[$j] == 'category_multiple') && ($order[$k] == $i)) {
				modK2FilterHelper::treeselectbox_multi($params, 0, 0, $i, $elems);
			}
			
			else if(($field_type[$j] == 'created') && ($order[$k] == $i)) {				
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'created'));
			}			
			
			else if(($field_type[$j] == 'created_range') && ($order[$k] == $i)) {				
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'created_range'));
			}
			
			else if(($field_type[$j] == 'publish_up') && ($order[$k] == $i)) {				
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'publish_up'));
			}			
			
			else if(($field_type[$j] == 'publish_up_range') && ($order[$k] == $i)) {				
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'publish_up_range'));
			}
			
			else if(($field_type[$j] == 'publish_down') && ($order[$k] == $i)) {				
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'publish_down'));
			}			
			
			else if(($field_type[$j] == 'publish_down_range') && ($order[$k] == $i)) {				
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'publish_down_range'));
			}			
			
			else if(($field_type[$j] == 'price_range') && ($order[$k] == $i)) {				
				require (JModuleHelper::getLayoutPath('mod_k2_filter', $getTemplate.DS.'price_range'));
			}
	
		}

		if(($i % $cols == 0)) {
			echo "</div></div>";
			if($i != $count) {
				echo "<div class='k2filter-row'><div class='k2filter-cell'>";
			}
		}
		else {
			echo "</div>";
			if($i != $count) {
				echo "<div class='k2filter-cell'>";
			}
			else {
				echo "</div>";
			}
		}
}
?>
		</div><!--/k2filter-table-->
		
	<?php if ($clear_btn):?>
	<script type="text/javascript">
		<!--
		function clearSearch() {
			jQuery("form[name=K2Filter] select").each(function () {
				jQuery(this).val(0);
			});
						
			jQuery("form[name=K2Filter] input.inputbox").each(function () {
				jQuery(this).val("");
			});		

			jQuery("form[name=K2Filter] input.slider_val").each(function () {
				jQuery(this).val("");
			});
					
			jQuery("form[name=K2Filter] input[type=checkbox]").each(function () {
				jQuery(this).removeAttr('checked');
			});						
			
			jQuery("form[name=K2Filter] input[type=radio]").each(function () {
				jQuery(this).removeAttr('checked');
			});	

			jQuery("form[name=K2Filter]").submit();
		}
		//-->
	</script>	

	<input type="button" value="<?php echo JText::_('MOD_K2_FILTER_BUTTON_CLEAR'); ?>" class="button <?php echo $moduleclass_sfx; ?>" onclick="clearSearch()" />
	<?php endif; ?>

	<?php if ($button):?>
	<input type="submit" value="<?php echo $button_text; ?>" class="button <?php echo $moduleclass_sfx; ?>" />
	<?php endif; ?>
	
	<?php if($resultf != "") : ?>
		<input type="hidden" name="resultf" value="<?php echo $resultf; ?>" />
	<?php endif; ?>	
	
	<?php if($noresult != "") : ?>
		<input type="hidden" name="noresult" value="<?php echo $noresult; ?>" />
	<?php endif; ?>
	
	<?php if($restrict == 1) : ?>
		<input type="hidden" name="restrict" value="1" />
		<input type="hidden" name="restmode" value="<?php echo $restmode; ?>" />
		<?php if($restmode == 0) : ?>
			<input type="hidden" name="restcat" value="<?php echo $restcat; ?>" />
		<?php endif; ?>
		<?php if($restmode == 1) : ?>			
			<?php 
				$restcata = "";
				$view = JRequest::getVar("view");
				if($view == "itemlist") 
					$restcata = JRequest::getInt("id");
				else if($view == "item") {
					$id = JRequest::getInt("id");
					$restcata = modK2FilterHelper::getParent($id);
				}
			
			?>
			<?php if($restcata != "") : ?>
				<input type="hidden" name="restcata" value="<?php echo $restcata; ?>" />
			<?php endif; ?>
			
			<?php $restauto = JRequest::getInt("restcata"); ?>
			<?php if($restauto != "" && $restcata == "") : ?>
				<input type="hidden" name="restcata" value="<?php echo $restauto; ?>" />
			<?php endif; ?>
		<?php endif; ?>
		<input type="hidden" name="restsub" value="<?php echo $restsub; ?>" />
	<?php endif; ?>

		<input type="hidden" name="ordering_default" value="<?php echo $ordering_default; ?>" />
		<input type="hidden" name="orderto" value="<?php echo JRequest::getVar("orderto"); if(JRequest::getVar("orderto") == '') echo $ordering_default_method; ?>">

	<?php if($ordering) : ?>		
		<input type="hidden" name="ordering" value="<?php echo $ordering; ?>" />
		<input type="hidden" name="orderby" value="<?php echo JRequest::getVar("orderby"); ?>">
		<?php if($ordering_extra) : ?>
			<input type="hidden" name="ordering_extra" value="1">
		<?php endif; ?>
	<?php endif; ?>	

	<input type="hidden" name="Itemid" value="<?php echo $itemid; ?>" />
	
	<?php if($page_heading != "") : ?>
	<input type="hidden" name="page_heading" value="<?php echo $page_heading; ?>" />
	<?php endif; ?>
	
	<?php if($template_selector == 1) : ?>
	<input type="hidden" name="template_selector" value="1" />
	<input type="hidden" name="template_id" value="<?php echo $filter_template; ?>" />
	<?php endif; ?>
	
  </form>
  
  <?php if($ajax_results == 1) : ?>
	<script type="text/javascript">

		jQuery(document).ready(function() {

			jQuery('form[name=K2Filter] input[type=submit]').click(function() {
				jQuery("div.K2FilterBlock div.results_container").html("<p><img src='media/k2/assets/images/system/loader.gif' /></p>");
				jQuery.ajax({
					data: jQuery("form[name=K2Filter]").serialize() + "&format=raw",
					type: jQuery("form[name=K2Filter]").attr('method'),
					url: jQuery("form[name=K2Filter]").attr('action'),
					success: function(response) {
						jQuery("div.K2FilterBlock div.results_container").html(response);
					}
				});
				return false;
			});
			
			jQuery('div.K2FilterBlock div.results_container div.k2Pagination a').live("click", function() {
				jQuery("div.K2FilterBlock div.results_container").html("<p><img src='media/k2/assets/images/system/loader.gif' /></p>");
				jQuery.ajax({
					type: "GET",
					url: jQuery(this).attr('href'),
					success: function(response) {
						jQuery("div.K2FilterBlock div.results_container").html(response);
					}
				});
				return false;
			});
			
		});
		
	</script>
  
    <div class="results_container"></div>
  <?php endif; ?>
  
</div><!-- k2-filter-box -->