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
    $(document).ready(function() {
        $(".fancy_pop").fancybox({
            'titlePosition'		: 'inside',
            'transitionIn'		: 'none',
            'transitionOut'		: 'none',
            'overlayColor'		: '#000',
            'overlayOpacity'	: 0.80			
        });
    });
    
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
    <p>Jesus said, &ldquo;As the Father has sent me, so I send you&rdquo; (John 20:21). Throughout this year and next you will have incredible opportunities to join God on mission among the nations. There are several ways to participate with us. You can help make others aware of the opportunities to serve; you can support those who are going through prayer or financially; or you can apply to go with one of the teams.</p>

    <p>Teams that go will participate in several types of ministry: education, evangelism, medical, exposure, and construction. No matter which role you participate in, our ultimate goal is to develop disciples of Jesus Christ who share His heart and vision for the entire world.</p>

<div class="twocol_trips"> 
    <h4 class="yellow nomar caps">A note about trip costs</h4>
    <p class="white">While international travel is expensive, don't get overwhelmed by the overall cost of the trip you're interested in. A major part of the pre-trip preparation involves raising funds for your trip and we'll teach you how. This process is a major opportunity to rely on God while allowing those who can't physically go to share in your time abroad.</p>
</div><!--/twocol_trips-->
</div>
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
            play: 4000,
            pause: 500,
            slideSpeed: 600,
            hoverPause: false,
            effect: 'slide',
            randomize: true,
            generateNextPrev: false,
            generatePagination: false
        });
    });
</script>
<!--TRIPS FEATURED -->
<div class="trips_featured">
    <div id="slider">
        <div id="slides">
            <div class="slides_container">
                
				<div class="slide">
				    <div class="trips_featured_content">
				       <img src="images/trips_slider/slide_trips1.jpg" alt="India"/> 
				    </div>
				</div><!--/slide-->
				<div class="slide">
				    <div class="trips_featured_content">
				       <img src="images/trips_slider/slide_trips2.jpg" alt="India"/> 
				    </div>
				</div><!--/slide-->
				<div class="slide">
				    <div class="trips_featured_content">
				       <img src="images/trips_slider/slide_trips3.jpg" alt="Ethiopia"/> 
				    </div>
				</div><!--/slide-->
				<div class="slide">
				    <div class="trips_featured_content">
				       <img src="images/trips_slider/slide_trips4.jpg" alt="Ethiopia"/> 
				    </div>
				</div><!--/slide-->
				<div class="slide">
				    <div class="trips_featured_content">
				       <img src="images/trips_slider/slide_trips5.jpg" alt="Cairo"/> 
				    </div>
				</div><!--/slide-->
				<div class="slide">
				    <div class="trips_featured_content">
				       <img src="images/trips_slider/slide_trips6.jpg" alt="Cairo"/> 
				    </div>
				</div><!--/slide-->												
            </div><!--/slides_container-->
            <a href="#" class="prev"></a>
            <a href="#" class="next"></a>
        </div><!--/slides-->
        <div style="clear:both;"></div>
    </div><!--/slider-->
</div><!--/TRIPS FEATURED --> 

<div style="clear:both;"></div>
<div class="bumpbump">&nbsp;</div>

<div class="trips_sort">
    <div class="trips_popout_wrapper">
        <div class="trips_pop fancy_pop" id="trips_commitment" href="#inline_commitments">
            <h4 class="yellow caps">Trip Commitments</h4>
        </div>
        <div style="display: none;">
            <div id="inline_commitments" style="width:615px;height:auto;overflow:auto;">
                <div class="trip_commitments">
                    <h1 class="title">Trip Commitments:</h1>
                    <p>Our desire is to be good stewards, financially and spiritually, of the resources that God has given us. We also strive to prepare our teams to show the unity in Christ and a heart of service to those we will meet.</p>
                    <p>To help us achieve this, we ask each team member to commit to:</p>
                    <ul class="bullet">

                        <li>Actively participate in providing the funds for your trip, either through personal means or fundraising activities. If the full amount of funds is not raised before the trip, the team member agrees to pay the remaining balance within 30 days of returning from the trip</li>
                        <li>Attend the first training meeting and attend at least 80% of subsequent training meetings and debrief sessions</li>
                        <li>Submit to the authority of the team leader(s), to serve with a spirit of humility, and to strive for biblical unity with your team and our national partners.</li>
                        <li>Consistently seek to abide in Christ through daily time in the scriptures and prayer leading up to, during, and after the trip</li>
                    </ul>
                    <p>If you have any questions about these requirements, please email us: <a href="mailto:makedisciples@austinstone.org">makedisciples@austinstone.org</a></p>
                </div>
            </div>
        </div>
        <div class="trips_pop fancy_pop" id="trips_types" href="#inline_types">
            <h4 class="yellow caps">Trip Types</h4>
        </div>
        <!-- Fancybox 2 -->
        <div style="display: none;">
            <div id="inline_types" style="width:645px;height:auto;overflow:auto;">
                <div class="trips_types">
                    <h1 class="title">Trips Types</h1>
                    <ul>
                        <li>
                            <img src="images/trips_types_construction.png" style="float:left;margin:0 10px 45px 0;" />
                            <h2 class="nomar blue">Construction</h2>

                            <p>Team members will engage in physical labor to help with various projects in cooperation with national partners. Examples include building latrines, homes, digging trenches, painting, etc.</p>
                        </li>
                        <div style="clear:both;"></div>
                        <li>
                            <img src="images/trips_types_education.png" style="float:left;margin:0 10px 45px 0;" />
                            <h2 class="nomar blue">Education</h2>
                            <p>Team members will participate in a variety of teaching environments. Opportunities vary from grade school environments to basic lessons for adults. Topics range from Bible lessons to basic health and language lessons.</p>

                        </li>
                        <div style="clear:both;"></div>
                        <li>
                            <img src="images/trips_types_evangelism.png" style="float:left;margin:0 10px 60px 0;" />
                            <h2 class="nomar blue">Evangelism</h2>
                            <p>Team members will participate in partnership with the local church (when local churches exist) in sharing the good news of the gospel in word through a variety of means, which may include home visits, speaking in public, or personal relationship development.</p>
                        </li>
                        <div style="clear:both;"></div>

                        <li>
                            <img src="images/trips_types_exposure.png" style="float:left;margin:0 10px 45px 0;" />
                            <h2 class="nomar blue">Exposure</h2>
                            <p>Team members will spend a significant amount of time listening to and learning about the culture and how the Lord is working among the people.</p>
                        </li>
                        <div style="clear:both;"></div>
                        <li>
                            <img src="images/trips_types_medical.png" style="float:left;margin:0 10px 45px 0;" />

                            <h2 class="nomar blue">Medical</h2>
                            <p>Team members have the opportunity to serve in clinics and other settings to meet the medical needs of many while praying for them and taking advantage of opportunities to share the good news of the gospel. *In most cases, basic medical training will be provided during team training..</p>
                        </li>
                    </ul>
                </div>
            </div>
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
            <a id="central-asia" class="filter"><li>Central Asia</li></a>
            <a id="dominican" class="filter"><li>Dominican Republic</li></a>
            <a id="haiti" class="filter"><li>Haiti</li></a>
            <a id="horn-africa" class="filter"><li>Horn of Africa</li></a>
            <a id="india" class="filter"><li>India</li></a>
            <a id="north-africa" class="filter"><li>North Africa</li></a>
            <a id="north-america" class="filter"><li>North America</li></a>
            <a id="europe" class="filter"><li>Europe</li></a>
            <a id="west-africa" class="filter"><li>West Africa</li></a>
            <a id="central-africa" class="filter"><li>Central Africa</li></a>
            <a id="asia" class="filter"><li>Asia</li></a>
        </ul>
    </div>

</div>

<?php

// render items
if (count($this->items)) {
    echo $this->partial('items');
}
?>