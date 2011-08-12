<?php
/**
* @package   com_zoo Component
* @file      teaser.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// init vars
$params = $item->getParams('site');

?>

<?php if ($this->checkPosition('title')) : ?>
<h1 class="pos-title">
	<?php echo $this->renderPosition('title'); ?>
</h1>
<?php endif; ?>

<?php if ($this->checkPosition('location')) : ?>
<h1 class="pos-location">
	<?php echo $this->renderPosition('location'); ?>
</h1>
<?php endif; ?>

<?php if ($this->checkPosition('start_date')) : ?>
<h2 class="pos-start_date">
	<?php echo $this->renderPosition('start_date'); ?>
</h2>
<?php endif; ?>

<?php if ($this->checkPosition('end_date')) { ?>
<h2 class="pos-end_date">
	<?php echo $this->renderPosition('end_date'); ?>
</h2>
<?php } ?>

<?php if ($this->checkPosition('weeks_times')) { ?>
<p class="pos-weeks_times">
	<?php echo $this->renderPosition('weeks_times'); ?>
</p>
<?php } ?>

<?php if ($this->checkPosition('class_description')) { ?>
<p class="pos-class_description">
	<?php echo $this->renderPosition('class_description'); ?>
</p>
<?php } ?>