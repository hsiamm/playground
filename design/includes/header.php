<!DOCTYPE html>
<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title>The Austin Stone Community Church</title>

        <link rel="icon" href="" type="image/x-icon" />
        <link rel="shortcut icon" href="" type="image/x-icon" />

        <link rel="stylesheet" type="text/css" href="http://dev.austinstone.org/design/css/style.css" media="screen">
                
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
						$(this).text('Get In Touch With Us');
					} else {
						$(obj).addClass('expanded');
						$('.content',obj).slideDown('slow');
						$(this).text('Nevermind, we can talk later');
					}
					return false;
				});
			});
		</script> 
    </head>