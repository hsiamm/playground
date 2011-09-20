<?php
/**
 * @package   com_zoo Component
 * @file      full.php
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

<div class="fivecol" style="min-height:400px;">

    <div class="staff_photo">
        <?php if ($this->checkPosition('picture')) { ?>
            <?php echo $this->renderPosition('picture'); ?>
        <?php } else { ?>
            <img src="images/staff_pics/staff2.jpg" style="margin-bottom:5px;" alt=""/>
        <?php } ?>
    </div>

    <div class="staff_sub">
        <?php if ($this->checkPosition('blog')) { ?>
            <a href=""><div class="staff_blog"><h5 class="grey nomar">Blog</h5></div></a>
        <?php } ?>
        <?php if ($this->checkPosition('twitter')) { ?>
            <a href="http://twitter.com/#!/<?php echo trim($this->renderPosition('twitter')); ?>"><div class="staff_twitter"><h5 class="grey nomar">Twitter</h5></div></a>
        <?php } ?>
        <?php if ($this->checkPosition('email')) { ?>
            <a href=""><div class="staff_email"><h5 class="grey nomar">Email</h5></div></a>	
        <?php } ?>
    </div>

    <?php if ($this->checkPosition('name')) { ?>
        <h2 class="nomar">
            <?php echo $this->renderPosition('name'); ?>
        </h2>
    <?php } ?>

    <?php if ($this->checkPosition('job_title')) { ?>
        <h3 class="nomar nocap">
            <?php echo $this->renderPosition('job_title'); ?>
        </h3>
    <?php } ?>

    <div class="rule_wh_short"></div><!--/\/\/\rule/\/\/\-->
    <div class="bump"></div>

</div>