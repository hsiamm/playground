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

<!-- SLIDER -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script src="/templates/ascctemplate/js/slides.min.jquery.js"></script>

<script>
    $(function(){
        // Set starting slide to 1
        var startSlide = 1;
        // Get slide number if it exists
        if (window.location.hash) {
            startSlide = window.location.hash.replace('#','');
        }
        // Initialize Slides
        $('#slides').slides({
            preload: true,
            preloadImage: 'images/loading.gif',
            generatePagination: false,
            play: 30000,
            pause: 2500,
            hoverPause: true,
            generateNextPrev: false,
            generatePagination: true
        });
    });
</script>
<!-- END SLIDER -->

<!-- SERVICE FEATURED -->
<div class="serve_featured">

    <h3 class="black">Featured Service Opportunities</h3>

    <div id="slider">
        <div id="slides">
            <div class="slides_container">
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
            <a href="#" class="prev"></a>
            <a href="#" class="next"></a>
        </div>
        <div style="clear:both;"></div>
    </div>

</div><!-- END SERVICE FEATURED -->