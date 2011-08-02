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

<?php if ($this->checkPosition('name')) : ?>
    <h1 class="title">
        <?php echo $this->renderPosition('name'); ?>
    </h1>
<?php endif; ?>

<?php if ($this->checkPosition('intern')) : ?>
    <p class="intern">
        <?php echo $this->renderPosition('intern'); ?>
    </p>
<?php endif; ?>

<?php if ($this->checkPosition('job_title')) : ?>
    <h2 class="job_title">
        <?php echo $this->renderPosition('job_title'); ?>
    </h2>
<?php endif; ?>

<?php if ($this->checkPosition('picture')) : ?>
    <div class="picture">
        <?php echo $this->renderPosition('picture'); ?>
    </div>
<?php endif; ?>
    
    <?php if ($this->checkPosition('blog')) : ?>
    <div class="blog">
        <?php echo $this->renderPosition('blog'); ?>
    </div>
<?php endif; ?>
    
    <?php if ($this->checkPosition('email')) : ?>
    <div class="email">
        <?php echo $this->renderPosition('email'); ?>
    </div>
<?php endif; ?>
    
    <?php if ($this->checkPosition('twitter')) : ?>
    <div class="twitter">
        <?php echo $this->renderPosition('twitter'); ?>
    </div>
<?php endif; ?>