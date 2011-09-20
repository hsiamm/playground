<?php defined( '_JEXEC' ) or die;

// variables
$app = JFactory::getApplication();
$templatepath = $this->baseurl.'/templates/'.$this->template;
$this->setGenerator(null);

?><!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="<?=$this->language?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="<?=$this->language?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="<?=$this->language?>"> <![endif]-->
<!--[if gt IE 8]><!-->  <html class="no-js" lang="<?=$this->language?>"> <!--<![endif]-->

	<head>
		<jdoc:include type="head" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- mobile viewport optimized -->
		<link rel="apple-touch-icon-precomposed" href="<?=$templatepath?>/apple-touch-icon-57x57.png"> <!-- iphone, ipod, android -->
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?=$templatepath?>/apple-touch-icon-72x72.png"> <!-- ipad -->
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?=$templatepath?>/apple-touch-icon-114x114.png"> <!-- iphone retina -->
		<link href="<?=$templatepath?>/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" /> <!-- favicon -->
		<link rel="stylesheet" href="<?=$templatepath?>/css/offline.css?v=1.0.0" type="text/css" /> <!-- stylesheet -->
		<script src="<?=$templatepath?>js/modernizr.js"></script> <!-- put all javascripts at the bottom, accept of modernizr.js -->
	</head>
	
	<body>
	<jdoc:include type="message" />
		<div id="frame" class="outline">
			<p><?=$app->getCfg('offline_message')?></p>
			<?php if(JPluginHelper::isEnabled('authentication', 'openid')) JHTML::_('script', 'openid.js'); ?>
			<form action="index.php" method="post" name="login" id="form-login">
				<fieldset class="input">
					<p id="form-login-username">
						<label for="username"><?=JText::_('Username')?></label><br />
						<input name="username" id="username" type="text" class="inputbox" alt="<?=JText::_('Username')?>" size="18" />
					</p>
					<p id="form-login-password">
						<label for="passwd"><?=JText::_('Password')?></label><br />
						<input type="password" name="passwd" class="inputbox" size="18" alt="<?=JText::_('Password')?>" id="passwd" />
					</p>
					<p id="form-login-remember">
						<label for="remember"><?=JText::_('Remember me')?></label>
						<input type="checkbox" name="remember" value="yes" alt="<?=JText::_('Remember me')?>" id="remember" />
					</p>
					<p id="form-login-submit">
						<label></label>
						<input type="submit" name="Submit" class="button" value="<?=JText::_('LOGIN')?>" />
					</p>
				</fieldset>
				<input type="hidden" name="option" value="com_user" />
				<input type="hidden" name="task" value="login" />
				<input type="hidden" name="return" value="<?=base64_encode(JURI::base())?>" />
				<?=JHTML::_( 'form.token' )?>
			</form>
		</div>
	</body>

</html>