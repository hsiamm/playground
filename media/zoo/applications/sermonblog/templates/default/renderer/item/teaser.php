<?php
/**
 * @package   com_zoo Component
 * @file      teaser.php
 * @version   2.4.10 June 2011
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

// init vars
$params = $item->getParams('site');
?>
<script>
    function popup($url) {
        window.open($url,"Share","status=1,height=450,width=550,resizable=0");
    }
    
    function fbs_click($url) {
        window.open('http://www.facebook.com/sharer.php?u='+$url,'sharer','toolbar=0,status=0,width=626,height=336');
        return false;
    }
</script>


<div class="sermon">
    <h3 class="series_label">
        <?php echo $this->renderPosition('categories'); ?>
    </h3>

    <?php if ($this->checkPosition('title')) { ?>
        <h2 class="sermon_title nomar">
            <?php $arr = explode('"', $this->renderPosition('guid'), 3); ?>
            <a href="<?php echo $arr[1]; //prints the permalink       ?>">
                <?php echo $this->renderPosition('title'); ?>
            </a>
        </h2>
    <?php } ?>

    <h4 class="grey nomar">
        <?php echo $this->renderPosition('speaker'); ?>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;
        <?php echo $this->renderPosition('date'); ?>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;
        <?php echo $this->renderPosition('passage'); ?>
    </h4>

    <?php if ($this->checkPosition('extra_embed')) { ?>
        <?php $videoid = str_replace(' ', '-', trim($this->renderPosition('extra_name'))); ?>
        <a class="video-pop" href="#<?php echo $videoid; ?>">
            <h4 class="blue nomar"><img style="height:12px;" src="/images/camera-icon.png"/><?php echo $this->renderPosition('extra_name'); ?></h4>
        </a>
        <div style="display:none;">
            <div id="<?php echo $videoid; ?>">
                <?php echo $this->renderPosition('extra_embed'); ?>
            </div>
        </div>
    <?php } ?>

    <?php if ($this->checkPosition('video_name')) { ?>
        <div class="sermon_player">
            <div class="sermon_player_wrap sermon_size">
                <object class="sermon_size" type="application/x-shockwave-flash" data="/images/sermons/player_mp3_maxi.swf">
                    <param name="movie" value="/images/sermons/player_mp3_maxi.swf" />
                    <param name="bgcolor" value="#ffffff" />
                    <param name="FlashVars" value="mp3=http://files.austinstone.org/audio/mp3/<?php echo trim($this->renderPosition('audio_file')); ?>&amp;width=250&amp;height=25&amp;showvolume=1&amp;loadingcolor=ffdd00&amp;bgcolor1=ffffff&amp;bgcolor2=ffffff&amp;slidercolor1=000000&amp;slidercolor2=000000&amp;sliderovercolor=FFDD00&amp;buttoncolor=000000&amp;buttonovercolor=FFDD00&amp;textcolor=000000" />
                    <audio src="http://files.austinstone.org/audio/mp3/<?php echo trim($this->renderPosition('audio_file')); ?>" preload="none" controls style="width:250px;line-height:25px;"></audio>
                </object>
            </div>
            <?php $sermonvideoid = str_replace(' ', '-', trim($this->renderPosition('video_name'))); ?>
            <div class="sermon_video"><a class="video-pop" href="#<?php echo $sermonvideoid; ?>"><img src="/images/sermon_video.png"></img></a></div>
            <div style="display:none;">
                <div id="<?php echo $sermonvideoid; ?>">
                    <?php echo $this->renderPosition('video_embed'); ?>
                </div>
            </div>
            <a href="http://files.austinstone.org/audio/mp3/<?php echo trim($this->renderPosition('audio_file')); ?>"><div class="download_mp3">Download MP3</div></a>
        </div>
    <?php } else { ?>
        <div class="sermon_player">
            <div class="sermon_player_wrap sermon_size_big">
                <object class="sermon_size_big" type="application/x-shockwave-flash" data="/images/sermons/player_mp3_maxi.swf" width="290" height="25">
                    <param name="movie" value="/images/sermons/player_mp3_maxi.swf" />
                    <param name="bgcolor" value="#ffffff" />
                    <param name="FlashVars" value="mp3=http://files.austinstone.org/audio/mp3/<?php echo trim($this->renderPosition('audio_file')); ?>&amp;width=290&amp;height=25&amp;showvolume=1&amp;loadingcolor=ffdd00&amp;bgcolor1=ffffff&amp;bgcolor2=ffffff&amp;slidercolor1=000000&amp;slidercolor2=000000&amp;sliderovercolor=FFDD00&amp;buttoncolor=000000&amp;buttonovercolor=FFDD00&amp;textcolor=000000" />
                    <audio src="http://files.austinstone.org/audio/mp3/<?php echo trim($this->renderPosition('audio_file')); ?>" preload="none" controls style="width:250px;line-height:25px;"></audio>
                </object>
            </div>
            <a href="http://files.austinstone.org/audio/mp3/<?php echo trim($this->renderPosition('audio_file')); ?>"><div class="download_mp3">Download MP3</div></a>
        </div>
    <?php } ?>
    <div id="custom-facebook-button">
        <?php
        $arr = explode('"', $this->renderPosition('guid'), 3);
        $shareURL = urlencode("http://austinstone.org" . $arr[1]);
        ?>
        <a onclick="fbs_click(<?php echo "'" . $shareURL . "'"; ?>)" target="_blank">
            <img src="/images/sermons/facebook.png" />
        </a>
    </div>
    <div id="custom-tweet-button">
        <?php
        $arr = explode('"', $this->renderPosition('guid'), 3);
        $shareURL = "https://twitter.com/share?url=";
        $shareURL .= urlencode("http://austinstone.org" . $arr[1]);
        $shareURL .= "&text=Check out " . urlencode(trim($this->renderPosition('title')) . " #ascc");
        ?>
        <a onClick="popup(<?php echo "'" . $shareURL . "'"; ?>)" target="_blank">
            <img src="/images/sermons/twitter.png" />
        </a>
    </div>

</div><!-- /sermon -->

<div class="rule_wh"></div>