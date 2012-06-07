<?php
/**
 * @version		$Id: category.php 1273 2011-10-27 16:12:32Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2011 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

	<div class="onecol">
		<h1>Stories</h1>
	</div>
	
	<div class="onecol">
		<div id="story_menu">
				<ul id="story_menunav">
					<li id="story_menunav"><a href="/stories" id="storyhomenav">Stories Main</a></li>
					<li id="story_menunav"><a href="/stories/film" id="storyfilmsnav">Film</a></li>
					<li id="story_menunav"><a href="/stories/written" id="storywrittennav">Written</a></li>
					<li id="story_menunav"><a href="/stories/photo" id="storyphotonav">Photo</a></li>
					<li id="story_menunav"><a href="/stories/spoken" id="storyaudionav">Spoken</a></li>						
					<li id="story_menunav"><a href="/stories/about" id="storyaboutnav">About Us</a></li>					
				</ul>
		</div> <!-- /story_menu -->
	</div>
	
	<div class="bump" style="height:10px;float:left;"></div>
	
	<div class="onecol" style="margin-bottom:10px;text-align:center;">
		<div class="rule_wh">
			<img src="../images/film-header-icon.png" alt="film-header-icon" style="margin:-26px 0 0 0;background-color:#DFDED2;padding:10px;" />
		</div>
	</div><!--/\/\/\FILMrule/\/\/\-->	


	<?php if($this->params->get('show_page_title')): ?>
	<!-- Page title -->
	<div class="componentheading<?php echo $this->params->get('pageclass_sfx')?>">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</div>
	<?php endif; ?>


	<?php if((isset($this->leading) || isset($this->primary) || isset($this->secondary) || isset($this->links)) && (count($this->leading) || count($this->primary) || count($this->secondary) || count($this->links))): ?>
	<!-- Item list -->
	<div class="itemList">

		<?php if(isset($this->leading) && count($this->leading)): ?>
		<!-- Leading items -->
		<div id="itemListLeading">
			<?php foreach($this->leading as $key=>$item): ?>

			<?php
			// Define a CSS class for the last container on each row
			if( (($key+1)%($this->params->get('num_leading_columns'))==0) || count($this->leading)<$this->params->get('num_leading_columns') )
				$lastContainer= ' itemContainerLast';
			else
				$lastContainer='';
			?>
			
			<div class="itemContainer<?php echo $lastContainer; ?>"<?php echo (count($this->leading)==1) ? '' : ' style="width:'.number_format(100/$this->params->get('num_leading_columns'), 1).'%;"'; ?>>
				<?php
					// Load category_item.php by default
					$this->item=$item;
					echo $this->loadTemplate('leading');
				?>
			</div>
			<?php if(($key+1)%($this->params->get('num_leading_columns'))==0): ?>
			<div class="clr"></div>
			<?php endif; ?>
			<?php endforeach; ?>
			<div class="clr"></div>
		</div>
		<?php endif; ?>

		
		<?php if(isset($this->primary) && count($this->primary)): ?>
		<!-- Primary items -->
		<div id="itemListPrimary">
			<?php foreach($this->primary as $key=>$item): ?>
			
			<?php
			// Define a CSS class for the last container on each row
			if( (($key+1)%($this->params->get('num_primary_columns'))==0) || count($this->primary)<$this->params->get('num_primary_columns') )
				$lastContainer= ' itemContainerLast';
			else
				$lastContainer='';
			?>
			
			<div class="itemContainer<?php echo $lastContainer; ?>"<?php echo (count($this->primary)==1) ? '' : ' style="width:'.number_format(100/$this->params->get('num_primary_columns'), 1).'%;"'; ?>>
				<?php
					// Load category_item.php by default
					$this->item=$item;
					echo $this->loadTemplate('primary');
				?>
			</div>
			<?php if(($key+1)%($this->params->get('num_primary_columns'))==0): ?>
			<div class="clr"></div>
			<?php endif; ?>
			<?php endforeach; ?>
			<div class="clr"></div>
		</div>
		<?php endif; ?>
	</div>

	<!-- Pagination -->
	<?php if(count($this->pagination->getPagesLinks())): ?>
	<div class="k2Pagination">
		<h5 class="grey" style="font-weight:500;letter-spacing:1px;"><?php if($this->params->get('catPagination')) echo $this->pagination->getPagesLinks(); ?>
		<div class="clr"></div>
		<?php if($this->params->get('catPaginationResults')) echo $this->pagination->getPagesCounter(); ?></h5>
	</div>
	<?php endif; ?>
	<?php endif; ?>
<!-- End K2 Category Layout -->
