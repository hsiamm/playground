<?php
/**
 * @package   ZOO Item
 * @file      layout2.php
 * @version   2.4.2
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

$media_position = $params->get('media_position', 'top');
?>

<div class="current_item">
    <?php if ($this->checkPosition('picture')) { ?>
        <a href="<?php echo trim($this->renderPosition('link')); ?>">
            <?php echo $this->renderPosition('picture'); ?>
        </a>
    <?php } else { ?>
        <img src="http://placehold.it/140x140" />
    <?php } ?>

    <h2><?php echo $this->renderPosition('title'); ?></h2>

    <p class="sans"><?php echo $this->renderPosition('description'); ?></p>

</div>