<!DOCTYPE html>
<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title>The Austin Stone Community Church</title>

        <link rel="icon" href="" type="image/x-icon" />
        <link rel="shortcut icon" href="" type="image/x-icon" />

        <link rel="stylesheet" type="text/css" href="http://dev.austinstone.org/design/css/style.css" media="screen">
        <link rel="stylesheet" type="text/css" href="http://dev.austinstone.org/design/css/sermon_style.css" media="screen">
        <link rel="stylesheet" type="text/css" href="http://dev.austinstone.org/design/css/current_style.css" media="screen">
        <link rel="stylesheet" type="text/css" href="http://dev.austinstone.org/design/css/map_style.css" media="screen">
        <link rel="stylesheet" type="text/css" href="http://dev.austinstone.org/design/css/classes_style.css" media="screen">
        <link rel="stylesheet" type="text/css" href="http://dev.austinstone.org/design/css/related_org_style.css" media="screen">
        <link rel="stylesheet" type="text/css" href="http://dev.austinstone.org/design/css/new_here_style.css" media="screen">
                
		<script type="text/javascript" src="http://use.typekit.com/itk0lox.js"></script>
		<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
        
        <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
		<script>
			$(function(){
				$('.expand .show').click(function(){
					var obj = $(this).parents('.expand');
					if ($(obj).hasClass('expanded')) {
						$(obj).removeClass('expanded');
						$('.content',obj).slideUp('slow');					
						$(this).text('Parking Map +');
					} else {
						$(obj).addClass('expanded');
						$('.content',obj).slideDown('slow');
						$(this).text('Hide Map -');
					}
					return false;
				});
			    $(document).ready(function() {
			        var flag = false;
			        $(".parking").click(function() {
			            if (flag)
			                $(".parking_drop").slideUp("slow");
			            else
			                $(".parking_drop").slideDown("slow");
			            flag = !flag;
			    });				
			});
		</script> 
		
		 <!-- SUB NAV SCROLL STOPPER -->

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
        <script>
            $(function () {
  
                var msie6 = $.browser == 'msie' && $.browser.version < 7;
  
                if (!msie6) {
                    var top = $('#sermon_scroll_one').offset().top - parseFloat($('#sermon_scroll_one').css('margin-top').replace(/auto/, 0));
                    $(window).scroll(function (event) {
                        // what the y position of the scroll is
                        var y = $(this).scrollTop();
      
                        // whether that's below the form
                        if (y >= top) {
                            // if so, ad the fixed class
                            $('#sermon_scroll_one').addClass('fixed');
                        } else {
                            // otherwise remove it
                            $('#sermon_scroll_one').removeClass('fixed');
                        }
                    });
                }  
            });
        </script>
        
        <script>
            $(function () {
  
                var msie6 = $.browser == 'msie' && $.browser.version < 7;
  
                if (!msie6) {
                    var top = $('#sermon_scroll_two').offset().top - parseFloat($('#sermon_scroll_two').css('margin-top').replace(/auto/, 0));
                    $(window).scroll(function (event) {
                        // what the y position of the scroll is
                        var y = $(this).scrollTop();
      
                        // whether that's below the form
                        if (y >= top) {
                            // if so, ad the fixed class
                            $('#sermon_scroll_two').addClass('fixed');
                        } else {
                            // otherwise remove it
                            $('#sermon_scroll_two').removeClass('fixed');
                        }
                    });
                }  
            });
        </script>

        <!-- END SUB NAV SCROLL STOPPER -->
        
        <!-- SLIDER -->

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
        <script src="/js/slides.min.jquery.js"></script>

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
        
        <!--bg -->
			<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
			<script src="./js/jquery.ez-bg-resize.js" type="text/javascript" charset="utf-8"></script>
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