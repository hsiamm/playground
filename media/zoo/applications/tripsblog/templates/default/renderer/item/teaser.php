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

//get the item id
$arr = explode('"', $this->renderPosition('item_link'), 3);
$itemID = substr($arr[1], 20);

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
    $classfix .= 'central-asia ';
else if (strpos($location, 'India'))
    $classfix .= 'india ';
else if (strpos($location, 'Europe'))
    $classfix .= 'europe ';
else if (strpos($location, 'West Africa'))
    $classfix .= 'west-africa ';
else if (strpos($location, 'Central Africa'))
    $classfix .= 'central-africa ';
else if (strpos($location, 'Asia'))
    $classfix .= 'asia ';

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
                <div class="trips_types_construction" title="Construction"></div>
            <?php } if (strpos($categories, 'Education')) { ?>
                <div class="trips_types_education" title="Education"></div>
            <?php } if (strpos($categories, 'Evangelism')) { ?>
                <div class="trips_types_evangelism" title="Evangelism"></div>
            <?php } if (strpos($categories, 'Exposure')) { ?>
                <div class="trips_types_exposure" title="Exposure"></div>
            <?php } if (strpos($categories, 'Medical')) { ?>
                <div class="trips_types_medical" title="Medical"></div>
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


            <p><?php echo $this->renderPosition('description'); ?></p>
                <p class="grey"><em>Please note, all trip funds including deposits are non-refundable.</em></p>
           
            <div style="clear:both;"></div>
        </div>


        <div style="clear:both;height:15px;"></div>

        <div class="rule_trips-l">&nbsp;</div>
        <div class="trips_price"><h2 class="blue nocap">Trip Cost: <?php echo $this->renderPosition('trip_cost'); ?></h2></div>
        <div class="rule_trips-r">&nbsp;</div>

        <div class="bump">&nbsp;</div>

        <?php if (strpos($this->renderPosition('status'), 'Open')) { ?>
            <div class="trips_funds_pop fancy_pop" href="#inline_funds_<?php echo $itemID; ?>">
                <h4 class="yellow caps">When Are Funds Due?</h4>
            </div>
            <div style="display: none;">
                <div id="inline_funds_<?php echo $itemID; ?>" style="width:440px;height:auto;overflow:auto;">
                    <div class="trip_funds_due">
                        <?php if ($this->checkPosition('timeline_dates')) { ?>
                            <h1 class="title">When are funds due?</h1>
                            <?php $dates = explode('|', $this->renderPosition('timeline_dates')); ?>
                            <?php $costs = explode('|', $this->renderPosition('timeline_cost')); ?>
                            <ul class="bullet">
                                <li><?php echo $dates[0] . "&nbsp;|&nbsp;" . $costs[0]; ?></li>
                                <li><?php echo $dates[1] . "&nbsp;|&nbsp;" . $costs[1]; ?></li>
                                <li><?php echo $dates[2] . "&nbsp;|&nbsp;" . $costs[2]; ?></li>
                            </ul>
                        <?php } else { ?>
                            <h2 class="nomar blue">No timeline set for funds yet.</h2>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="trips_apply">
            	<?php $type=$this->renderPosition('type'); ?>
            	
            	<?php if (strpos($type,'CRU')) { ?>
            	<div class="fancy_pop" href="#cru"><h4 class="black caps">Start Application</h4></div>
            	<div style="display: none;">
                <div id="cru" style="width:440px;height:auto;overflow:auto;">
                	<div class="trip_funds_due">
	            		<h1 class="title"><a href="<?php echo trim($this->renderPosition('link')); ?>">Click here</a> to go to the CRU website to begin the application process.</h1>
                	</div>
                </div>
                </div>
            	<?php } else if (strpos($type,'In-house')) { ?>
                <a href="<?php echo trim($this->renderPosition('link')); ?>"><h4 class="black caps">Start Application</h4></a>
                <?php } else { ?>
             	<div class="fancy_pop" href="#journey"><h4 class="black caps">Start Application</h4></div>
	            	<div style="display: none;">
	                <div id="journey" style="width:440px;height:auto;overflow:auto;">
	                	<div class="trip_funds_due">
		            		<ul class="bullet">
	                            <li>Follow <a href="https://www.formspring.com/forms/?164673-MrI2dGIKUF">this link</a> to the Journey Form.</li>
	                            <li>Fill out and submit the “Journey” form (Indicate your interest in the South Asia Exposure Team).</li>
	                        </ul>
	                	</div>
	                </div>
                </div>               
                <?php } ?>
            </div>
        <?php } else if (strpos($this->renderPosition('status'), 'Closed')) { ?>
            <div class="trips_funds_pop fancy_pop" href="#inline_funds_<?php echo $itemID; ?>">
                <h4 class="yellow caps">When Are Funds Due?</h4>
            </div>
            <div style="display: none;">
                <div id="inline_funds_<?php echo $itemID; ?>" style="width:440px;height:auto;overflow:auto;">
                    <div class="trip_funds_due">
                        <?php if ($this->checkPosition('timeline_dates')) { ?>
                            <h1 class="title">When are funds due?</h1>
                            <?php $dates = explode('|', $this->renderPosition('timeline_dates')); ?>
                            <?php $costs = explode('|', $this->renderPosition('timeline_cost')); ?>
                            <ul class="bullet">
                                <li><?php echo $dates[0] . "&nbsp;|&nbsp;" . $costs[0]; ?></li>
                                <li><?php echo $dates[1] . "&nbsp;|&nbsp;" . $costs[1]; ?></li>
                                <li><?php echo $dates[2] . "&nbsp;|&nbsp;" . $costs[2]; ?></li>
                            </ul>
                        <?php } else { ?>
                            <h1 class="title">No timeline set for funds yet.</h1>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="trips_capacity">
                <h4 class="black caps">Trip At Capacity</h4>
            </div>
        <?php } else if (strpos($this->renderPosition('status'), 'Not Finalized')) { ?>
            <div class="trips_nodate">
                <p class="trips nomar"><em>Trip dates are not yet finalized and will be updated as soon as possible.</em></p>
            </div>
        <?php } ?>

        <div style="clear:both;"></div>
    </div><!--/trips_general -->			
</div><!--/trips_item -->
