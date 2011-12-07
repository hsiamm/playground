<?php
defined('_JEXEC') or die('Restricted access');

$pageRef = $_SERVER['REQUEST_URI'];
?>

<!DOCTYPE html>
<head>

<jdoc:include type="head" />

<meta property="og:image" content="http://austinstone.org/images/austin_stone.jpg"/> 
<script type="text/javascript" src="http://use.typekit.com/itk0lox.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/styles.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/fancybox.css" type="text/css" media="screen" />


<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/jquery.fancybox-1.3.4.pack.js"></script>
<script>
    // show Texas flag
    $(document).ready(function() {
        var flag = false;
        $(".texas").click(function() {
            if (flag)
                $(".texas_flag").slideUp("slow");
            else
                $(".texas_flag").slideDown("slow");
            flag = !flag;
        });
    });
</script>
<script>
    // Show search bar
    $(document).ready(function(){
        var shown = false;
        $("#search").click(function () {
            if (shown)
                $("#searchText").hide('fast');
            else 
                $("#searchText").toggle('slide');
            shown = !shown;
        });
    });
</script> 
<script>
    // Show map
    $(document).ready(function(){
        var down = false;
        var first = true;
        $("#map_toggle").click(function () {
            if (down){
                $(this).text("Show Worship Times & Locations");
                $("#map_div").slideUp('slow', function() {
                    $(".map_info").hide();
                    $("#map_canvas").hide();
                });
            }
            else {
                $(this).text("Hide Worship Times & Locations");
                $("#map_div").slideDown('slow', function() {
                    // if opening for first time, initialize the map
                    if (first)
                        initialize();
                    first = false;
                });
                $("#map_canvas").show();
                $(".map_info").show();
            }
            down = !down;
        });
    });
</script>
<script>
    // Make the top level navigation stay highlighted when in sub-menu
    $(document).ready(function(){
        var listItem = document.getElementById("menunav").getElementsByTagName('ul');
        for(var i=0;i<listItem.length;i++) {
            listItem[i].onmouseover=function() {
                var changeStyle = this.parentNode.getElementsByTagName('a');
                $(changeStyle[0]).addClass('current');
            }

            listItem[i].onmouseout=function() {
                var changeStyle = this.parentNode.getElementsByTagName('a');
                $(changeStyle[0]).removeClass('current');
            }
        }  
    });
</script>

<!-- Anything Slider -->
<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/anythingslider.css">
<script src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/jquery.anythingSlider.js"></script>
<script src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/jquery.anythingSlider.fx.js"></script>
<script>
    $(function () {
        $('#slider1').anythingSlider({
            expand : true,
            autoPlay : true,
            startStopped : false,
            pauseOnHover : false,
            buildNavigation : false,
            buildStartStop : false,
            delay : 3000, /* milliseconds */
            animationTime : 600, /* milliseconds transition */
            appendForwardTo: $('#forward'),
            appendBackTo: $('#back')
        });
    });
</script>

<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-3493264-14']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>

<!--[if IE]>
<style>
.drop {
margin-top: -4px;
}
</style>
<![EndIf]-->

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/map.js"></script>

</head>

<body>

    <div class="top">

        <div class="container">
            <div class="map_drop">
                <h5 id="map_toggle">Show Worship Times &amp; Locations</h5>
            </div><!--/map_drop-->

            <div class="top_right" style="width:350px;">
                <h5><span id="search" style="float:right; padding-left:10px;">Search</span>
                    <jdoc:include type="modules" name="search" />
                    <a class="new_here_top" href="/connect/new-here">New Here</a>
                    <a class="give_top" href="/give">Give</a></h5> 
            </div><!--/top_right-->
        </div>

    </div> <!-- /top -->

    <div style="clear:both;"></div>

    <div id="map_div">
        <div id="map_canvas"></div>
        <div style="clear:both;"></div>
        <div class="map_info">
            <div class="container">
                <h2 class="white">Our Services:</h2>
                <h2 class="white left_rule">Downtown Campus<br>
				9:00&nbsp;&nbsp;11:15&nbsp;&nbsp;5:00&nbsp;7:00</h2>

                <h2 class="white left_rule">St. John Campus<br>
				9:15&nbsp;&nbsp;11:15&nbsp;&nbsp;5:00&nbsp;7:00</h2>
                <h2 class="white left_rule">West Campus<br>
				10:00</h2>

                <div class="map_button">
                    <a href="/about/times-locations"><p class="caption caps">More Details</p></a>
                </div>
            </div><!-- /container -->
        </div>
    </div>
    <div style="clear:both;"></div>

    <div class="container">
        <div class="bump">&nbsp;</div>		
        <div class="logo">
            <a href="<?php echo $this->baseurl ?>">The Austin Stone Community Church</a>
        </div>

        <div id="menu">
            <jdoc:include type="modules" name="menu" />
        </div> <!-- /menu -->
    </div> <!-- /container -->

    <div style="clear:both;"></div>

    <?php // test if home page, if so, show image_main
    if ($pageRef == '/') { ?>
        <div class="image_main">

            <jdoc:include type="modules" name="home_page_slider" />

            <div class="container">
                <div class="home_modules">
                    <jdoc:include type="modules" name="current_sermon" />

                    <div style="clear:both;"></div>
                    <jdoc:include type="modules" name="current_hap" />

                    <div style="clear:both;"></div>
                </div>
                <div class="slider_controls">
                    <div id="forward"></div>
                    <div id="back"></div>
                </div>
            </div> <!-- /container -->

        </div> <!-- /image_main -->

        <div class="bumpbump">&nbsp;</div>
    <?php } else { ?>
        <div class="bump">&nbsp;</div>
    <?php } ?>

    <div class="container">
        <jdoc:include type="component" />
    </div> <!-- /container -->

    <div style="clear:both;"></div>
    <div class="bump">&nbsp;</div>
    <div class="bumpbump">&nbsp;</div>

    <div class="footer">
        <div class="twitter">
            <div class="container">
                <div class="onecol">
                    <jdoc:include type="modules" name="twitter" />
                </div>
            </div> <!-- /container -->
        </div> <!-- /twitter -->

        <div class="container">
            <div class="bumpbump"></div>
            <div class="sixcol">
                <h2 class="blue">Follow Us</h2>
                <h4>We frequently share information online. Here's where you can find us.</h4>
                <a href="http://twitter.com/#!/theaustinstone"><img src="images/twitter.png" alt="Twitter" /></a> 
                <a href="http://www.facebook.com/theaustinstone"><img src="images/fb.png" alt="Facebook" /></a> 
                <a href="http://vimeo.com/theaustinstone"><img src="images/vimeo.png" alt="Vimeo" /></a> 
                <a href="http://www.youtube.com/user/theaustinstonechurch"><img src="images/youtube.png" alt="YouTube" /></a></div>
            <div class="sixcol">
                <h2 class="blue">Stay Connected</h2>
                <p class="sans"><em>The City</em> is the central communication tool for our church. <a href="https://theaustinstone.wufoo.com/forms/connect-me-to-the-city/">Join today</a>.</p>
                <p class="sans"><a href="https://austinstone.onthecity.org/"><em>The City</em></a> &nbsp;|&nbsp;<a href="http://austinstone.onthecity.org/plaza"><em>The City</em> Plaza</a></p>
            </div>				

            <div class="sixcol">
                <h2 class="blue">Office</h2>
                <p class="sans">1033 La Posada Dr. #210<br /> Austin, Tx 78752<br /> 512.708.8860</p>
                <p class="sans"><a href="mailto:info@austinstone.org">info@austinstone.org</a></p>
            </div>
            <div class="sixcol" style="height: 100px;"></div>
            <div class="threecol">
                <h2 class="blue">Go Here</h2>
            </div>
            <div class="sixcol">
                <h4><a href="/connect/new-here">I'M NEW HERE</a> <img src="images/arrow_y.png" alt="Go" /></h4>
                <h4><a href="https://theaustinstone.wufoo.com/forms/get-connected-to-missional-community/">JOIN A GROUP</a> <img src="images/arrow_y.png" alt="Go" /></h4>
                <h4><a href="/about/contact">CONTACT US</a> <img src="images/arrow_y.png" alt="Go" /></h4>
            </div>
            <div class="sixcol">
                <h4><a href="/connect/current-happenings">CURRENT HAPPENINGS</a> <img src="images/arrow_y.png" alt="Go" /></h4>
                <h4><a href="/resources/sermons">OUR SERMON ARCHIVE</a> <img src="images/arrow_y.png" alt="Go" /></h4>
                <h4><a href="/about/times-locations">OUR CAMPUSES /<br />WORSHIP TIMES</a> <img src="images/arrow_y.png" alt="Go" /></h4>
            </div>
            <div style="clear: both;"></div>
            <div class="texas_flag"></div>
            <div class="texas">Texas is awesome</div>
        </div>
        <!--/container-->
        <div class="bump">&nbsp;</div>
    </div> <!-- /footer -->        

</body>
</html>