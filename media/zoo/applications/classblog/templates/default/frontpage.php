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

<script type="text/javascript">
    $(document).ready(function() {
        $("#classes_commitment").fancybox({
            'titlePosition'		: 'inside',
            'transitionIn'		: 'none',
            'transitionOut'		: 'none',
            'overlayColor'		: '#000',
            'overlayOpacity'	: 0.80			
        });
        $("#classes_categories").fancybox({
            'titlePosition'		: 'inside',
            'transitionIn'		: 'none',
            'transitionOut'		: 'none',
            'overlayColor'		: '#000',
            'overlayOpacity'	: 0.75		
        });		
    });
</script>
<script>
    $(function(){
        $('.class_details_button').click(function(){
            var obj = $(this).parents('.class');
            if ($(obj).hasClass('expanded')) {
                $(obj).removeClass('expanded');
                $('.class_details',obj).slideUp('slow');					
                $(this).text('More Details');
            } else {
                $(obj).addClass('expanded');
                $('.class_details',obj).slideDown('slow');
                $(this).text('Less Details');
            }
            return false;
        });
        
        $("#all").click(function(){
            $(".class_sort_options").hide();
            $(".class").fadeIn('slow');
            $(this).addClass("currentFilter");
            return false;
        });
        
        $("#allLoc").click(function(){
            $(".class_sort_options").hide();
            $(".class").fadeIn('slow');
            $(this).addClass("currentFilter");
            return false;
        });

        $(".filter").click(function(){
            $(".class_sort_options").hide();
            var thisFilter = $(this).attr("id");
            $(".class").fadeOut();
            $("."+ thisFilter).fadeIn('slow');
            $(this).addClass("currentFilter");
            return false;
        });
        
        $(".class_sort_button").click(function(){
            var id = $(this).attr("id");
            $("#" + id + "_filter").slideToggle("slow");
        });
    });
</script>

<div class="onecol">

    <h1>Classes</h1>
</div>

<div class="fivecol_2">
    <p>Each semester The Austin Stone offers a wide spectrum of classes, each one intended to develop a proper view of God, a fuller heart for the Gospel, and the ability to apply both of those things practically in life through Mission.</p>
    <p>To register for a class, click on the section info for the class you're interested in. Online payment is required to hold your space in a class. If you don't complete the registration, your spot will not be reserved. Classes are closed to new registrants after their start date.</p>
</div>

<div class="fivecol_2"> 
    <p>Our classes fill up fast and we want to hold spots for those who are able to commit. The last day for refunds will be <strong>February 2</strong>.</p>
    <p>Don't let course fees prevent you from registering. <br> <a href="https://theaustinstone.wufoo.com/forms/gt-get-trained-class-scholarship/">Apply for a needs-based scholarship</a>.</p>
</div>

<div style="clear:both;">&nbsp;</div>



<div class="class_sort">
    <div class="class_popout_wrapper">
        <div class="class_popout" id="classes_commitment" href="#inline_commit">
            <h4 class="yellow caps">Class Commitments</h4>
        </div>
        <!-- FANCYBOX1-->
        <div style="display: none;">
            <div id="inline_commit" style="width:440px;height:315px;overflow:auto;">

                <div class="commitments">
                    <h1 class="title">Class Commitments:</h1>
                    <p>Because our classes rely heavily on discussion and community, we ask participants to commit to the following before signing up. Space is limited, so please do not take a spot in a class if you cannot:</p>
                    <ul class="bullet">
                        <li>Attend the first class of the semester</li>
                        <li>Attend at least 80% of the class meetings. For most classes this will mean missing no more than one class.</li>
                        <li>Each class may have additional requirements to enable you to get the most out of the semester.</li>
                    </ul>
                    <p>If you have any questions about these requirements, please email us: <a href="mailto:gettrained@austinstone.org">gettrained@austinstone.org</a></p>
                </div>


            </div>
        </div>
        <!-- /FANCYBOX1-->        

        <div class="class_popout" id="classes_categories" href="#inline_category">
            <h4 class="yellow caps">Class Categories</h4>
        </div>        
        <!-- FANCYBOX2-->
        <div style="display: none;">
            <div id="inline_category" style="width:645px;height:525px;overflow:auto;">

                <div class="class_categories">
                    <h1 class="title">Class Categories</h1>
                    <ul>
                        <li>
                            <img src="images/class_cat_bible.png" style="float:left;margin:0 10px 45px 0;" />
                            <h2 class="nomar blue">Bible</h2>
                            <p>Classes built around specific books, sections, or major themes of the Bible. In addition to developing a greater understanding of the material at hand, these classes provide throning in how to study the Bible.</p>
                        </li>
                        <div style="clear:both;"></div>
                        <li>
                            <img src="images/class_cat_xian_life.png" style="float:left;margin:0 10px 45px 0;" />
                            <h2 class="nomar blue">Christian Life &amp; Practice</h2>
                            <p>Classes focused on spiritual formation. Christian Life &amp; Practice classes focus on developing disciplines and community towards becoming better apprentices &ndash; foolwers, learners, doers &ndash; of Jesus.</p>
                        </li>
                        <div style="clear:both;"></div>
                        <li>
                            <img src="images/class_cat_connect.png" style="float:left;margin:0 10px 60px 0;" />
                            <h2 class="nomar blue">Get Connected</h2>
                            <p>Classes designed to help you get connected at The Austin Stone. Most of these classes include a small group component with group meetings outside of class to facilitate community and mission, with many groups continuing after the class semester.</p>
                        </li>
                        <div style="clear:both;"></div>
                        <li>
                            <img src="images/class_cat_theology.png" style="float:left;margin:0 10px 45px 0;" />
                            <h2 class="nomar blue">Theology</h2>
                            <p>Classes that walk through core Christian beliefs with an emphasis on how theology should inform our choices and pursuits in everyday life.</p>
                        </li>
                        <div style="clear:both;"></div>
                        <li>
                            <img src="images/class_cat_lifestage.png" style="float:left;margin:0 10px 45px 0;" />
                            <h2 class="nomar blue">Stage of Life</h2>
                            <p>Classes designed to help you engage with the specific opportunities and challenges of various stages of life while providing a community of peers to learn and grow alongside.</p>
                        </li>
                        <div style="clear:both;"></div>
                        <li>
                            <img src="images/class_cat_skills.png" style="float:left;margin:0 10px 60px 0;" />
                            <h2 class="nomar blue">Skills Training</h2>
                            <p>Classes that provide training in living on mission in specific environments or contexts.</p>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
        <!-- /FANCYBOX2-->          
    </div>

    <h2 style="float:left;margin-right:15px;">Filter Schedule</h2>

    <div class="class_sort_category">
        <a id="category" class="class_sort_button"><h2 class="nomar">By Category</h2></a>
        <ul id="category_filter" class="class_sort_options">
            <a id="all" class="currentFilter"><li>All</li></a>
            <a id="bible" class="filter"><li>Bible</li></a>
            <a id="christianlife" class="filter"><li>Christian Life & Practice</li></a>
            <a id="connect" class="filter"><li>Get Connected</li></a>
            <a id="theology" class="filter"><li>Theology</li></a>
            <a id="stageoflife" class="filter"><li>Stage of Life</li></a>
            <a id="skills" class="filter"><li>Skills Training</li></a>
        </ul>
    </div>

    <div style="float:left;width:15px;height:auto;margin:15px 6px 0 6px;"><h3>or</h3></div>

    <div class="class_sort_location">
        <a id="location" class="class_sort_button"><h2 class="nomar">By Campus</h2></a>
        <ul id="location_filter" class="class_sort_options">
            <a id="allLoc" class="currentFilter"><li>All</li></a>
            <a id="downtown" class="filter"><li>Downtown</li></a>
            <a id="stjohn" class="filter"><li>St. John</li></a>
            <a id="west" class="filter"><li>West</li></a>
        </ul>
    </div>
</div>

<div class="bump"></div>
<div class="onecol">
    <blockquote>
        Below is a preview of Spring 2012 classes. Registration will open in early 2012.
    </blockquote>
</div>
<div class="clear:both;">&nbsp;</div>

<?php

// render items
if (count($this->items)) {
    echo $this->partial('items');
}
?>