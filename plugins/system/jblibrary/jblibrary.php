<?php
/**
* @id			$Id$
* @author 		Joomla Bamboo
* @package  	JB Library
* @copyright 	Copyright (C) 2006 - 2010 Joomla Bamboo. http://www.joomlabamboo.com  All rights reserved.
* @license  	GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
/** Thanks to onejQuery for being the inspiration of our unique jQuery function **/
/** ensure this file is being included by a parent file */
jimport( 'joomla.plugin.plugin' );
class plgSystemJblibrary extends JPlugin {
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
		$app = JFactory::getApplication();
        $this->_jqpath = '';
		//Dont Add Jquery in Admin
        if($app->isAdmin())return;
    }
    function onAfterInitialise() {
        if(JFactory::getApplication()->isAdmin())return;
		$doc =& JFactory::getDocument();
        $source = $this->params->get('source','google');    
        $jQueryVersion = $this->params->get('jQueryVersion','1.5.1');
		$noConflict = $this->params->get('noConflict',1);
        $ie6Warning = $this->params->get('ie6Warning',1); 
        $scrolltop = $this->params->get('scrollTop',1);
        $scrollStyle = $this->params->get('scrollStyle','dark');
        $scrollText = $this->params->get('scrollText','^ Back To Top');
		$resizeImage = $this->params->get('resizeImage','1');
		$riContent = $this->params->get('riContent','1');
		$prettyPhoto = $this->params->get('prettyPhoto','1');
		$ppContent = $this->params->get('ppContent','1');
        $llSelector = $this->params->get('llSelector','img');
        $selectedMenus = $this->params->get('menuItems','');
        $lazyLoad = $this->params->get('lazyLoad',1);
        $itemid = JRequest::getInt('Itemid');
        if(!$itemid) $itemid = 1;
        if($llSelector == '') $llSelector = 'img';
        if (is_array($selectedMenus)){
            $menus = $selectedMenus;
        } elseif (is_string($selectedMenus) && $selectedMenus!=''){
            $menus[] = $selectedMenus;
        } elseif ($selectedMenus == ''){
            $menus[] = $itemid;
        }
        //module base
        $modbase = JURI::root (true).DS.'media'.DS.'plg_jblibrary'.DS;
    	$jsbase = $modbase.'jquery'.DS;
		$helperbase = JPATH_SITE.DS.'media'.DS.'plg_jblibrary'.DS.'helpers'.DS;
		$document =& JFactory::getDocument();
		if(in_array($itemid,$menus)){
		   	// Load Mootools first
		   	JHTML::_(' behavior.mootools');
		   	if ($jQueryVersion == '1.2.6') {
					if ($source == 'local') {
						$this->_jqpath = $jsbase . 'jquery-1.2.6.pack.js';
					}
					if ($source == 'google') {
						$this->_jqpath = 'http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js';
					}
					$document->addScript($this->_jqpath); 
		   	}
		   	if ($jQueryVersion == '1.3.2') {
					if ($source == 'local') {
						$this->_jqpath = $jsbase . 'jquery-1.3.2.min.js';
					}
					if ($source == 'google') {
						$this->_jqpath = 'http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js';
					}
					$document->addScript($this->_jqpath); 
		   	}
			if ($jQueryVersion == '1.4.2') {
					if ($source == 'local') {
						$this->_jqpath = $jsbase . 'jquery-1.4.2.min.js';
					}
					if ($source == 'google') {
						$this->_jqpath = 'http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js';
					}
					$document->addScript($this->_jqpath); 
	   			}
	   		if ($jQueryVersion == '1.4.3') {
					if ($source == 'local') {
						$this->_jqpath = $jsbase . 'jquery-1.4.3.min.js';
					}
					if ($source == 'google') {
						$this->_jqpath = 'http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js';
					}
					$document->addScript($this->_jqpath); 
	   			}
	   		if ($jQueryVersion == '1.4.4') {
					if ($source == 'local') {
						$this->_jqpath = $jsbase . 'jquery-1.4.4.min.js';
					}
					if ($source == 'google') {
						$this->_jqpath = 'http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js';
					}
					$document->addScript($this->_jqpath); 
	   			}
			if ($jQueryVersion == '1.5.0') {
					if ($source == 'local') {
						$this->_jqpath = $jsbase . 'jquery-1.5.0.min.js';
					}
					if ($source == 'google') {
						$this->_jqpath = 'http://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js';
					}
					$document->addScript($this->_jqpath); 
	   			}
			if ($jQueryVersion == '1.5.1') {
					if ($source == 'local') {
						$this->_jqpath = $jsbase . 'jquery-1.5.1.min.js';
					}
					if ($source == 'google') {
						$this->_jqpath = 'http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js';
					}
					$document->addScript($this->_jqpath); 
	   			}
			if ($jQueryVersion == '1.5.2') {
					if ($source == 'local') {
						$this->_jqpath = $jsbase . 'jquery-1.5.2.min.js';
					}
					if ($source == 'google') {
						$this->_jqpath = 'http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js';
					}
					$document->addScript($this->_jqpath); 
	   			}
			if ($jQueryVersion == '1.6.0') {
					if ($source == 'local') {
						$this->_jqpath = $jsbase . 'jquery-1.6.0.min.js';
					}
					if ($source == 'google') {
						$this->_jqpath = 'http://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js';
					}
					$document->addScript($this->_jqpath); 
	   			}
			if ($jQueryVersion == '1.6.1') {
					if ($source == 'local') {
						$this->_jqpath = $jsbase . 'jquery-1.6.1.min.js';
					}
					if ($source == 'google') {
						$this->_jqpath = 'http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js';
					}
					$document->addScript($this->_jqpath); 
	   			}
			if ($jQueryVersion == '1.6') {
					$this->_jqpath = 'http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js';
					$document->addScript($this->_jqpath); 
	   			}
			if(!($jQueryVersion == "none") and $noConflict){
				$document->addScriptDeclaration('jQuery.noConflict();');
			}
   		}	   	
	   	//Detect Browser
		$browser = $_SERVER['HTTP_USER_AGENT'];
		$browser = substr('$browser', 25, 8);
		//Load Scroll To Top if Not IE6
		if ($scrolltop and ($browser != 'MSIE 6.0')){
			if($scrollStyle == 'dark')
			{
				$document->addStyleDeclaration('#toTop {width:100px;z-index: 10;border: 1px solid #333; background:#121212; text-align:center; padding:5px; position:fixed; bottom:0px; right:0px; cursor:pointer; display:none; color:#fff;text-transform: lowercase; font-size: 0.7em;}');
			}
			if($scrollStyle == 'light')
			{
				$document->addStyleDeclaration('#toTop {width:100px;z-index: 10;border: 1px solid #eee; background:#f7f7f7; text-align:center; padding:5px; position:fixed; bottom:0px; right:0px; cursor:pointer; display:none;  color:#333;text-transform: lowercase; font-size: 0.8em;}');
			}
		}		
		//Load Pretty Photo Script
		if (($resizeImage)||(!$riContent)){
			include_once($helperbase . 'image.php');
		}
		//Load Pretty Photo Script
		if (($prettyPhoto)||($ppContent)){
			if((!$resizeImage)&&(!$riContent)){
				include_once($helperbase . 'image.php');
			}
			include_once($helperbase . 'pretty.php');
			$document->addScript($modbase . 'prettyPhoto'.DS.'js'.DS.'jquery.prettyPhoto.js');
			$document->addStyleSheet($modbase . 'prettyPhoto'.DS.'css'.DS.'prettyPhoto.css');
		}
		//Load Lazy Load Script
		if ($lazyLoad){
			$document->addScript($jsbase. 'jquery.lazyload.js');
		}
	}
	function onContentPrepare($context, &$article, &$params, $page = 0){
		$riContent = $this->params->get('riContent','1');
		$ppContent = $this->params->get('ppContent','1');
		
		if($riContent){
			// expression to search for
			$runword = "jbresize";
			$img_regex = "|<[\s\v]*img[\s\v]([^>]*".$runword."[^>]*)>|Ui"; 
			$img_matches = array();
			// find all instances of plugin and put in $matches
			preg_match_all($img_regex, $article->text, $img_matches, PREG_SET_ORDER);
			foreach ($img_matches as $img_match) {
				// $match[0] is full pattern match, $match[1] is the regex
				$output = $this->processResizeImageInline( $img_match[0] );
				$article->text = preg_replace("|$img_match[0]|", $output, $article->text, 1);
			}
			// expression to search for
			$regex = '/{resizeImage:\s*.*?}/i'; 
			$matches = array();
			// find all instances of plugin and put in $matches
			preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);
			foreach ($matches as $match) {
				// $match[0] is full pattern match, $match[1] is the regex
				$output = $this->processResizeImageContent( $match[0] );
				$article->text = preg_replace("|$match[0]|", $output, $article->text, 1);			
			}
		}
		if ($ppContent){
			// expression to search for
			$regex = '/{prettyPhoto:\s*.*?}/i'; 
			$matches = array();
			//find all instances of plugin and put in $matches
			preg_match_all( $regex, $article->text, $matches, PREG_SET_ORDER);
			foreach ($matches as $match) {
				// $match[0] is full pattern match, $match[1] is the regex
				$output = $this->processPrettyPhotoContent( $match[0] );
				$article->text = preg_replace("|$match[0]|", $output, $article->text, 1);
			}
		} 
	}
	
	private function processResizeImageInline( $match ){
		jimport( 'joomla.filesystem.file' );
		//get variable data from string
		$asrc = array();
			preg_match( "#src=\"(.*?)\"#si", $match, $asrc);
			if (isset($asrc[1])) $src = trim($asrc[1]);
			  else $src="";
		$awidth = array();
			preg_match( "#width=\"(.*?)\"#si", $match, $awidth);
			if (isset($awidth[1])) $width = trim($awidth[1]);
			  else $width="";			
		$aheight = array();
			preg_match( "#height=\"(.*?)\"#si", $match, $aheight);
			if (isset($aheight[1])) $height = trim($aheight[1]);
			  else $height="";
		$atitle = array();
			preg_match( "#title=\"(.*?)\"#si", $match, $atitle);
			if (isset($atitle[1])) $title = str_replace('_', ' ', trim($atitle[1]));
			  else $title = str_replace('_', ' ', Jfile::stripExt(Jfile::getName($image)));
		$option='smart';
		$quality='90';
							  
		$newImage = resizeImageHelper::getResizedImage($src, $width, $height, $option, $quality);				
		return '<img src="'.$newImage.'" class="thumbnail" title="'.$title.'" />';
	}	
	
	private function processResizeImageContent( $match ){
		jimport( 'joomla.filesystem.file' );
		//get variable data from string
		$aimage = array();
			preg_match( "#image=\"(.*?)\"#si", $match, $aimage);
			if (isset($aimage[1])) $image = trim($aimage[1]);
			  else $image="";
		$awidth = array();
			preg_match( "#width=\"(.*?)\"#si", $match, $awidth);
			if (isset($awidth[1])) $width = trim($awidth[1]);
			  else $width="";			
		$aheight = array();
			preg_match( "#height=\"(.*?)\"#si", $match, $aheight);
			if (isset($aheight[1])) $height = trim($aheight[1]);
			  else $height="";
		$aoption = array();
			preg_match( "#option=\"(.*?)\"#si", $match, $aoption);
			if (isset($aoption[1])) $option = trim($aoption[1]);
			  else $option='smart';
		$aquality = array();
			preg_match( "#quality=\"(.*?)\"#si", $match, $aquality);
			if (isset($aquality[1])) $quality = trim($aquality[1]);
			  else $quality='90';
		$atitle = array();
			preg_match( "#title=\"(.*?)\"#si", $match, $atitle);
			if (isset($atitle[1])) $title = str_replace('_', ' ', trim($atitle[1]));
			  else $title = str_replace('_', ' ', Jfile::stripExt(Jfile::getName($image)));
							  
		$newImage = resizeImageHelper::getResizedImage($image, $width, $height, $option, $quality);				
		return '<img src="'.$newImage.'" class="thumbnail" title="'.$title.'" />';
	}	
	
	private function processPrettyPhotoContent( $match ){
		$defaultImg = $this->params->get('defaultImg');
		//get variable data from string
		$atype = array();
			preg_match( "#type=\"(.*?)\"#si", $match, $atype);
			if (isset($atype[1])) $type = trim($atype[1]);
			  else $type="";
		$asource = array();
			preg_match( "#source=\"(.*?)\"#si", $match, $asource);
			if (isset($asource[1])){ $source = trim($asource[1]); }
			else if (isset($defaultImg)){ $source = $defaultImg; }
			else{ $source = JURI::root (true).DS.'media'.DS.'plg_jblibrary'.DS.'prettyPhoto'.DS.'images'.DS.'backgrounds'.DS.'externalLink.png';}
		$awidth = array();
			preg_match( "#width=\"(.*?)\"#si", $match, $awidth);
			if (isset($awidth[1])) $width = trim($awidth[1]);
			  else $width="";			
		$aheight = array();
			preg_match( "#height=\"(.*?)\"#si", $match, $aheight);
			if (isset($aheight[1])) $height = trim($aheight[1]);
			  else $height="";
		$aoption = array();
			preg_match( "#option=\"(.*?)\"#si", $match, $aoption);
			if (isset($aoption[1])) $option = trim($aoption[1]);
			  else $option='smart';
		$aquality = array();
			preg_match( "#quality=\"(.*?)\"#si", $match, $aquality);
			if (isset($aquality[1])) $quality = trim($aquality[1]);
			  else $quality='90';
		$atheme = array();
			preg_match( "#theme=\"(.*?)\"#si", $match, $atheme);
			if (isset($atheme[1])) $theme = trim($atheme[1]);
			  else $theme="facebook";			
		$apadding = array();
			preg_match( "#padding=\"(.*?)\"#si", $match, $apadding);
			if (isset($apadding[1])) $padding = trim($apadding[1]);
			  else $padding="0";
		$aopacity = array();
			preg_match( "#opacity=\"(.*?)\"#si", $match, $aopacity);
			if (isset($aopacity[1])) $opacity = trim($aoption[1]);
			  else $opacity="0.8";
		$atitle = array();
			preg_match( "#title=\"(.*?)\"#si", $match, $atitle);
			if (isset($atitle[1])) $title = str_replace('_', ' ', trim($atitle[1]));
			  else $title = str_replace('_', ' ', Jfile::stripExt(Jfile::getName($image)));
		$aspeed = array();
			preg_match( "#speed=\"(.*?)\"#si", $match, $aspeed);
			if (isset($aspeed[1])) $speed = trim($awidth[1]);
			  else $speed="normal";		
		$auser = array();
			preg_match( "#user=\"(.*?)\"#si", $match, $auser);
			if (isset($auser[1])) $user = trim($auser[1]);
			  else $user="";
		$aapikey = array();
			preg_match( "#apikey=\"(.*?)\"#si", $match, $aapikey);
			if (isset($aapikey[1])) $apikey = trim($aapikey[1]);
			  else $apikey="";
		$asetid = array();
			preg_match( "#setid=\"(.*?)\"#si", $match, $asetid);
			if (isset($asetid[1])) $setid = trim($asetid[1]);
			  else $setid="";
		$anumber = array();
			preg_match( "#number=\"(.*?)\"#si", $match, $anumber);
			if (isset($anumber[1])) $number = trim($anumber[1]);
			  else $number="";
		$aextlink = array();
			preg_match( "#extlink=\"(.*?)\"#si", $match, $aextlink);
			if (isset($aextlink[1])) $extlink = trim($aextlink[1]);
			  else $extlink="";
			  		
		$html = prettyPhotoHelper::getPrettyPhoto($type, $source, $width, $height, $option, $quality, $theme, $padding, $opacity, $title, $speed, $user, $apikey, $setid, $number, $extlink );
		return $html;	
	}

	function onAfterRoute() {
		/*if(JFactory::getApplication()->isAdmin())return;
		$selectedMenus = $this->params->get('menuItems','');
		//$menu = &JSite::getMenu();
		$menuItem   = $this->_menu->getActive();
		$itemid = $menuItem->id;
		//$itemid = JSite::getMenu()->getActive()->id;
		//$itemid 		= JRequest::getInt('Itemid');
		if(!$itemid) $itemid = 1;
		if (is_array($selectedMenus)){
			$menus = $selectedMenus;
		} elseif (is_string($selectedMenus) && $selectedMenus!=''){
			$menus[] = $selectedMenus;
		} elseif ($selectedMenus == ''){
			$menus[] = $itemid;
		}
		$menuVar = '<pre>'.$itemid.' in route array '.print_r($menus).'</pre>';
		$doc->addCustomTag($menuVar);
		if(in_array($itemid,$menus)){}
		*/
	}
	function onAfterRender() {
		if(JFactory::getApplication()->isAdmin()){return;}	
		$jqRegex = $this->params->get('jqregex','([\/a-zA-Z0-9_:\.-]*)jquery([0-9\.-]|min|pack)*?.js');
		$jqUnique = $this->params->get('jqunique',0);
		$stripCustom = $this->params->get('stripCustom',0);
		$customScripts = $this->params->get('customScripts','');
		$stripMootools = $this->params->get('stripMootools',0);
		$stripMootoolsMore = $this->params->get('stripMootoolsMore',0);
		$replaceMootools = $this->params->get('replaceMootools',0);
		$ie6Warning = $this->params->get('ie6Warning',1);
		$mootoolsPath = $this->params->get('mootoolsPath','http://ajax.googleapis.com/ajax/libs/mootools/1.2.4/mootools-yui-compressed.js');
		$scrolltop = $this->params->get('scrollTop',1);
		$lazyLoad = $this->params->get('lazyLoad',1);
		$scrollStyle = $this->params->get('scrollStyle','dark');
		$scrollText = $this->params->get('scrollText','^ Back To Top');
		$llSelector = $this->params->get('llSelector','img');
		if($llSelector == '') $llSelector = 'img';
		$body =& JResponse::getBody();
		if($stripMootools){
			$body = preg_replace("#([\/a-zA-Z0-9_:\.-]*)mootools-core.js#", "", $body);
			$body = preg_replace("#([\/a-zA-Z0-9_:\.-]*)caption.js#", "", $body);
			$body = str_ireplace('<script src="" type="text/javascript"></script>', "", $body);
		}
		if($stripMootoolsMore){
			$body = preg_replace("#([\/a-zA-Z0-9_:\.-]*)mootools-more.js#", "", $body);
			$body = str_ireplace('<script src="" type="text/javascript"></script>', "", $body);
		}
		if($replaceMootools){
			if ($mootoolsPath != ''){$body = preg_replace("#([\/a-zA-Z0-9_:\.-]*)mootools-core.js#", "MTLIB", $body, 1);}
			$body = str_ireplace('<script src="" type="text/javascript"></script>', "", $body);
			$body = preg_replace("#MTLIB#", $mootoolsPath, $body);
		}
		if($jqUnique && $jqRegex){
			if ($this->_jqpath != ''){$body = preg_replace("#$jqRegex#", "JQLIB", $body, 1);}
            $body = preg_replace("#$jqRegex#", "", $body);
            $body = str_ireplace('<script src="" type="text/javascript"></script>', "", $body);
            $body = preg_replace("#jQuery\.noConflict\(\);#", "", $body);
            $body = preg_replace('#(<script src="JQLIB" type="text/javascript"></script>)#', '\\1<script type=\'text/javascript\'>jQuery.noConflict();</script>', $body);
            $body = preg_replace("#JQLIB#", $this->_jqpath, $body);
		}
		if($stripCustom && ($customScripts != '')){
			$customScripts = preg_split("/[\s,]+/", trim($customScripts));
			foreach($customScripts as $scriptName){
				$scriptRegex = "([\/a-zA-Z0-9_:\.-]*)".trim($scriptName);
				$body = preg_replace("#$scriptRegex#", "", $body);
			}
			$body = str_ireplace('<script src="" type="text/javascript"></script>', "", $body);
		}
		//Detect Browser
		$browser = $_SERVER['HTTP_USER_AGENT'];
		$browser = substr('$browser', 25, 8);
		$scripts = '';
		if ($ie6Warning and ($browser == 'MSIE 6.0')) { 
	   			$scripts = '
	   			<!--[if lte IE 6]>
	   			<script type="text/javascript" src="'.$jsbase.'jquery.badBrowser.js"></script> 
	   			 <![endif]-->
	   			 ';	
		 }
		//Load Scroll To Top if Not IE6
		if ($scrolltop and ($browser != 'MSIE 6.0')){
			$scripts .= '
			<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery(function () {
				var scrollDiv = document.createElement("div");
				jQuery(scrollDiv).attr("id", "toTop").html("'.$scrollText.'").appendTo("body");    
				jQuery(window).scroll(function () {
						if (jQuery(this).scrollTop() != 0) {
							jQuery("#toTop").fadeIn();
						} else {
							jQuery("#toTop").fadeOut();
						}
					});
					jQuery("#toTop").click(function () {
						jQuery("body,html").animate({
							scrollTop: 0
						},
						800);
					});
				});
			});
			</script>
			';
		}
		if ($lazyLoad){
			$scripts .= '
			<script type="text/javascript">
			jQuery(document).ready(function(){jQuery("'.$llSelector.'").lazyload({ 
		    effect : "fadeIn" 
		    });
		});
		</script>
		';
		}
		$body = str_replace ("</body>", $scripts."</body>", $body);
		JResponse::setBody($body);
		return true;
	}
}
?>
