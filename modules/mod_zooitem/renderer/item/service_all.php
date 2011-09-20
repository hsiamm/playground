<?php
/**
 * @package   ZOO Item
 * @file      layout3.php
 * @version   2.4.2
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

$media_position = $params->get('media_position', 'top');
$title = trim($this->renderPosition('title'));
?>

<div class="service expand">
    <div class="serve_opp">
        <h2 class="twenty nomar">
            <?php echo str_replace('-','&ndash;',$title); ?>
        </h2>
        <div style="float:right;" class="map_button">
            <a href="#"><p class="show caption caps">Show Details</p></a>
        </div>
        <div class="serve_signup_button">
            <a href="<?php echo trim($this->renderPosition('signup')); ?>">
                <p class="caption caps">Sign Up</p>
            </a>
        </div>
        <h4 class="caps"><?php echo $this->renderPosition('hours'); ?></h4>
    </div>
    <!-- hidden stuff -->
    <div class="serve_opp_details">

        <p><?php echo $this->renderPosition('description'); ?></p>

    </div>
</div>