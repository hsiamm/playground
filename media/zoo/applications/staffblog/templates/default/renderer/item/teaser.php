<?php
/**
 * @package   com_zoo Component
 * @file      teaser.php
 * @version   2.4.10 July 2011
 * @author    Jason Kennedy http://www.austinstone.org
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

// init vars
$params = $item->getParams('site');
?>

<?php if ($this->checkPosition('name')) { ?>
    <h1 class="pos-title">
        <?php echo $this->renderPosition('name'); ?>
    </h1>
<?php } ?>

<?php if ($this->checkPosition('categories')) { ?>
    <h2 class="pos-categories">
        <?php echo $this->renderPosition('categories') ?>
    </h2>
<?php } ?>

<?php if ($this->checkPosition('job_title')) { ?>
    <h2 class="pos-job_title">
        <?php echo $this->renderPosition('job_title'); ?>
    </h2>
<?php } ?>

<?php if ($this->checkPosition('picture')) { ?>
    <div class="pos-picture">
        <?php echo $this->renderPosition('picture'); ?>
    </div>
<?php } ?>

<?php if ($this->checkPosition('blog')) { ?>
    <div class="pos-blog">
        <?php echo $this->renderPosition('blog'); ?>
    </div>
<?php } ?>

<?php if ($this->checkPosition('email')) { ?>
    <div class="pos-email">
        <?php echo $this->renderPosition('email'); ?>
    </div>
<?php } ?>

<?php if ($this->checkPosition('twitter')) { ?>
    <div class="pos-twitter">
        <?php echo $this->renderPosition('twitter'); ?>
    </div>
<?php } ?>