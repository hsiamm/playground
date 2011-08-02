<?php
/**
* @package   com_zoo Component
* @file      edit.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$id = 'elements'.$element.'';

?>

<div id="<?php echo $id; ?>">

    <div class="row">
        <?php echo $this->app->html->_('control.selectfile', 'root:'.$directory, '/^.*('.$extensions.')$/i', 'elements['.$element.'][file]', $file, 'class="file"'); ?>
    </div>

    <div class="row">
        <?php echo $this->app->html->_('control.text', 'elements['.$element.'][url]', $url, 'placeholder="'.JText::_('URL').'" class="url" size="50" maxlength="255" title="'.JText::_('URL').'"'); ?>
    </div>

	<div class="more-options">
		<div class="trigger">
			<div>
				<div class="file button"><?php echo JText::_('Video File'); ?></div>
				<div class="url button"><?php echo JText::_('Video Provider'); ?></div>
				<div class="advanced button hide"><?php echo JText::_('Hide Options'); ?></div>
				<div class="advanced button"><?php echo JText::_('Show Options'); ?></div>
			</div>
		</div>

		<div class="advanced options">
			<div class="row short">
				<?php echo $this->app->html->_('control.text', 'elements['.$element.'][width]', $width, 'maxlength="4" title="'.JText::_('Width').'" placeholder="'.JText::_('Width').'"'); ?>
			</div>

			<div class="row short">
					<?php echo $this->app->html->_('control.text', 'elements['.$element.'][height]', $height, 'maxlength="4" title="'.JText::_('Height').'" placeholder="'.JText::_('Height').'"'); ?>
			</div>

			<div class="row">
				<strong><?php echo JText::_('AutoPlay'); ?></strong>
				<?php echo $this->app->html->_('select.booleanlist', 'elements['.$element.'][autoplay]', '', $autoplay); ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery(function($) {
		$('#<?php echo $id; ?>').ElementVideo();
	});
</script>