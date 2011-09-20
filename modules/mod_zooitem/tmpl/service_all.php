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
        $(".serve_opp h2").click(function() {
            var id = $(this).attr('id');
            $("hidden_" + id).slideToggle("slow");
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