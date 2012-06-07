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
			<img src="../images/written-header-icon.png" alt="film-header-icon" style="margin:-22px 0 0 0;background-color:#DFDED2;padding:10px;" />
		</div>
	</div><!--/\/\/\/\/\/\/\/\/\WRITTENrule/\/\/\/\/\/\/\/\/\-->
		
		
<!-- Start K2 Item Layout -->
	<div class="story_written-container">		
		<?php if($this->item->params->get('itemDateCreated')): ?>
		<!-- Date created -->
			<h3 class="grey" style="float:right;"><?php echo JHTML::_('date', $this->item->created , JText::_('K2_DATE_FORMAT_LC2')); ?></h3>
			<div style="clear:both;"></div>
			<div class="bump">&nbsp;</div>
		<?php endif; ?>
		
		
		<div class="story_written-post">
			<?php if($this->item->params->get('itemTitle')): ?>
			<!-- Item title -->
			<h1 class="black nocap"><?php echo $this->item->title; ?></h1>
			<?php endif; ?>
		
			
			<!-- Item fulltext -->
			<?php echo $this->item->introtext; ?>
			
			
			<div class="rule_bl"></div><!--/\/\/\rule/\/\/\-->
			
			
			<div class="bump">&nbsp;</div>
			<h3>Share story:</h3>
			<div style="float:left;width:130px;">		
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
			</div><!--/fourcol-->
			
			<div style="float:right;">
				<a href="#" class="hot_fourcol-b"><blockquote>More written stories</blockquote></a>
			</div>
		</div><!--/story_written-post-->
	</div><!--/written-container-->
<!-- End K2 Item Layout -->