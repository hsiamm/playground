<?php
/**
 * @package		 ITPrism Plugins
 * @subpackage	 ITPSharePoint
 * @copyright    Copyright (C) 2010 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * ITPSharePoint is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

/**
 * ITPSharePoint Plugin
 *
 * @package		ITPrism Plugins
 * @subpackage	ITPSharePoint
 * @since 		1.5
 */
class plgSystemITPSharePoint extends JPlugin {
    
    private $fbLocale = "en_US";
    
    public function __construct($subject, $params){
        
        parent::__construct($subject, $params);
    
        if($this->params->get("fbDynamicLocale", 0)) {
            $lang = JFactory::getLanguage();
            $locale = $lang->getTag();
            $this->fbLocale = str_replace("-","_",$locale);
        } else {
            $this->fbLocale = $this->params->get("fbLocale", "en_US");
        }

    }
    
    /**
     * Add social buttons into the article
     *
     * Method is called by the view
     *
     * @param   string  The context of the content being passed to the plugin.
     * @param   object  The content object.  Note $article->text is also available
     * @param   object  The content params
     * @param   int     The 'page' number
     * @since   1.6
     */
    public function onAfterDispatch(){
        
        $doc   = JFactory::getDocument();
        /* @var $doc JDocumentHtml */
        $doc->addStyleSheet(JURI::root() . "plugins/system/itpsharepoint/itpsharepoint.css");
        
        $desc = $doc->getDescription();
        $desc = str_replace("{itpsharepoint}", "", $desc);
        $doc->setDescription($desc);
        
    }
    
    /**
     * Add social buttons into the article
     *
     * Method is called by the view
     *
     * @param   string  The context of the content being passed to the plugin.
     * @param   object  The content object.  Note $article->text is also available
     * @param   object  The content params
     * @param   int     The 'page' number
     * @since   1.6
     */
    public function onAfterRender(){
        
        $app = JFactory::getApplication();
        /* @var $app JApplication */

        if($app->isAdmin()) {
            return;
        }
        
        $buffer = JResponse::getBody();

        // Generate buttons
        $buttons    = $this->getContent();
        
        $buffer = str_replace("{itpsharepoint}", $buttons, $buffer);
        
        JResponse::setBody($buffer);
        
    }
    
    
    /**
     * Generate content
     * @param   object      The article object.  Note $article->text is also available
     * @param   object      The article params
     * @return  string      Returns html code or empty string.
     */
    private function getContent(){
        
        $doc   = JFactory::getDocument();
        /* @var $doc JDocumentHtml */
        $docType = $doc->getType();
        
        // Check document type
        if(strcmp("html", $docType) != 0){
            return "";
        }
        
        $currentOptions = JRequest::getCmd("option");
        $currentView    = JRequest::getWord("view");

        // Check for excluded views in specify options/components
        $displayIn = $this->params->get("displayIn");
        if(!empty($displayIn)) {
            $displayIn = str_replace("'",'"',$displayIn);
            $displayIn = explode(";",$displayIn);
            $displayInViews = array();
            foreach($displayIn as $item) {
                $item = json_decode(trim($item),true);
                
                if(!empty($item)) {
                    if($currentOptions == $item[0] AND $currentView != $item[1])  {
                        return "";
                    }
                }
            }
        }
        
        $url = JURI::getInstance();
        $url = $url->toString();
        $title  = $doc->getTitle();
        $title  = htmlentities($title, ENT_QUOTES, "UTF-8");
        
        // Start buttons box
        $html = '
        <div class="itp-share">';
        
        $html   .= $this->getFacebookLike($this->params, $url, $title);
        
        $html   .= $this->getTwitter($this->params, $url, $title);
        $html   .= $this->getDigg($this->params, $url, $title);
        $html   .= $this->getStumbpleUpon($this->params, $url, $title);
        $html   .= $this->getBuzz($this->params, $url, $title);
        $html   .= $this->getLinkedIn($this->params, $url, $title);
        $html   .= $this->getGooglePlusOne($this->params, $url, $title);
        $html   .= $this->getReTweetMeMe($this->params, $url, $title);
        $html   .= $this->getYahooBuzz($this->params, $url, $title);
        $html   .= $this->getFacebookShareMe($this->params, $url, $title);
        
        // Gets extra buttons
        $html   .= $this->getExtraButtons($this->params, $url, $title);
        
        // End buttons box
        $html .= '
        </div>
        <div style="clear:both;"></div>
        ';
    
        return $html;
    }
    
    /**
     * Generate a code for the extra buttons
     */
    private function getExtraButtons($params, $url, $title) {
        
        $html  = "";
        // Extra buttons
        for($i=1; $i < 6;$i++) {
            $btnName = "ebuttons" . $i;
            $extraButton = $params->get($btnName, "");
            if(!empty($extraButton)) {
                $extraButton = str_replace("{URL}", $url,$extraButton);
                $extraButton = str_replace("{TITLE}", $title,$extraButton);
                $html  .= $extraButton;
            }
        }
        
        return $html;
    }
    
    private function getTwitter($params, $url, $title){
        
        $html = "";
        if($params->get("twitterButton")) {
            $html = '
            <div class="itp-share-tw">
            <a href="http://twitter.com/share" class="twitter-share-button" data-url="' . $url . '" data-text="' . $title . '" data-count="' . $params->get("twitterCounter") . '" data-via="' . $params->get("twitterName") . '" data-lang="' . $params->get("twitterLanguage") . '">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
            </div>
            ';
        }
        
        return $html;
    }
    
    private function getGooglePlusOne($params, $url, $title){
        $type = "";
        $language = "";
        if($params->get("plusType")) {
            $type = 'size="' . $params->get("plusType") . '"';
        }
        
        if($params->get("plusLocale")) {
            $language = " {lang: '" . $params->get("plusLocale") . "'}";
        }
            
        $html = "";
        if($params->get("plusButton")) {
            $html = '
            <div class="itp-share-gone">
            <!-- Place this tag in your head or just before your close body tag -->
            <script type="text/javascript" src="http://apis.google.com/js/plusone.js">' . $language . '</script>
            <!-- Place this tag where you want the +1 button to render -->
            <g:plusone ' . $type . ' href="' . $url . '"></g:plusone>
            </div>
            ';
        }
        
        return $html;
    }
    
    private function getFacebookLike($params, $url, $title){
        
        $html = "";
        if($params->get("facebookLikeButton")) {
            
            $faces = (!$params->get("facebookLikeFaces")) ? "false" : "true";
            
            $layout = $params->get("facebookLikeType","button_count");
            if(strcmp("box_count", $layout)==0){
                $height = "80";
            } else {
                $height = "25";
            }
            
            if(!$params->get("facebookLikeRenderer")){ // iframe
                $html = '
                <div class="itp-share-fbl">
                <iframe src="http://www.facebook.com/plugins/like.php?';
                
                if($params->get("facebookLikeAppId")) {
                    $html .= 'app_id=' . $params->get("facebookLikeAppId"). '&amp;';
                }
                
                $html .= '
                href=' . rawurlencode($url) . '&amp;
                send=' . $params->get("facebookLikeSend",0). '&amp;
                locale=' . $this->fbLocale . '&amp;
                layout=' . $layout . '&amp;
                show_faces=' . $faces . '&amp;
                width=' . $params->get("facebookLikeWidth","450") . '&amp;
                action=' . $params->get("facebookLikeAction",'like') . '&amp;
                colorscheme=' . $params->get("facebookLikeColor",'light') . '&amp;
                height='.$height.'
                ';
                if($params->get("facebookLikeFont")){
                    $html .= "&amp;font=" . $params->get("facebookLikeFont");
                }
                $html .= '
                " scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:' . $params->get("facebookLikeWidth", "450") . 'px; height:' . $height . 'px;" allowTransparency="true"></iframe>
                </div>
                ';
            } else {//XFBML
                $html = '<div class="itp-share-fbl">';
                
                if($params->get("facebookRootDiv",1)) {
                    $html .= '<div id="fb-root"></div>';
                }
                
               if($params->get("facebookLoadJsLib", 1)) {
                    $html .= '<script src="http://connect.facebook.net/' . $this->fbLocale . '/all.js#';
                    if($params->get("facebookLikeAppId")){
                        $html .= 'appId=' . $params->get("facebookLikeAppId"). '&amp;'; 
                    }
                    $html .= 'xfbml=1"></script>';
                }
                
                $html .= '
                <fb:like 
                href="' . $url . '" 
                layout="' . $layout . '" 
                show_faces="' . $faces . '" 
                width="' . $params->get("facebookLikeWidth","450") . '" 
                colorscheme="' . $params->get("facebookLikeColor","light") . '"
                send="' . $params->get("facebookLikeSend",0). '" 
                action="' . $params->get("facebookLikeAction",'like') . '" ';

                if($params->get("facebookLikeFont")){
                    $html .= 'font="' . $params->get("facebookLikeFont") . '"';
                }
                $html .= '></fb:like>
                </div>
                ';
            }
        }
        
        return $html;
    }
    
    private function getDigg($params, $url, $title){
        $title = html_entity_decode($title,ENT_QUOTES, "UTF-8");
        
        $html = "";
        if($params->get("diggButton")) {
            
            $html = '
            <div class="itp-share-digg">
            <script type="text/javascript">
(function() {
var s = document.createElement(\'SCRIPT\'), s1 = document.getElementsByTagName(\'SCRIPT\')[0];
s.type = \'text/javascript\';
s.async = true;
s.src = \'http://widgets.digg.com/buttons.js\';
s1.parentNode.insertBefore(s, s1);
})();
</script>
<a 
class="DiggThisButton '.$params->get("diggType","DiggCompact") . '"
href="http://digg.com/submit?url=' . rawurlencode($url) . '&amp;title=' . rawurlencode($title) . '">
</a>
            </div>
            ';
        }
        
        return $html;
    }
    
    private function getStumbpleUpon($params, $url, $title){
        
        $html = "";
        if($params->get("stumbleButton")) {
            
            $html = '
            <div class="itp-share-su">
            <script src="http://www.stumbleupon.com/hostedbadge.php?s=' . $params->get("stumbleType",1). '&r=' . rawurlencode($url) . '"></script>
            </div>
            ';
        }
        
        return $html;
    }
    
    private function getLinkedIn($params, $url, $title){
        
        $html = "";
        if($params->get("linkedInButton")) {
            
            $html = '
            <div class="itp-share-lin">
            <script type="text/javascript" src="http://platform.linkedin.com/in.js"></script><script type="in/share" data-url="' . $url . '" data-counter="' . $params->get("linkedInType",'right'). '"></script>
            </div>
            ';
        }
        
        return $html;
    }
    
    private function getBuzz($params, $url, $title){
        
        $html = "";
        if($params->get("buzzButton")) {
            
            $html = '
            <div class="itp-share-buzz">
            <a title="Post to Google Buzz" class="google-buzz-button" 
            href="http://www.google.com/buzz/post" 
            data-button-style="' . $params->get("buzzType","small-count"). '" 
            data-url="' . $url . '"
            data-locale="' . $this->params->get("buzzLocale", "en") . '"></a>
<script type="text/javascript" src="http://www.google.com/buzz/api/button.js"></script>
            </div>
            ';
        }
        
        return $html;
    }

    private function getReTweetMeMe($params, $url, $title){
        
        $html = "";
        if($params->get("retweetmeButton")) {
            
            $html = '
            <div class="itp-share-retweetme">
            <script type="text/javascript">
tweetmeme_url = "' . $url . '";
tweetmeme_style = "' . $params->get("retweetmeType") . '";
tweetmeme_source = "' . $params->get("twitterName") . '";
</script>
<script type="text/javascript" src="http://tweetmeme.com/i/scripts/button.js"></script>
            </div>';
        }
        
        return $html;
    }
    
    
    private function getYahooBuzz($params, $url, $title){
        
        $html = "";
        if($params->get("yahooBuzzButton")) {
            
            $html = '
            <div class="itp-share-yahoobuzz">
            <script type="text/javascript" src="http://d.yimg.com/ds/badge2.js" badgetype="'.$params->get("yahooBuzzType").'">' . $url . '</script>
            </div>';
        }
        
        return $html;
    }
    
    private function getFacebookShareMe($params, $url, $title){
            
            $html = "";
            if($params->get("facebookShareMeButton")) {
                
                $html = '
                <div class="itp-share-fbsh">
                <script>var fbShare = {
    url: "' . $url . '",
    title: "' . $title . '",
    size: "' . $params->get("facebookShareMeType","large"). '",
    badge_text: "' . $params->get("facebookShareMeBadgeText","C0C0C0"). '",
    badge_color: "' . $params->get("facebookShareMeBadge","CC00FF"). '",
    google_analytics: "false"
    }</script>
    <script src="http://widgets.fbshare.me/files/fbshare.js"></script>
                </div>
                ';
            }
            
            return $html;
        }
}