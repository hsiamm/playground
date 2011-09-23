<?php
/**
 * @package   com_zoo Component
 * @file      full.php
 * @version   2.4.10 June 2011
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<script>
    $(document).ready(function() {
        $(".sermon_sort h2 a").click(function() {
            var id = $(this).attr('id');
            var item = document.getElementById(id);
            $(".sermon_sort_options").slideUp("slow");
            console.log($(item).css('display'));
            if ($(item).css('display') == 'inline')
                $("#" + id + "_sort").slideDown("slow");
            console.log($(item).css('display'));
            return false;
        });
    });
    
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
                    $('#sermon_scroll_two').addClass('fixed');
                } else {
                    // otherwise remove it
                    $('#sermon_scroll_one').removeClass('fixed');
                    $('#sermon_scroll_two').removeClass('fixed');
                }
            });
        }  
    });
</script>

<div class="sermon" style="height: 500px;">
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
        <!--<div id="custom-tweet-button">
            <?php
            $arr = explode('"', $this->renderPosition('guid'), 3);
            $shareURL = "?url=";
            $shareURL .= urlencode("http://austinstone.org" . $arr[1]);
            $shareURL .= "&text=Check out this sermon from Austin Stone! ";
            ?>
            <a href="https://twitter.com/share<?php //echo $shareURL; ?>" target="_blank">Tweet</a>
        </div>-->
    </h4>

    <?php if ($this->checkPosition('extra_embed')) { ?>
        <h4 class="blue nomar"><?php echo $this->renderPosition('extra_name'); ?></h4>
    <?php } ?>

    <?php if ($this->checkPosition('video_name')) { ?>
        <div class="sermon_player">
            <div style="width:320px;height:25px;background:#fff;color:#000;line-height:25px;text-align:center;float:left;">
                <object type="application/x-shockwave-flash" data="/images/sermons/player_mp3_maxi.swf" width="320" height="25">
                    <param name="movie" value="/images/sermons/player_mp3_maxi.swf" />
                    <param name="bgcolor" value="#ffffff" />
                    <param name="FlashVars" value="mp3=http://files.austinstone.org/audio/mp3/<?php echo trim($this->renderPosition('audio_file')); ?>&amp;width=320&amp;height=25&amp;showvolume=1&amp;loadingcolor=ffdd00&amp;bgcolor1=ffffff&amp;bgcolor2=ffffff&amp;slidercolor1=000000&amp;slidercolor2=000000&amp;sliderovercolor=FFDD00&amp;buttoncolor=000000&amp;buttonovercolor=FFDD00&amp;textcolor=000000" />
                    <audio src="http://files.austinstone.org/audio/mp3/<?php echo trim($this->renderPosition('audio_file')); ?>" preload="none" controls style="width:400px;padding-top:5px"></audio>
                </object>
            </div>
            <div class="sermon_video"><a class="pop" href="http://vimeo.com/29166738"><img src="/images/sermon_video.png"></img></a></div>
            <div class="download_mp3">Download MP3</div>
        </div>
    <?php } else { ?>
        <div class="sermon_player">
            <div style="width:360px;height:25px;background:#fff;color:#000;line-height:25px;text-align:center;float:left;">
                <object type="application/x-shockwave-flash" data="/images/sermons/player_mp3_maxi.swf" width="360" height="25">
                    <param name="movie" value="/images/sermons/player_mp3_maxi.swf" />
                    <param name="bgcolor" value="#ffffff" />
                    <param name="FlashVars" value="mp3=http://files.austinstone.org/audio/mp3/<?php echo trim($this->renderPosition('audio_file')); ?>&amp;width=360&amp;height=25&amp;showvolume=1&amp;loadingcolor=ffdd00&amp;bgcolor1=ffffff&amp;bgcolor2=ffffff&amp;slidercolor1=000000&amp;slidercolor2=000000&amp;sliderovercolor=FFDD00&amp;buttoncolor=000000&amp;buttonovercolor=FFDD00&amp;textcolor=000000" />
                    <audio src="http://files.austinstone.org/audio/mp3/<?php echo trim($this->renderPosition('audio_file')); ?>" preload="none" controls style="width:400px;padding-top:5px"></audio>
                </object>
            </div>
            <a href="http://files.austinstone.org/audio/mp3/<?php echo trim($this->renderPosition('audio_file')); ?>"><div class="download_mp3">Download MP3</div></a>
        </div>
    <?php } ?>

</div><!-- /sermon -->