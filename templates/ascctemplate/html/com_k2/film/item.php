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
	
	<!-- Start K2 Item Layout -->
	<span id="startOfPageId<?php echo JRequest::getInt('id'); ?>"></span>
	
	<div class="onecol">
		<?php if($this->item->params->get('itemVideo') && !empty($this->item->video)): ?>
		<!-- Item video -->
		
			<?php if($this->item->videoType=='embedded'): ?>
		
			<div style="border:20px solid #fff;"><?php echo $this->item->video; ?></div>
		
			<?php else: ?>
			<div style="border:20px solid #fff;"><?php echo $this->item->video; ?></div>
			<?php endif; ?>
		
		<?php endif; ?>
	</div><!--onecol-->
	
	<div style="clear:both;"></div>
	<div class="bump">&nbsp;</div>

	<div class="onecol">
		<?php if($this->item->params->get('itemTitle')): ?>
		<!-- Item title -->
		<h1 class="black nocap">
			<?php if(isset($this->item->editLink)): ?>
			<!-- Item edit link -->
			<span class="itemEditLink">
				<a class="modal" rel="{handler:'iframe',size:{x:990,y:550}}" href="<?php echo $this->item->editLink; ?>">
					<?php echo JText::_('K2_EDIT_ITEM'); ?>
				</a>
			</span>
			<?php endif; ?>
			
			<?php echo $this->item->title; ?>
		
		</h1>
		<?php endif; ?>
	</div><!--/onecol-->
	
	<div class="twocol">
		<blockquote>
			<?php if(!empty($this->item->fulltext)): ?>
			<?php if($this->item->params->get('itemIntroText')): ?>
			<!-- Item introtext -->
			
				<?php echo $this->item->introtext; ?>
			<?php endif; ?>
			<?php if($this->item->params->get('itemFullText')): ?>
			<!-- Item fulltext -->
				<?php echo $this->item->fulltext; ?>
			<?php endif; ?>
			<?php else: ?>
			<!-- Item text -->
				<?php echo $this->item->introtext; ?>
			<?php endif; ?>		
		</blockquote>
	</div><!--/twocol-->
	
	<div class="fourcol">
		<h3>Share story:</h3>
		<?php if($this->item->params->get('itemTwitterButton',1) || $this->item->params->get('itemFacebookButton',1) || $this->item->params->get('itemGooglePlusOneButton',1)): ?>
		<!-- Social sharing -->
			<?php if($this->item->params->get('itemTwitterButton',1)): ?>
			<!-- Twitter Button -->
			<div class="itemTwitterButton" style="margin-top:-1px;">
				<a href="https://twitter.com/share" class="twitter-share-button" data-via="storyteam" data-related="theaustinstone" data-hashtags="stories">Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div>
			<?php endif; ?>
			<?php if($this->item->params->get('itemFacebookButton',1)): ?>
			<!-- Facebook Button -->
			<div class="itemFacebookButton" style="margin:-24px 0 0 90px;">
				<div id="fb-root"></div>
				<script type="text/javascript">
					(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) {return;}
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/en_US/all.js#appId=177111755694317&xfbml=1";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
				</script>
				<div class="fb-like" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-font="arial"></div>
			</div>
			<?php endif; ?>
		<?php endif; ?>	
		<div style="clear:both;"></div>
		<div class="bump">&nbsp;</div>	
		<a href="#" class="hot_fourcol-b"><blockquote>More written stories</blockquote></a>	
	</div><!--/foucol-->
	<div class="fourcol">
		<h3>Related Stories:</h3>
		<p class="sans">tags tags tags tags tags tags tags tags tags tags tags tags tags tags tags tags tags tags tags tags tags </p>
	</div>	

	<div style="clear:both;"></div>


<!-- End K2 Item Layout -->
