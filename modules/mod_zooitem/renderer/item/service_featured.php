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
?>

<div class="slide">
    <div class="serve_featured_content">
        <h1 class="serve"><?php echo $this->renderPosition('title'); ?></h1>
        <h4 class="caps white"><?php echo $this->renderPosition('hours'); ?></h4>

        <p class="sans white"><?php echo $this->renderPosition('description'); ?></p>
        <div style="float:left;" class="map_button">
            <a href="<?php echo trim($this->renderPosition('signup')); ?>"><p class="show caption caps">Sign Up</p></a>
        </div>
    </div>
</div>