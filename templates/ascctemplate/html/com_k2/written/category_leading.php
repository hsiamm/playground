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
	<div class="onecol">
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
		<span class="catItemDateCreated">
			<h3 class="grey"><?php echo JHTML::_('date', $this->item->created , JText::_('M d, Y')); ?></h3>
		</span>
		<?php endif; ?>			
	</div><!--/onecol-->
	
	
	<div class="threecol_2">
		<?php if($this->item->params->get('catItemIntroText')): ?>	
		<!-- Item introtext -->
			<p class="story"><?php echo $this->item->introtext; ?></p>
		<?php endif; ?>
				
		
		<?php if ($this->item->params->get('catItemReadMore')): ?>
		<!-- Item "read more..." link -->
			<a class="hot_film_featured" href="<?php echo $this->item->link; ?>">
				<h1><?php echo JText::_('READ MORE'); ?></h1>
			</a>
		<?php endif; ?>		
	</div><!--/threecol_2-->
	
	<div class="threecol">	
		<?php if($this->item->params->get('catItemImage') && !empty($this->item->image)): ?>
		<!-- Item Image -->
		    <a href="<?php echo $this->item->link; ?>" title="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>">
		    	<img src="<?php echo $this->item->image; ?>" class="img_border" alt="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>" style="width:<?php echo $this->item->imageWidth; ?>px; height:auto;" />
		    </a>
		<?php endif; ?>
	</div><!--/threecol-->
	
	<div class="onecol"><div class="rule_wh"></div></div><!--/\/\/\rule/\/\/\-->
	<!-- End K2 Item Layout -->
