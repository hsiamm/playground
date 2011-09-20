<?php
/**
 * @package   com_zoo Component
 * @file      _items.php
 * @version   2.4.10 June 2011
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

// init vars
$i = 0;
$columns = array();
$column = 0;
$row = 0;
$rows = ceil(count($this->items) / $this->params->get('template.items_cols'));
?>

<div id="sermon_scroll_one"><!-- page stops scrolling here -->
    <div class="twocol">
        <?php
        // render pagination
        echo $this->partial('pagination');
        ?>
    </div>

</div>

<div id="sermon_column">
    <div class="twocol">
        <?php
        // create columns
        foreach ($this->items as $item) {
            echo $this->partial('item', compact('item'));
        }
        ?>

    </div>
    <div style="clear:both;"></div>
    <div class="twocol" style="margin-top: 25px;">
        <?php
        // render pagination
        echo $this->partial('pagination');
        ?>
    </div>
</div>