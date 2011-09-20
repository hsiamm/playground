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

<script>
    // show more details for service opp
    $(document).ready(function() {
        $('.expand .show').click(function() {
            var parent = $(this).parents('.expand');
            if ($(parent).hasClass('expanded')) {
                $(parent).removeClass('expanded');
                $('.serve_opp_details',parent).slideUp('slow');					
                $(this).text('Show Details');
            } else {
                $(parent).addClass('expanded');
                $('.serve_opp_details',parent).slideDown('slow');
                $(this).text('Hide Details');
            }
            return false;
        });
    });
</script>

<!-- SERVICE ALL -->
<?php
if (!empty($items)) {
    $i = 0;
    foreach ($items as $item) {
        echo $renderer->render('item.' . $layout, compact('item', 'params'));
        $i++;
    }
}
?>	
<!-- END SERVICE ALL -->