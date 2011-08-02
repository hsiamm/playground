<?php 
/**
* @package   com_zoo Component
* @file      default.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// add js
$this->app->document->addScript('assets:js/update.js');

?>

<form id="update-default" action="index.php" method="post" name="adminForm" accept-charset="utf-8">

<div class="box-bottom">

	<?php if ($this->update) :?>

	<div class="col col-left width-40">
		<div class="updatebox">
			<div>
				<h3><?php echo JText::_('ZOO requires to be updated:'); ?></h3>
				<button class="button-green update" type="button">
					<span><?php echo JText::_('Start Update'); ?></span>
				</button>
			</div>
			<div class="message-box"></div>
		</div>
		
	</div>
	
	<div class="col col-right width-60">
		<h2><?php echo JText::_('Information'); ?>:</h2>

		<div class="creation-form infobox">
			<p><?php echo JText::_("For the ZOO to function correctly it needs to run some update scripts."); ?></p>
		</div>
	</div>
	
	<?php else :

			$title   = JText::_('No further Update required').'!';
			$message = null;
			echo $this->partial('message', compact('title', 'message'));

		endif;
	?>

</div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="step" />
<input type="hidden" name="format" value="raw" />
<?php echo $this->app->html->_('form.token'); ?>

<script type="text/javascript">
	jQuery(function($) {
		$('#update-default').Update({ msgPerformingUpdate: '<?php echo JText::_('Performing Update...'); ?>', msgFinished: '<?php echo JText::_('Update successfull...Reload page to continue working.'); ?>' });
	});	
</script>

</form>

<?php echo ZOO_COPYRIGHT; ?>