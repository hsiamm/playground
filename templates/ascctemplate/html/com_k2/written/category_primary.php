<?php
/**
 * @version		$Id: category_item.php 1251 2011-10-19 17:50:13Z joomlaworks $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2011 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Define default image size (do not change)
K2HelperUtilities::setDefaultImage($this->item, 'itemlist', $this->params);

?>

<!-- Start K2 Item Layout -->
	<div class="twocol" style="margin-top:20px;">
		<?php if($this->item->params->get('catItemTitle')): ?>
		<!-- Item title -->
			<?php if ($this->item->params->get('catItemTitleLinked')): ?>
			<a href="<?php echo $this->item->link; ?>">
				<h1 class="black nocap nomar"><?php echo $this->item->title; ?></h1>
			</a>
			<?php else: ?>
			<?php echo $this->item->title; ?>
			<?php endif; ?>
		<?php endif; ?>	


		<?php if($this->item->params->get('catItemDateCreated')): ?>
		<!-- Date created -->
			<h3 class="grey"><?php echo JHTML::_('date', $this->item->created , JText::_('M d, Y')); ?></h3>
		<?php endif; ?>	 
	

		<?php if($this->item->params->get('catItemImage') && !empty($this->item->image)): ?>
		<!-- Item Image -->
		    <a href="<?php echo $this->item->link; ?>" title="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>">
		    	<img src="<?php echo $this->item->image; ?>" class="img_border" alt="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>" style="width:<?php echo $this->item->imageWidth; ?>px; height:auto;margin-right:10px;" />
		    </a>
		<?php endif; ?>
				
		
		<?php if($this->item->params->get('catItemIntroText')): ?>
		<!-- Item introtext -->
			<p style="font-size:12px;line-height:21px;margin-top:-6px;"><?php echo $this->item->introtext; ?></p>
		<?php endif; ?> 
	</div>		
<!-- End K2 Item Layout -->