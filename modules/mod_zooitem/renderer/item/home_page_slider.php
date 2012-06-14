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
<li class="home-slide nomar white nocap" style="line-height:35px;">
    <div class="content" style="background-image: url('<?php echo trim($this->renderPosition('image')); ?>');"></div>

    <?php if ($this->checkPosition('text')) { ?>
        <div class="container">
            <div class="home_image_info">
                <?php echo $this->renderPosition('text'); ?>
            </div>
        </div>
    <?php } ?>
</li>