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

<!-- CURRENT HAPPENINGS -->
<div class="twocol">
    <h1>Current Happenings</h1>
    <p class="nomar">Short ribs tri-tip swine tail meatloaf, cow corned beef spare ribs. Turkey cow ball tip beef ribs ham hock spare ribs rump, beef.</p>

    <div class="current_item">
        <?php
        if (!empty($items)) {
            $i = 0;
            foreach ($items as $item) {
                echo $renderer->render('item.' . $layout, compact('item', 'params'));
                $i++;
            }
        }
        ?>
    </div>

</div><!-- END CURRENT HAPPENINGS -->