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

$location = $this->renderPosition('location');
$categories = $this->renderPosition('categories');
$status = $this->renderPosition('status');
$classfix = '';
if (strpos($location, 'Austin High'))
    $classfix .= 'austinhigh ';
else
    $classfix .= 'stjohn ';
if (strpos($categories, 'Bible'))
    $classfix .= 'bible ';
if (strpos($categories, 'Christian Life'))
    $classfix .= 'christianlife ';
if (strpos($categories, 'Theology'))
    $classfix .= 'theology ';
if (strpos($categories, 'Skills'))
    $classfix .= 'skills ';
if (strpos($categories, 'Stage of Life'))
    $classfix .= 'stageoflife ';
if (strpos($categories, 'Connect'))
    $classfix .= 'connect ';

$classfix = trim($classfix);
?>

<div class="class <?php echo $classfix ?>">
    <div class="class_general">
        <h1 class="title"><?php echo $this->renderPosition('title'); ?></h1>

        <div style="clear:both;"></div>

        <!--
        <div class="class_signup">
        <?php //if (strpos($status, 'Open')) { ?>
                <a href="http://<?php //echo trim($this->renderPosition('link'));  ?>"><p class="caption caps">Sign Up Now</p></a>
        <?php // } else if (strpos($status, 'Waitlist')) { ?>
                <a href="http://<?php //echo trim($this->renderPosition('link'));  ?>"><p class="caption caps">Join Waitlist</p></a>
        <?php //} else { ?>
                <a style="background:#77787B; cursor:default;" href="#"><p class="caption caps"><del>Sign Up Now</del></p></a>
        <?php //} ?>
        </div>
        -->
        <div class="bump"></div>

        <div style="clear:both;"></div>

        <?php if (strpos($categories, 'Bible')) { ?>
            <div class="class_cat_bible"></div>
        <?php } if (strpos($categories, 'Christian Life')) { ?>
            <div class="class_cat_xian_life"></div>
        <?php } if (strpos($categories, 'Theology')) { ?>
            <div class="class_cat_theology"></div>
        <?php } if (strpos($categories, 'Skills')) { ?>
            <div class="class_cat_skills"></div>
        <?php } if (strpos($categories, 'Stage of Life')) { ?>
            <div class="class_cat_lifestage"></div>
        <?php } if (strpos($categories, 'Connect')) { ?>
            <div class="class_cat_connect"></div>
        <?php } ?>

        <div class="class_info">
            <?php if (strpos($status, 'Closed')) { ?>
                <h3 style="color:red;">Sorry, class closed</h3>
            <?php } else if (strpos($status, 'Waitlist')) { ?>
                <h3 style="color:red;">Class full, join waitlist</h3>
            <?php } ?>
            <h3><?php echo $this->renderPosition('location'); ?><br>
                <?php echo $this->renderPosition('day_of_week'); ?> <?php echo str_replace('-', '&ndash;', $this->renderPosition('time')); ?></h3>
            <h3><?php echo $this->renderPosition('weeks'); ?> / Fee: <?php echo $this->renderPosition('fee'); ?><br>

                <?php echo str_replace('-', '&ndash;', $this->renderPosition('dates')); ?></h3>
            <h3><?php echo str_replace(',', '<br>', $this->renderPosition('instructors')); ?></h3>
        </div>
        <div style="clear:both;margin-top:10px;"></div>
    </div>

    <p class="class_details_button">More Details</p>

    <div class="class_details">			
        <div class="rule_class"></div>

        <p><?php echo $this->renderPosition('class_description'); ?></p>

        <h5 class="nomar"><?php echo str_replace('-', '&ndash;', $this->renderPosition('homework')); ?></h5>
    </div>
</div>