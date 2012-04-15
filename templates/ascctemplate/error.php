<!DOCTYPE html>
<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title>The Austin Stone Community Church</title>

        <link rel="icon" href="" type="image/x-icon" />
        <link rel="shortcut icon" href="" type="image/x-icon" />

        <script type="text/javascript" src="http://use.typekit.com/pto8iwc.js"></script>
        <script type="text/javascript">try{Typekit.load();}catch(e){}</script>

        <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/styles.css" type="text/css" media="screen" />
        <!--bg -->
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/jquery.ez-bg-resize.js"></script>
        <script>
            $(document).ready(function() {
                $("#body-background").ezBgResize();
            });
		
            $(window).bind("resize", function(){
                $("#body-background").ezBgResize();
            });
        </script> 
        <!-- end bg-->
    </head>

    <body style="padding:20px 0;">


        <div class="container">	
            <div class="onecol">
                <h2 class="jumbo" style="color:#000;font-size:150px;line-height:135px;margin-top:50px;">ERRR&hellip; SEEMS LIKE YOU'RE LOST</h2>
            </div>
        </div>

        <div style="width:100%;background:rgb(255, 255, 255);background:rgba(255, 255, 255, .80);width:100%;height:30px;clear:both;margin:20px 0 40px 0;">
            <div class="container">
                <h2 class="nocap" style="text-align:center;color:#000;margin:20px auto;line-height:30px;">Never fear! These popular links might help you get where you're going.</h2>
            </div>
        </div>

        <div class="container">
            <div class="fourcol">
                <a href="/connect/new-here" class="hot_left"><h2>I'm New Here</h2></a>
            </div>
            <div class="fourcol">
                <a href="/connect/current-happenings" class="hot_left"><h2>Current Happenings</h2></a>
            </div>
            <div class="fourcol">
                <a href="/campuses/campuses-main" class="hot_left"><h2>Our Campuses</h2></a>
            </div>
            <div class="fourcol">
                <a href="/resources/sermons" class="hot_left"><h2>Our Sermon Archive</h2></a>
            </div>

            <div class="onecol">
                <div style="margin:30px 0;position:absolute;bottom:0;left:50%;margin-left:-141px;width:282px;height:57px;">
                    <a href="/"><img src="/images/logo_wh.png" /></a>
                </div>
            </div>
        </div>

        <div id="body-background"><img src="/images/big_bend_404.jpg" width="1920" height="1200"></div>

    </body> 
</html>