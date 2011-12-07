<?php
/**
 * @package   com_zoo Component
 * @file      frontpage.php
 * @version   2.4.10 June 2011
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<!-- Expanding AnythingSlider 100% width -->

<div id="wrapper1">
    <ul id="slider1">
        <?php if (!empty($items)) : ?>
            <?php $i = 0;
            foreach ($items as $item) : ?>
                <h2 class="nomar"><?php echo $renderer->render('item.' . $layout, compact('item', 'params')); ?></h2>
                <?php $i++;
            endforeach; ?>

        <?php else : ?>
            <?php echo JText::_('COM_ZOO_NO_ITEMS_FOUND'); ?>
        <?php endif; ?>
    </ul>
</div>

<!-- END AnythingSlider -->