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
<script>
    $(function(){
        $("#all").click(function(){
            $(".trips_sort_options").hide();
            $(".trips_item").fadeIn('slow');
            $(this).addClass("currentFilter");
            return false;
        });
        
        $("#allLoc").click(function(){
            $(".trips_sort_options").hide();
            $(".trips_item").fadeIn('slow');
            $(this).addClass("currentFilter");
            return false;
        });

        $(".filter").click(function(){
            $(".trips_sort_options").hide();
            var thisFilter = $(this).attr("id");
            $(".trips_item").fadeOut();
            $("."+ thisFilter).fadeIn('slow');
            $(this).addClass("currentFilter");
            return false;
        });
        
        $(".trips_sort_button").click(function(){
            var id = $(this).attr("id");
            console.log(id);
            $("#" + id + "_filter").slideToggle("slow");
        }); 
    });
</script>

<div class="onecol">
    <h1>Trips</h1>
</div>

<div class="twocol">
    <p>Jesus said, &ldquo;As the Father has sent me, so I send you&rdquo; (John 20:21). During the summer of 2011 you will have incredible opportunities to join God on mission among the nations. There are several ways to participate with us. You can help make others aware of the opportunities to serve; you can support those who are going through prayer or financially; or you can apply to go with one of the teams.</p>

    <p>Teams that go will participate in several types of ministry: education, evangelism, medical, exposure, and construction. No matter which role you participate in, our ultimate goal is to develop disciples of Jesus Christ who share His heart and vision for the entire world. <a href="https://theaustinstone.wufoo.com/forms/short-term-trip-application/">Start the general trip application</a> <strong>(you only need to do this once, even if you're applying for multiple trips)</strong>.</p>
</div>
<div class="twocol_trips"> 
    <h4 class="yellow nomar caps">A note about trip costs</h4>
    <p class="white">While international travel is expensive, don't get overwhelmed by the overall cost of the trip you're interested in. A major part of the pre-trip preparation involves raising funds for your trip and we'll teach you how. This process is a major opportunity to rely on God while allowing those who can't physically go to share in your time abroad.</p>

</div>

<div class="trips_sort">
    <div class="trips_popout_wrapper">
        <div class="trips_commitments_pop">
            <h4 class="yellow caps">Trip Commitments</h4>
        </div>
        <div class="trips_types_pop">
            <h4 class="yellow caps">Trip Types</h4>

        </div>
    </div>

    <h2 style="float:left;margin-right:15px;">Filter Trips</h2>

    <div class="trips_sort_type">
        <a id="type" class="trips_sort_button"><h2 class="nomar">By Trip Type</h2></a>
        <ul id="type_filter" class="trips_sort_options">
            <a id="all" class="currentFilter"><li>All</li></a>
            <a id="construction" class="filter"><li>Construction</li></a>
            <a id="education" class="filter"><li>Education</li></a>
            <a id="evangelism" class="filter"><li>Evangelism</li></a>
            <a id="exposure" class="filter"><li>Exposure</li></a>
            <a id="medical" class="filter"><li>Medical</li></a>
        </ul>
    </div>
    <div style="float:left;width:15px;height:auto;margin:15px 6px 0 6px;"><h3>or</h3></div>
    <div class="trips_sort_location">
        <a id="location" class="trips_sort_button"><h2 class="nomar">By Trip Location</h2></a>
        <ul id="location_filter" class="trips_sort_options">
            <a id="allLoc" class="currentFilter"><li>All</li></a>
            <a id="asia" class="filter"><li>Central Asia</li></a>
            <a id="dominican" class="filter"><li>Dominican Republic</li></a>
            <a id="haiti" class="filter"><li>Haiti</li></a>
            <a id="horn-africa" class="filter"><li>Horn of Africa</li></a>
            <a id="india" class="filter"><li>India</li></a>
            <a id="north-africa" class="filter"><li>North Africa</li></a>
            <a id="north-america" class="filter"><li>North America</li></a>
        </ul>
    </div>

</div>

<?php

// render items
if (count($this->items)) {
    echo $this->partial('items');
}
?>