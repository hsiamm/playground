<?php
defined('_JEXEC') or die('Restricted access');

$pageRef = $_SERVER['REQUEST_URI'];
?>

<!DOCTYPE html>
<head>

<jdoc:include type="head" />

<script type="text/javascript" src="http://use.typekit.com/itk0lox.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/styles.css" type="text/css" media="screen" />

<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script>
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
    $(document).ready(function(){
        var shown = false;
        $("#search").click(function () {
            if (shown){
                $("#searchText").hide('fast');
            }
            else {
                $("#searchText").toggle('slide');
            }
            shown = !shown;
        });
    });
</script> 
<script>
    $(document).ready(function(){
        var down = false;
        var first = true;
        $("#map_toggle").click(function () {
            if (down){
                $(this).text("Hide Worship Times & Locations");
                $("#map_canvas").slideUp('slow');
            }
            else {
                $(this).text("Show Worship Times & Locations");
                $("#map_canvas").slideDown('slow', function() {
                    if (first)
                        initialize();
                    pan();
                });
            }
            down = !down;
        });
    });
</script>

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
                <form method="post" action="/">
                    <input style="float:right; height:10px; padding:0 10px; display:none;" id="searchText" type="text" name="keywords" maxlength="100" placeholder="Search" />
                </form>
                <span style="float:right; border-right: 1px solid #fff;padding:0 10px;"><a href="/new-here">New Here</a></span>
                <span style="float:right; border-right: 1px solid #fff;padding-right:10px;">Give</span></h5>
            </div><!--/top_right-->
        </div>

    </div> <!-- /top -->

    <div style="clear:both;"></div>

    <div id="map_canvas"></div>

    <div class="container">
        <div class="bump">&nbsp;</div>
        <a href="<?php echo $this->baseurl ?>/" class="logo">
            The Austin Stone Community Church
        </a>

        <div id="menu">
            <jdoc:include type="modules" name="menu" />
        </div> <!-- /menu -->
    </div> <!-- /container -->

    <div style="clear:both;"></div>

    <?php // test if home page, if so, show image_main
    if ($pageRef == '/') { ?>
        <div class="image_main">
            <div class="container">
                <jdoc:include type="modules" name="current_sermon" />

                <div style="clear:both;"></div>
                <jdoc:include type="modules" name="current_hap" />
            </div> <!-- /container -->
        </div> <!-- /image_main -->
    <?php } ?>

    <div class="bumpbump">&nbsp;</div>

    <div class="container">
        <jdoc:include type="component" />
    </div> <?php // End component                                                                   ?>

    <div style="clear:both;"></div>
    <div class="bump">&nbsp;</div>
    <div class="bumpbump">&nbsp;</div>

    <div class="footer">
        <div class="twitter">
            <div class="container">
                <jdoc:include type="modules" name="twitter" />
            </div> <!-- /container -->
        </div> <!-- /twitter -->

        <div class="container">		
            <div class="bumpbump">&nbsp;</div>	
            <jdoc:include type="modules" name="follow_us" />

            <jdoc:include type="modules" name="stay_connected" />			

            <jdoc:include type="modules" name="office" />				

            <jdoc:include type="modules" name="footer_menu" />

            <jdoc:include type="modules" name="get_in_touch" />

            <div style="clear:both;"></div>			

            <div class="texas_flag"></div>
            <div class="texas">Texas is awesome</div>

        </div> <!-- /container -->
        <div class="bump">&nbsp;</div>
    </div> <!-- /footer -->        

</body>
</html>