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
$location = $this->renderPosition('locale');
$categories = $this->renderPosition('categories');
$classfix = '';
if (strpos($location, 'North Africa'))
    $classfix .= 'north-africa ';
else if (strpos($location, 'North America'))
    $classfix .= 'north-america ';
else if (strpos($location, 'Dominican Republic'))
    $classfix .= 'dominican ';
else if (strpos($location, 'Haiti'))
    $classfix .= 'haiti ';
else if (strpos($location, 'Horn of Africa'))
    $classfix .= 'horn-africa ';
else if (strpos($location, 'Central Asia'))
    $classfix .= 'asia ';
else if (strpos($location, 'India'))
    $classfix .= 'india ';

if (strpos($categories, 'Construction'))
    $classfix .= 'construction ';
if (strpos($categories, 'Education'))
    $classfix .= 'education ';
if (strpos($categories, 'Evangelism'))
    $classfix .= 'evangelism ';
if (strpos($categories, 'Exposure'))
    $classfix .= 'exposure ';
if (strpos($categories, 'Medical'))
    $classfix .= 'medical ';

$classfix = trim($classfix);
?>

<div class="trips_item <?php echo $classfix; ?>">
    <div class="trips_general">
        <div class="trips_details">

            <?php if (strpos($categories, 'Construction')) { ?>
                <div class="trips_types_construction"></div>
            <?php } if (strpos($categories, 'Education')) { ?>
                <div class="trips_types_education"></div>
            <?php } if (strpos($categories, 'Evangelism')) { ?>
                <div class="trips_types_evangelism"></div>
            <?php } if (strpos($categories, 'Exposure')) { ?>
                <div class="trips_types_exposure"></div>
            <?php } if (strpos($categories, 'Medical')) { ?>
                <div class="trips_types_medical"></div>
            <?php } ?>

            <h1 class="blue nomar"><?php echo $this->renderPosition('title'); ?></h1>
            <?php if ($this->checkPosition('subtitle')) { ?>
                <h2><?php echo $this->renderPosition('subtitle'); ?></h2>
            <?php } ?>

            <?php if ($this->checkPosition('start_date')) { ?>
                <h3 class="grey"><?php echo $this->renderPosition('start_date'); ?>&ndash;<?php echo $this->renderPosition('end_date'); ?></h3>
            <? } else { ?>
                <h3 class="grey"><?php echo $this->renderPosition('relative_dates'); ?></h3>
            <?php } ?>


            <p class="sans"><?php echo $this->renderPosition('description'); ?></p>
            <?php if (strpos($this->renderPosition('note'), 'Yes')) { ?>
                <p class="grey"><em>Please note, all trip funds including deposits are non-refundable. For additional information regarding our financial policy, please contact us at <a class="grey" href="mailto:makedisciples@austinstone.org">makedisciples@austinstone.org</a>.</em></p>
            <?php } ?>
            <div style="clear:both;"></div>
        </div>


        <div style="clear:both;height:15px;"></div>

        <div class="rule_trips-l">&nbsp;</div>
        <div class="trips_price"><h2 class="blue nocap">Trip Cost: <?php echo $this->renderPosition('trip_cost'); ?></h2></div>
        <div class="rule_trips-r">&nbsp;</div>

        <div class="bump">&nbsp;</div>

        <?php if (strpos($this->renderPosition('status'), 'Open')) { ?>
            <div class="trips_funds_pop">
                <h4 class="yellow caps">When Are Funds Due?</h4>
            </div>
            <div class="trips_apply">
                <a href="<?php echo trim($this->renderPosition('link')); ?>"><h4 class="black caps">Start Application</h4></a>
            </div>
        <?php } else if (strpos($this->renderPosition('status'), 'Closed')) { ?>
            <div class="trips_funds_pop">
                <h4 class="yellow caps">When Are Funds Due?</h4>
            </div>
            <div class="trips_capacity">

                <h4 class="black caps">Trip At Capacity</h4>
            </div>
        <?php } else if (strpos($this->renderPosition('status'), 'Not Finalized')) { ?>
            <div class="trips_nodate">
                <p class="sans"><em>Trip dates are not yet finalized and will be updated as soon as possible. We will open the trip for registration when dates are confirmed.</em></p>
            </div>
        <?php } ?>

        <div style="clear:both;"></div>
    </div><!--/trips_general -->			
</div><!--/trips_item -->
