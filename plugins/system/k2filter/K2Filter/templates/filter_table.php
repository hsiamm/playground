<?php
/**
 * @version		$Id: generic.php 1492 2012-02-22 17:40:09Z joomlaworks@gmail.com $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$version = new JVersion;
$joomlaVersion = $version->RELEASE;

$doc =& JFactory::getDocument();

if($joomlaVersion < 1.6) {
	$doc->addStyleSheet( 'administrator/templates/khepri/css/general.css' );
	if(JRequest::getVar("format") == "raw") {
		echo '<link rel="stylesheet" href="administrator/templates/khepri/css/general.css" type="text/css" />';
	}
}
else {
	$doc->addStyleSheet( 'administrator/templates/bluestork/css/template.css' );
	if(JRequest::getVar("format") == "raw") {
		echo '<link rel="stylesheet" href="administrator/templates/bluestork/css/template.css" type="text/css" />';
	}
}

$doc->addStyleSheet( 'media/k2/assets/css/k2.css' );
if(JRequest::getVar("format") == "raw") {
	echo '<link rel="stylesheet" href="media/k2/assets/css/k2.css" type="text/css" />';
}

?>

<script>
	
	jQuery(document).ready(function() {

		jQuery("div.genericItemList th a").click(function() {
			var value = jQuery(this).attr("rel");
			document.K2Filter.orderby.value = value;

			if(jQuery(this).hasClass("desc")) {
				document.K2Filter.orderto.value='asc'
			}
			else {
				document.K2Filter.orderto.value='desc'
			}

			document.K2Filter.submit();
			
			return false;
		});
	
	});

</script>

<!-- Start K2 Generic (search/date) Layout -->
<div id="k2Container" class="genericView<?php if($this->params->get('pageclass_sfx')) echo ' '.$this->params->get('pageclass_sfx'); ?>">

	<?php if($this->params->get('show_page_title')): ?>
	<!-- Page title -->
	<div class="componentheading<?php echo $this->params->get('pageclass_sfx')?>">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</div>
	<?php endif; ?>

	<?php if($this->params->get('genericFeedIcon',1)): ?>
	<!-- RSS feed icon -->
	<div class="k2FeedIcon">
		<a href="<?php echo $this->feed; ?>" title="<?php echo JText::_('K2_SUBSCRIBE_TO_THIS_RSS_FEED'); ?>">
			<span><?php echo JText::_('K2_SUBSCRIBE_TO_THIS_RSS_FEED'); ?></span>
		</a>
		<div class="clr"></div>
	</div>
	<?php endif; ?>
	
	<!--added K2FSM -->
	<?php if($this->resultf != "") : ?>
		<p class="resultf" style="float: left;">
			<?php echo JText::_($this->resultf); ?>
			<?php if($this->result_count != 0) : ?>
			<?php echo "(".$this->result_count.")" ?>
			<?php endif; ?>
		</p>
	<?php endif; ?>
	
	<?php if(JRequest::getVar("template_selector") == 1) : ?>
	<p class="template_selector" style="float: right;">
		<a href="javascript: document.K2Filter.template_id.value='0'; document.K2Filter.submit()"><img src="modules/mod_k2_filter/assets/generic.png" /></a>
		<a href="javascript: document.K2Filter.template_id.value='1'; document.K2Filter.submit()"><img src="modules/mod_k2_filter/assets/generic_table.png" /></a>
	</p>
	<div style="clear: both;"></div>
	<?php endif; ?>
	
	<!--///added K2FSM -->

	<?php if(count($this->items)): ?>
	<div class="genericItemList K2AdminViewItems">
		
		<table cellpadding="0" cellspacing="0" border="0" class="adminlist">
		
		<thead>
			<tr>
				<?php if($this->params->get('genericItemDateCreated')) : ?>
				<th>
					<a href="#" rel="date" title="<?php echo JText::_("CLICK TO SORT THIS COLUMN"); ?>"<?php if(JRequest::getVar("orderby") == "date" && JRequest::getVar("orderto") == "desc") echo " class='desc'"; ?>>
						<?php echo JText::_("K2_DATE"); ?>
						
						<?php if(JRequest::getVar("orderby") == "date") : ?>
							<?php if(JRequest::getVar("orderto") == "desc") : ?>
								<img src="modules/mod_k2_filter/assets/sort_desc.png" border="0" width="12" height="12" />
							<?php else : ?>
								<img src="modules/mod_k2_filter/assets/sort_asc.png" border="0" width="12" height="12" />
							<?php endif; ?>
						<?php endif; ?>
						
					</a>
				</th>
				<?php endif; ?>
				
				<?php if($this->params->get('genericItemCategory')) : ?>
				<th>
					<a href="#" rel="cat" title="<?php echo JText::_("CLICK TO SORT THIS COLUMN"); ?>"<?php if(JRequest::getVar("orderby") == "cat" && JRequest::getVar("orderto") == "desc") echo " class='desc'"; ?>>
						<?php echo JText::_("K2_PUBLISHED_IN"); ?>
						
						<?php if(JRequest::getVar("orderby") == "cat") : ?>
							<?php if(JRequest::getVar("orderto") == "desc") : ?>
								<img src="modules/mod_k2_filter/assets/sort_desc.png" border="0" width="12" height="12" />
							<?php else : ?>
								<img src="modules/mod_k2_filter/assets/sort_asc.png" border="0" width="12" height="12" />
							<?php endif; ?>
						<?php endif; ?>
					</a>
				</th>
				<?php endif; ?>
				
				<?php if($this->params->get('genericItemTitle')) : ?>
				<th>
					<a href="#" rel="alpha" title="<?php echo JText::_("CLICK TO SORT THIS COLUMN"); ?>"<?php if(JRequest::getVar("orderby") == "alpha" && JRequest::getVar("orderto") == "desc") echo " class='desc'"; ?>>
						<?php echo JText::_("K2_NAME"); ?>
						
						<?php if(JRequest::getVar("orderby") == "alpha") : ?>
							<?php if(JRequest::getVar("orderto") == "desc") : ?>
								<img src="modules/mod_k2_filter/assets/sort_desc.png" border="0" width="12" height="12" />
							<?php else : ?>
								<img src="modules/mod_k2_filter/assets/sort_asc.png" border="0" width="12" height="12" />
							<?php endif; ?>
						<?php endif; ?>
					</a>
				</th>
				<?php endif; ?>
				
				<?php if($this->params->get('genericItemIntroText')) : ?>
				<th>
					<a href="#" rel="intro" title="<?php echo JText::_("CLICK TO SORT THIS COLUMN"); ?>"<?php if(JRequest::getVar("orderby") == "intro" && JRequest::getVar("orderto") == "desc") echo " class='desc'"; ?>>
						<?php echo JText::_("K2_DESCRIPTION"); ?>
						
						<?php if(JRequest::getVar("orderby") == "intro") : ?>
							<?php if(JRequest::getVar("orderto") == "desc") : ?>
								<img src="modules/mod_k2_filter/assets/sort_desc.png" border="0" width="12" height="12" />
							<?php else : ?>
								<img src="modules/mod_k2_filter/assets/sort_asc.png" border="0" width="12" height="12" />
							<?php endif; ?>
						<?php endif; ?>
					</a>
				</th>
				<?php endif; ?>
				
				<?php if($this->params->get('genericItemExtraFields')) : ?>
				<?php foreach($this->extras as $extra) : ?>
				<th>
					<a href="#" rel="<?php echo $extra->id; ?>" title="<?php echo JText::_("CLICK TO SORT THIS COLUMN"); ?>"<?php if(JRequest::getVar("orderby") == $extra->id && JRequest::getVar("orderto") == "desc") echo " class='desc'"; ?>>
						<?php echo $extra->name; ?>
						
						<?php if(JRequest::getVar("orderby") == $extra->id) : ?>
							<?php if(JRequest::getVar("orderto") == "desc") : ?>
								<img src="modules/mod_k2_filter/assets/sort_desc.png" border="0" width="12" height="12" />
							<?php else : ?>
								<img src="modules/mod_k2_filter/assets/sort_asc.png" border="0" width="12" height="12" />
							<?php endif; ?>
						<?php endif; ?>
					</a>
				</th>
				<?php endforeach; ?>
				<?php endif; ?>
			</tr>
		</thead>
		
		<tbody>
	
		<?php foreach($this->items as $k=>$item): ?>

			<!-- Start K2 Item Layout -->
			
			<tr class="row<?php echo ($k+2) % 2; ?>">
			
				<?php if($item->params->get('genericItemDateCreated')): ?>
				<!-- Date created -->
				<td><?php echo JHTML::_('date', $item->created , JText::_('K2_DATE_FORMAT_LC2')); ?></td>
				<?php endif; ?>
			
				<?php if($item->params->get('genericItemCategory')): ?>
				<!-- Item category name -->
				<td><?php echo $item->category->name; ?></td>
				<?php endif; ?>
				
				<?php if($item->params->get('genericItemTitle')): ?>
				<!-- Item title -->
				<td><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></td>
				<?php endif; ?>
 
				<?php if($item->params->get('genericItemIntroText')): ?>
				<!-- Item introtext -->
				<td><?php echo $item->introtext; ?></td>
				<?php endif; ?>
			  
				<?php if($item->params->get('genericItemExtraFields') && count($item->extra_fields)): ?>
				<!-- Item extra fields -->  

				<?php foreach ($item->extra_fields as $key=>$extraField) : ?>
					<?php if($extraField->value) : ?>
						<td><?php echo $extraField->value; ?></td>		
					<?php endif; ?>
				<?php endforeach; ?>	
				<?php endif; ?>
			  
			</tr>

			<!-- End K2 Item Layout -->
		
		<?php endforeach; ?>
		
		</tbody>
		</table>
		
	</div>

	<!-- Pagination -->
	<?php if($this->pagination->getPagesLinks()): ?>
	<div class="k2Pagination">
		<?php echo $this->pagination->getPagesLinks(); ?>
		<div class="clr"></div>
		<?php echo $this->pagination->getPagesCounter(); ?>
	</div>
	<?php endif; ?>

	<?php endif; ?>
	
</div>
<!-- End K2 Generic (search/date) Layout -->
