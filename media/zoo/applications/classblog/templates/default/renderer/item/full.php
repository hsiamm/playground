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

<?php if ($this->checkPosition('date_time')) : ?>
<p class="pos-date_time">
	<?php echo $this->renderPosition('date_time'); ?>
</p>
<?php endif; ?>

<?php if ($this->checkPosition('homework')) : ?>
<h2 class="pos-homework">
	Homework: <?php echo $this->renderPosition('homework'); ?>
</h2>
<?php endif; ?>

<?php if ($this->checkPosition('class_fee')) : ?>
<h2 class="pos-class_fee">
	<?php echo $this->renderPosition('class_fee'); ?>
</h2>
<?php endif; ?>

<?php if ($this->checkPosition('instructor')) : ?>
<h2 class="pos-instructor">
	Instructor: <?php echo $this->renderPosition('instructor'); ?>
</h2>
<?php endif; ?>

<?php if ($this->checkPosition('open_to')) : ?>
<h2 class="pos-open_to">
	Open to: <?php echo $this->renderPosition('open_to'); ?>
</h2>
<?php endif; ?>

<?php if ($this->checkPosition('class_description')) : ?>
<h2 class="pos-class_description">
	Class Description: <?php echo $this->renderPosition('class_description'); ?>
</h2>
<?php endif; ?>