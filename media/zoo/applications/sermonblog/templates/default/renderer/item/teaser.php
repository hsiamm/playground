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
        window.open( $url, "Share on Twitter", "status = 1, height = 450, width = 550, resizable = 0" );
    }
</script>

<div class="sermon">
    <h3 class="series_label">
        <?php echo $this->renderPosition('categories'); ?>
    </h3>

    <?php if ($this->checkPosition('title')) { ?>
        <h2 class="sermon_title nomar">
            <?php echo $this->renderPosition('title'); ?>
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
            <div style="width:285px;height:25px;background:#fff;color:#000;line-height:25px;text-align:center;float:left;">
                <object type="application/x-shockwave-flash" data="/images/sermons/player_mp3_maxi.swf" width="285" height="25">
                    <param name="movie" value="/images/sermons/player_mp3_maxi.swf" />
                    <param name="bgcolor" value="#ffffff" />
                    <param name="FlashVars" value="mp3=http://files.austinstone.org/audio/mp3/<?php echo trim($this->renderPosition('audio_file')); ?>&amp;width=285&amp;height=25&amp;showvolume=1&amp;loadingcolor=ffdd00&amp;bgcolor1=ffffff&amp;bgcolor2=ffffff&amp;slidercolor1=000000&amp;slidercolor2=000000&amp;sliderovercolor=FFDD00&amp;buttoncolor=000000&amp;buttonovercolor=FFDD00&amp;textcolor=000000" />
                    <audio src="http://files.austinstone.org/audio/mp3/<?php echo trim($this->renderPosition('audio_file')); ?>" preload="none" controls style="width:400px;padding-top:5px"></audio>
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
            <div style="width:325px;height:25px;background:#fff;color:#000;line-height:25px;text-align:center;float:left;">
                <object type="application/x-shockwave-flash" data="/images/sermons/player_mp3_maxi.swf" width="325" height="25">
                    <param name="movie" value="/images/sermons/player_mp3_maxi.swf" />
                    <param name="bgcolor" value="#ffffff" />
                    <param name="FlashVars" value="mp3=http://files.austinstone.org/audio/mp3/<?php echo trim($this->renderPosition('audio_file')); ?>&amp;width=325&amp;height=25&amp;showvolume=1&amp;loadingcolor=ffdd00&amp;bgcolor1=ffffff&amp;bgcolor2=ffffff&amp;slidercolor1=000000&amp;slidercolor2=000000&amp;sliderovercolor=FFDD00&amp;buttoncolor=000000&amp;buttonovercolor=FFDD00&amp;textcolor=000000" />
                    <audio src="http://files.austinstone.org/audio/mp3/<?php echo trim($this->renderPosition('audio_file')); ?>" preload="none" controls style="width:400px;padding-top:5px"></audio>
                </object>
            </div>
            <a href="http://files.austinstone.org/audio/mp3/<?php echo trim($this->renderPosition('audio_file')); ?>"><div class="download_mp3">Download MP3</div></a>
        </div>
    <?php } ?>
    <div id="custom-tweet-button">
        <?php
        $arr = explode('"', $this->renderPosition('guid'), 3);
        $shareURL = "https://twitter.com/share?url=";
        $shareURL .= urlencode("http://austinstone.org" . $arr[1]);
        $shareURL .= "&text=Check out " . urlencode(trim($this->renderPosition('title'))) . ".";
        ?>
        <a onClick="popup(<?php echo "'".$shareURL."'"; ?>)" target="_blank">
            <img src="/images/sermons/twitter.png" />
        </a>
    </div>

</div><!-- /sermon -->

<div class="rule_wh"></div>