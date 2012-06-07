<?php
/**
 * @version		$Id: item.php 1251 2011-10-19 17:50:13Z joomlaworks $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2011 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<?php if(JRequest::getInt('print')==1): ?>
<!-- Print button at the top of the print page only -->
<a class="itemPrintThisPage" rel="nofollow" href="#" onclick="window.print();return false;">
	<span><?php echo JText::_('K2_PRINT_THIS_PAGE'); ?></span>
</a>
<?php endif; ?>

<!-- Start K2 Item Layout -->
	<?php if($this->item->params->get('itemDateCreated')): ?>
	<!-- Date created -->
		<?php echo JHTML::_('date', $this->item->created , JText::_('K2_DATE_FORMAT_LC2')); ?>
	<?php endif; ?>


	<?php if($this->item->params->get('itemTitle')): ?>
	<!-- Item title -->
		<h1 class="nocap white"><?php echo $this->item->title; ?></h1>
	<?php endif; ?>


	<?php if($this->item->params->get('itemImage') && !empty($this->item->image)): ?>
	<!-- Item Image -->
	  	<a rel="{handler: 'image'}" href="<?php echo $this->item->imageXLarge; ?>" title="<?php echo JText::_('K2_CLICK_TO_PREVIEW_IMAGE'); ?>">
	  		<img src="<?php echo $this->item->image; ?>" alt="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>" style="width:<?php echo $this->item->imageWidth; ?>px; height:auto;" />
	  	</a>
	<?php endif; ?>
	
	
	<!-- Item fulltext -->
	<?php echo $this->item->introtext; ?>


	<?php if($this->item->params->get('itemExtraFields') && count($this->item->extra_fields)): ?>
	<!-- Item extra fields -->
		<h3><?php echo JText::_('K2_ADDITIONAL_INFO'); ?></h3>
		<ul>
		<?php foreach ($this->item->extra_fields as $key=>$extraField): ?>
		<?php if($extraField->value): ?>
		<li class="<?php echo ($key%2) ? "odd" : "even"; ?> type<?php echo ucfirst($extraField->type); ?> group<?php echo $extraField->group; ?>">
			<span class="itemExtraFieldsLabel"><?php echo $extraField->name; ?>:</span>
			<span class="itemExtraFieldsValue"><?php echo $extraField->value; ?></span>
		</li>
		<?php endif; ?>
		<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>
<!-- End K2 Item Layout -->
