<?php
/**
 * @package   ZOO Item
 * @file      list-v.php
 * @version   2.4.2
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<div class="home_hap">
    <h4 class="nomar grey">THINGS TO KNOW:</h4>
    
    <?php if (!empty($items)) : ?>
        <?php $i = 0;
        foreach ($items as $item) : ?>
            <h2 class="nomar"><?php echo $renderer->render('item.' . $layout, compact('item', 'params')); ?><img src="/images/arrow_g.png" alt="Go"/></h2>
            <?php $i++;
        endforeach; ?>

    <?php else : ?>
        <?php echo JText::_('COM_ZOO_NO_ITEMS_FOUND'); ?>
    <?php endif; ?>
</div>