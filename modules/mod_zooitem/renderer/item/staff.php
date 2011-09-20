<?php
/**
 * @package   ZOO Item
 * @file      default.php
 * @version   2.4.2
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
$category = $this->renderPosition('categories');
$category = str_replace('Elder', '', $category);
$category = str_replace('Deacon', '', $category);
?>

<div class="fivecol_staff">

    <?php if (!strpos($category, 'Staff')) { ?>
        <div class="staff_cat_top"> 
            <p class="caption caps" style="line-height:22px;"><?php echo $category; ?></p> 
        </div> 
    <?php } ?>
    <div class="staff_photo">
        <?php if ($this->checkPosition('picture')) { ?>
            <?php echo $this->renderPosition('picture'); ?>
        <?php } else { ?>
            <img src="images/staff_pics/staff2.jpg" style="margin-bottom:5px;" alt=""/>
        <?php } ?>
    </div>

    <div class="staff_sub">
        <?php if ($this->checkPosition('blog')) { ?>
            <a href="<?php echo trim($this->renderPosition('blog')); ?>"><div class="staff_blog"><h5 class="grey nomar">Blog</h5></div></a>
        <?php } ?>
        <?php if ($this->checkPosition('twitter')) { ?>
            <a href="http://twitter.com/#!/<?php echo trim(str_replace('@', '', $this->renderPosition('twitter'))); ?>"><div class="staff_twitter"><h5 class="grey nomar">Twitter</h5></div></a>
        <?php } ?>
        <?php if ($this->checkPosition('email')) { ?>
            <a href="mailto:<?php echo trim($this->renderPosition('email')); ?>"><div class="staff_email"><h5 class="grey nomar">Email</h5></div></a>	
        <?php } ?>
        <?php //if ($this->checkPosition('bio')) { ?>
        <!--<a href=""><div class="staff_bio"><h5 class="grey nomar">Bio</h5></div></a>-->
        <?php //} ?>
    </div>

    <?php if ($this->checkPosition('name') && !$this->checkPosition('placeholder')) { ?>
        <h2 class="nomar blue">
            <?php echo $this->renderPosition('name'); ?>
        </h2>
    <?php } ?>

    <?php if ($this->checkPosition('job_title')) { ?>
        <h3 class="nomar nocap">
            <?php echo $this->renderPosition('job_title'); ?>
        </h3>
    <?php } ?>

    <!--<div class="rule_wh_short"></div>
    <h4 class="nomar">WHAT I DO:</h4>
        <h4>Drumstick meatballpork loin beef, venison spare ribs beef ribs corned beef ham shankle short loin. Cow drumstick venison, tenderloin</h4>
    -->
    <div class="bump"></div>

</div>