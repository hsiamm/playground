<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html>
<head>

<title>The Austin Stone Community Church</title>
<link rel="icon" type="image/png" href="/interface/ascc-fav-4.png" />
<link rel="alternate" type="application/rss+xml" title="RSS" href="http://feeds2.feedburner.com/TheAustinStone">
<link rel="stylesheet" href="/scripts/new.css" type="text/css" />

<script type="text/javascript" src="/scripts/jquery_ac.js"></script>
<script type="text/javascript" src="/scripts/animatedcollapse.js"></script>

<meta name="google-site-verification" content="wUWH2GYlqQxY3T13WO4uPy4CHKjRZQYEQK7XdXcMmS0" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>


<!-- give scripts -->


<!-- ac script -->
<script type="text/javascript">

animatedcollapse.addDiv('give1', 'fade=0,speed=300,hide=1')


animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
	//$: Access to jQuery
	//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
	//state: "block" or "none", depending on state
}

animatedcollapse.init()

</script>
<!-- end ac script -->


<!-- end give scripts -->



<script type="text/javascript">
		
		var offsetValue = 0;
 
 		function changeStories(amount) {
 			offsetValue += amount;
 			var data = new Object();
			data['offset'] = offsetValue;

			$('#loading-results').show();
			$("#recent-results").load("/latest_additions", data, function() { 
        		     // once the load is complete, hide indicator
        		     $('#loading-results').hide();
        		     if (offsetValue != 0) {$("#prev-show-hide").show();}
        		     else {$("#prev-show-hide").hide();}
        	        });
			return false;
 		}

$(document).ready(function() {

		
 
		$('#next-recent').click(function(){
			return changeStories(4);
		}); 
		
		$('#prev-recent').click(function(){
			return changeStories(-4);
		}); 
 });

</script> 


</head>

<body id="index">
<div id="frame">	



<!-- Begin Header -->

<div id="header">
	     <div id="headerLeft">
	     	<h1 id="logo" title="The Austin Stone Community Church"><a href="/">The Austin Stone Community Church</a></h1>
	     </div>
	       <div id="headerMenu">
	      	    <ul id="nav">
	          	<li id="nav_who" ><a href="http://old.austinstone.org/who/">Who We Are</a>
	          	<ul>
					<li><a href="http://old.austinstone.org/who/identity_and_beliefs/">Identity and Beliefs</a></li>
					<li><a href="http://old.austinstone.org/who/elders/">Elders</a></li>
					<li><a href="http://old.austinstone.org/who/staff/">Staff</a></li>
					<li><a href="http://old.austinstone.org/who/interns/">Residents and Interns</a></li>
					<li><a href="http://old.austinstone.org/institute/">Austin Stone Institute</a></li>
 					<!--<li><a href="http://old.austinstone.org/who/internship_board/"><span style="padding-left:10px">Internship Board</span></a></li>-->
					
					<li><a href="http://old.austinstone.org/who/giving/">Giving</a></li>
					
					<li><a href="http://old.austinstone.org/who/contact/">Contact Us</a></li>
				</ul>
	          	
	          </li>
	          	
	          	
	          	
	         <li id="nav_what" ><a href="http://old.austinstone.org/what/">What We Do</a>
	         <ul>
					<li><a href="http://old.austinstone.org/what/missional_communities/">Missional Community</a></li>
					<li><a href="http://old.austinstone.org/what/worship/">Worship</a></li>
					<li><a href="/what/care_counseling">Care & Counseling</a></li>
					<li><a href="http://old.austinstone.org/what/get_trained/">Get Trained</a></li>
					<li><a href="http://old.austinstone.org/what/make_disciples/">Make Disciples</a></li>
					<li><a href="http://old.austinstone.org/trips/"><span style="padding-left:10px">Trips</span></a></li>
					<li><a href="http://old.austinstone.org/college/">College</a></li>
					<li><a href="http://old.austinstone.org/what/next_generation/">Next Generation:</a></li>
					<li><a href="http://old.austinstone.org/what/teenrock/"><span style="padding-left:10px">TeenRock</span></a></li>
					<li><a href="http://old.austinstone.org/what/kidstuff/"><span style="padding-left:10px">KidStuff</span></a></li>
					<li><a href="http://old.austinstone.org/what/partnership/">Partnership</a></li>
				</ul>
	         
	         </li>
	         <li id="nav_going_on" ><a href="http://old.austinstone.org/current/">What's Going On</a>
	         <ul>
					<li><a href="http://old.austinstone.org/current/this_week/">This Week</a></li>
					<li><a href="http://old.austinstone.org/signups/">Sign-Ups</a></li>
					<li><a href="http://old.austinstone.org/serve/">Service Opportunities</a></li>
					<li><a href="http://old.austinstone.org/blog/">Blog</a></li>
				<!--	<li><a href="http://old.austinstone.org/current/email_updates/">Email Updates</a></li> -->
				</ul>
	         
	         </li>
	         <li id="nav_resources" ><a href="http://old.austinstone.org/resources/">Resources</a>
	         <ul>
					<li><a href="http://old.austinstone.org/resources/sermons/">Sermons</a></li>
					<li><a href="http://old.austinstone.org/resources/stories/">Stories</a></li>
					<li><a href="http://old.austinstone.org/resources/bible_reading_plan/">Bible Reading Plan</a></li>
					<li><a href="http://old.austinstone.org/resources/frequent_questions/">Frequent Questions</a></li>
					<li><a href="http://old.austinstone.org/resources/new_to_the_stone/">New to the Stone?</a></li>
					<li><a href="http://old.austinstone.org/resources/who_is_jesus/">Who is Jesus?</a></li>
				</ul>
	         
	         </li>


  <li id="nav_twitter" title="find us on Twitter"><a href="http://twitter.com/theaustinstone">Twitter</a></li>
 <!--[if gte IE 7]>
<li id="nav_facebook" title="connect with us on Facebook"><a href="http://www.facebook.com/theaustinstone">Facebook</a></li>
<![endif]-->


<![if !IE]>
<li id="nav_facebook" title="connect with us on Facebook"><a href="http://www.facebook.com/theaustinstone">Facebook</a></li>
<![endif]>

	           </ul>
	   	</div>
	       <div id="headerRight"></div>
</div>





	

<!-- End Header -->
<div id="center">

		<div id="homeMain" style="background:url(/images/home/sanjose.jpg) no-repeat;">
      <div style="position:relative;top:322px;left:595px;width:20px;height:16px;z-index:5;"><a href="http://www.toddwhite.org/" target="_blank" style="background-image:none" title="photo by Todd White"><img src="/interface/camera-icon-85.png" border="0"></a> </div>        


 <div id="newHere" class="action"><a href="/resources/new_to_the_stone/">I'm New Here</a></div>


<div class="homeBar"><span style="color:white"><strong> </strong></span>
<div id="homeBarSubtext" style="color:#ddd;margin-top:5px;margin-bottom:5px;width:400px;"><div style="background-color:#777;background: rgba(119, 119, 119, 0.8);padding: 5px 5px 2px 5px;line-height:1.2em;font-size:10px;"> &gt; Worship Services:
<a href="/current/this_week"  style="color: white ">Austin High School:
 
9am, 11:15am, 5pm, 7pm </a>
<span style="margin-left:117px;"><a href="/current/this_week" style="color: white">St. John Campus:
9:15am, 11:15am, 5pm, 7pm
</a>
</span>
<span style="margin-left:117px;"><a href="/current/this_week" style="color: white">West Campus:
10:00am
</a>
</span>


</div></div>
		
</div>
<div class="sermonHomeBar"> &gt; <a href="/resources/sermons">Listen to previous sermons</a></div>
  
<div id="blog-link" class="action" style="margin-top:-30px;"><a href="/who/giving/">Give</a></div>

 
<!-- GIVE -->
<!--
 <div class="donate">
 	<div><a href="javascript:animatedcollapse.toggle('give1')">Give</a></div>
 
 	<div id="give1">
 		<div class="donate_links">
 			<div style="border-top: dotted 1px; margin: 0px 5px 10px 5px;"></div>
 			<div style="padding-bottom:2px;padding-left:6px;"><img src="/interface/hex.png" style="padding-right:3px;">
 				<a href="https://secure.acceptiva.com/?cst=10ce82">General Fund</a></div>
 			<div style="padding-left:6px;"><img src="/interface/stj.png" style="padding-right:3px;">
 				<a href="https://secure.acceptiva.com/?cst=cf4fa6">Building Fund</a></div>
 		</div>
 	</div>
 </div>-->
 
<!-- End GIVE -->


 

		</div>

                <div class="breakingnews" style="font-size:12px;">
		</div>
		<div id="homePromo">
			
                 
                <div class="homePromoColumn">
				
                                    <a href="https://theaustinstone.wufoo.com/forms/connect-me-to-the-city/" class="noUnderline">                    <img src="/images/promos_sign_ups/city_button_1.jpg" width="200" height="154" border="0"  /></a>		    <p>To stay up to date on what is happening at The Austin Stone and connect with our community, join <a href="https://theaustinstone.wufoo.com/forms/connect-me-to-the-city/">The City</a>. If you already have an account, <a href="https://austinstone.onthecity.org/">go here</a>.</p>

                </div>
 
                <div class="homePromoColumn">
				
                                    <a href="http://forthecity.org/blog/fire_victims_need_your_help/" class="noUnderline">                    <img src="/images/promos_sign_ups/2011_fire-relief.jpg" width="200" height="154" border="0"  /></a>		    <p>Fire victims need your help. <a href="http://forthecity.org/blog/fire_victims_need_your_help/">Get involved now</a>.</p>

                </div>
 
                <div class="homePromoColumn">
				
                                    <a href="/what/fall11_classes" class="noUnderline">                    <img src="/images/promos_sign_ups/gt_fallclasses_button_1.png" width="200" height="154" border="0"  /></a>		    <p>Sign-up for a Get Trained class this Fall. Classes fill up quick so <a href="/what/fall11_classes">sign up now</a>.</p>

                </div>

<!-- end Buttons on Home page -->
                </div>
		<div class="clear"></div>

	<div id="footer">
<!-- Begin Footer -->

<div id="footerLeft">A New Testament church existing for the supremacy of the name and purpose of Jesus Christ</div>
			<div id="footerMenu">
				<ul>
					<li class="first"><a href="http://old.austinstone.org/resources/who_is_jesus/">Who is Jesus?</a></li>
					<li><a href="http://old.austinstone.org/who/contact/">Contact Us</a></li>
					<li><a href="http://old.austinstone.org/site_map/">Site Map</a></li>
					<li><a href="http://old.austinstone.org/privacy/">Privacy Policy</a></li>
				</ul>
			</div>
			

			
<script type="text/javascript">

sfHover = function() {
	var sfEls = document.getElementById("nav").getElementsByTagName("LI");
	for (var i=0; i<sfEls.length; i++) {
		sfEls[i].onmouseover=function() {
			this.className+=" sfhover";
		}
		sfEls[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
		}
	}
}
if (window.attachEvent) window.attachEvent("onload", sfHover);

</script>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>

<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-3493264-1");
pageTracker._initData();
pageTracker._trackPageview();
</script>

<script src="/admin/mint/?js" type="text/javascript"></script>

<!-- End Footer -->
	</div>
</div>
	
</body>
</html>
