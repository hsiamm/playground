<?php
/**
 * @package   com_zoo Component
 * @file      item.php
 * @version   2.4.10 June 2011
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

// include assets css/js
if (strtolower(substr($GLOBALS[($this->app->joomla->isVersion('1.5') ? 'mainframe' : 'app')]->getTemplate(), 0, 3)) != 'yoo') {
    $this->app->document->addStylesheet('media:zoo/assets/css/reset.css');
}
$this->app->document->addStylesheet($this->template->resource . 'assets/css/zoo.css');

$css_class = $this->application->getGroup() . '-' . $this->template->name;
?>
<?php
/**
 * @package   com_zoo Component
 * @file      category.php
 * @version   2.4.10 June 2011
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

// include assets css/js
if (strtolower(substr($GLOBALS[($this->app->joomla->isVersion('1.5') ? 'mainframe' : 'app')]->getTemplate(), 0, 3)) != 'yoo') {
    $this->app->document->addStylesheet('media:zoo/assets/css/reset.css');
}
$this->app->document->addStylesheet($this->template->resource . 'assets/css/zoo.css');

// show description only if it has content
if (!$this->application->description) {
    $this->params->set('template.show_description', 0);
}

// show title only if it has content
if (!$this->application->getParams()->get('content.title')) {
    $this->params->set('template.show_title', 0);
}

// show image only if an image is selected
if (!($image = $this->application->getImage('content.image'))) {
    $this->params->set('template.show_image', 0);
}

$css_class = $this->application->getGroup() . '-' . $this->template->name;
?>

<div class="twocol">
    <?php if ($this->params->get('template.show_title')) : ?>
        <h1><?php echo $this->application->getParams()->get('content.title') ?></h1>
    <?php endif; ?>

    <?php if ($this->application->getParams()->get('content.subtitle')) : ?>
        <p><?php echo $this->application->getParams()->get('content.subtitle') ?></p>
    <?php endif; ?>
</div>

<div class="container">

    <div id="sermon_scroll_two"><!-- page stops scrolling here -->

        <div class="twocol">
            {modulepos current_sermon_sermons}

            <div class="sermon_sort">
                <h4 class="yellow nomar">SORT THROUGH SERMONS:</h4>
                <h2 class="white">
                    <a id="month" href="#">BY MONTH</a>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;<a id="series" href="#">BY SERIES</a>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;<a id="speaker" href="#">BY SPEAKER</a>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;<a href="/resources/sermons/">ALL</a></h2>
                    
                <div id="series_sort" class="sermon_sort_options">
                    <ul class="left">
                    	<a href="/resources/sermons/category/the-gospel-of-mark"><li>Gospel of Mark</li></a>
                        <a href="/resources/sermons/category/counterfeit-love"><li>Counterfeit Love</li></a>
                        <a href="/resources/sermons/category/christmas-in-the-valley-of-vision "><li>Christmas in the Valley of Vision</li></a>                     
                        <a href="/resources/sermons/category/from-israel"><li>From Israel</li></a>
                        <a href="/resources/sermons/category/dna"><li>DNA</li></a>
                        <a href="/resources/sermons/category/heaven-hell"><li>Heaven & Hell</li></a>
                        <a href="/resources/sermons/category/the-history-of-redemption"><li>The History of Redemption</li></a>
                        <a href="/resources/sermons/category/temptation"><li>Temptation</li></a>
                        <a href="/resources/sermons/category/genesis"><li>Genesis</li></a>
                        <a href="/resources/sermons/category/the-gospel-according-to-noah"><li>The Gospel According to Noah</li></a>
                        <a href="/resources/sermons/category/biblical-manhood"><li>Biblical Manhood</li></a>
                        <a href="/resources/sermons/category/and-you-will-be-healed"><li>And You Will Be Healed</li></a>
                        <a href="/resources/sermons/category/1-corinthians"><li>1 Corinthians</li></a>
                        <a href="/resources/sermons/category/singleness-marriage-sex-and-divorce"><li>Singleness, Marriage, Sex, and Divorce</li></a>
                        <a href="/resources/sermons/category/a-church-for-the-city"><li>A Church for the City</li></a>
                        <a href="/resources/sermons/category/we-are-the-church-together"><li>We are the Church, Together</li></a>
                        <a href="/resources/sermons/category/the-seve-sayings-of-jesus-on-the-cross"><li>The Seven Sayings of Jesus on the Cross</li></a>
                        <a href="/resources/sermons/category/advent"><li>Advent</li></a>
                    </ul>
                    <ul class="right">
                        <a href="/resources/sermons/category/halleluha-what-a-savior"><li>Hallelujah! What a Savior</li></a>
                        <a href="/resources/sermons/category/vision-series-2010"><li>Vision Series 2010</li></a>
                        <a href="/resources/sermons/category/summer-preaching-series"><li>Summer Preaching Series</li></a>
                        <a href="/resources/sermons/category/faith-is"><li>Faith Is:</li></a>
                        <a href="/resources/sermons/category/sin-vs"><li>Sin vs. ____</li></a>
                        <a href="/resources/sermons/category/blueprint"><li>Blueprint</li></a>
                        <a href="/resources/sermons/category/if-christ-has-been-raised"><li>If Christ Has Been Raised</li></a>
                        <a href="/resources/sermons/category/the-good-news-of-recession"><li>The Good News of Recession</li></a>
                        <a href="/resources/sermons/category/o-holy-night"><li>O Holy Night</li></a>
                        <a href="/resources/sermons/category/money-matters"><li>Money Matters</li></a>
                        <a href="/resources/sermons/category/why-churches-die"><li>Why Churches Die</li></a>
                        <a href="/resources/sermons/category/from-this-day-forward"><li>From This Day Forward</li></a>
                        <a href="/resources/sermons/category/you-are-the-church"><li>You Are the Church</li></a>
                    </ul>
                    <div style="clear:both;"></div>
                </div>

                <div id="month_sort" class="sermon_sort_options">
                    <div class="one_month">
                        <h4>2011:</h4>
                        <ul class="months">
                            <a href="/resources/sermons/tag/sermons/Jan 2011"><li>Jan</li></a>
                            <a href="/resources/sermons/tag/sermons/Feb 2011"><li>Feb</li></a>
                            <a href="/resources/sermons/tag/sermons/Mar 2011"><li>Mar</li></a>
                            <a href="/resources/sermons/tag/sermons/Apr 2011"><li>Apr</li></a>
                            <a href="/resources/sermons/tag/sermons/May 2011"><li>May</li></a>
                            <a href="/resources/sermons/tag/sermons/Jun 2011"><li>Jun</li></a>
                            <a href="/resources/sermons/tag/sermons/Jul 2011"><li>Jul</li></a>
                            <a href="/resources/sermons/tag/sermons/Aug 2011"><li>Aug</li></a>
                            <a href="/resources/sermons/tag/sermons/Sep 2011"><li>Sep</li></a>
                            <a href="/resources/sermons/tag/sermons/Oct 2011"><li>Oct</li></a>
                            <a href="/resources/sermons/tag/sermons/Nov 2011"><li>Nov</li></a>
                            <a href="/resources/sermons/tag/sermons/Dec 2011"><li>Dec</li></a>
                        </ul>
                    </div>
                    <div class="one_month">
                        <h4>2010:</h4>
                        <ul class="months">
                            <a href="/resources/sermons/tag/sermons/Jan 2010"><li>Jan</li></a>
                            <a href="/resources/sermons/tag/sermons/Feb 2010"><li>Feb</li></a>
                            <a href="/resources/sermons/tag/sermons/Mar 2010"><li>Mar</li></a>
                            <a href="/resources/sermons/tag/sermons/Apr 2010"><li>Apr</li></a>
                            <a href="/resources/sermons/tag/sermons/May 2010"><li>May</li></a>
                            <a href="/resources/sermons/tag/sermons/Jun 2010"><li>Jun</li></a>
                            <a href="/resources/sermons/tag/sermons/Jul 2010"><li>Jul</li></a>
                            <a href="/resources/sermons/tag/sermons/Aug 2010"><li>Aug</li></a>
                            <a href="/resources/sermons/tag/sermons/Sep 2010"><li>Sep</li></a>
                            <a href="/resources/sermons/tag/sermons/Oct 2010"><li>Oct</li></a>
                            <a href="/resources/sermons/tag/sermons/Nov 2010"><li>Nov</li></a>
                            <a href="/resources/sermons/tag/sermons/Dec 2010"><li>Dec</li></a>
                        </ul>
                    </div>
                    <div class="one_month">
                        <h4>2009:</h4>
                        <ul class="months">
                            <a href="/resources/sermons/tag/sermons/Jan 2009"><li>Jan</li></a>
                            <a href="/resources/sermons/tag/sermons/Feb 2009"><li>Feb</li></a>
                            <a href="/resources/sermons/tag/sermons/Mar 2009"><li>Mar</li></a>
                            <a href="/resources/sermons/tag/sermons/Apr 2009"><li>Apr</li></a>
                            <a href="/resources/sermons/tag/sermons/May 2009"><li>May</li></a>
                            <a href="/resources/sermons/tag/sermons/Jun 2009"><li>Jun</li></a>
                            <a href="/resources/sermons/tag/sermons/Jul 2009"><li>Jul</li></a>
                            <a href="/resources/sermons/tag/sermons/Aug 2009"><li>Aug</li></a>
                            <a href="/resources/sermons/tag/sermons/Sep 2009"><li>Sep</li></a>
                            <a href="/resources/sermons/tag/sermons/Oct 2009"><li>Oct</li></a>
                            <a href="/resources/sermons/tag/sermons/Nov 2009"><li>Nov</li></a>
                            <a href="/resources/sermons/tag/sermons/Dec 2009"><li>Dec</li></a>
                        </ul>
                    </div>
                    <div class="one_month">
                        <h4>2008:</h4>
                        <ul class="months">
                            <a href="/resources/sermons/tag/sermons/Jan 2008"><li>Jan</li></a>
                            <a href="/resources/sermons/tag/sermons/Feb 2008"><li>Feb</li></a>
                            <a href="/resources/sermons/tag/sermons/Mar 2008"><li>Mar</li></a>
                            <a href="/resources/sermons/tag/sermons/Apr 2008"><li>Apr</li></a>
                            <a href="/resources/sermons/tag/sermons/May 2008"><li>May</li></a>
                            <a href="/resources/sermons/tag/sermons/Jun 2008"><li>Jun</li></a>
                            <a href="/resources/sermons/tag/sermons/Jul 2008"><li>Jul</li></a>
                            <a href="/resources/sermons/tag/sermons/Aug 2008"><li>Aug</li></a>
                            <a href="/resources/sermons/tag/sermons/Sep 2008"><li>Sep</li></a>
                            <a href="/resources/sermons/tag/sermons/Oct 2008"><li>Oct</li></a>
                            <a href="/resources/sermons/tag/sermons/Nov 2008"><li>Nov</li></a>
                            <a href="/resources/sermons/tag/sermons/Dec 2008"><li>Dec</li></a>
                        </ul>
                    </div>
                    <div class="one_month">
                        <h4>2007:</h4>
                        <ul class="months">
                            <a href="/resources/sermons/tag/sermons/Jan 2007"><li>Jan</li></a>
                            <a href="/resources/sermons/tag/sermons/Feb 2007"><li>Feb</li></a>
                            <a href="/resources/sermons/tag/sermons/Mar 2007"><li>Mar</li></a>
                            <a href="/resources/sermons/tag/sermons/Apr 2007"><li>Apr</li></a>
                            <a href="/resources/sermons/tag/sermons/May 2007"><li>May</li></a>
                            <a href="/resources/sermons/tag/sermons/Jun 2007"><li>Jun</li></a>
                            <a href="/resources/sermons/tag/sermons/Jul 2007"><li>Jul</li></a>
                            <a href="/resources/sermons/tag/sermons/Aug 2007"><li>Aug</li></a>
                            <a href="/resources/sermons/tag/sermons/Sep 2007"><li>Sep</li></a>
                            <a href="/resources/sermons/tag/sermons/Oct 2007"><li>Oct</li></a>
                            <a href="/resources/sermons/tag/sermons/Nov 2007"><li>Nov</li></a>
                            <a href="/resources/sermons/tag/sermons/Dec 2007"><li>Dec</li></a>
                        </ul>
                    </div>
                    <div class="one_month">
                        <h4>2006:</h4>
                        <ul class="months">
                            <a href="/resources/sermons/tag/sermons/Jan 2006"><li>Jan</li></a>
                            <a href="/resources/sermons/tag/sermons/Feb 2006"><li>Feb</li></a>
                            <a href="/resources/sermons/tag/sermons/Mar 2006"><li>Mar</li></a>
                            <a href="/resources/sermons/tag/sermons/Apr 2006"><li>Apr</li></a>
                            <a href="/resources/sermons/tag/sermons/May 2006"><li>May</li></a>
                            <a href="/resources/sermons/tag/sermons/Jun 2006"><li>Jun</li></a>
                            <a href="/resources/sermons/tag/sermons/Jul 2006"><li>Jul</li></a>
                            <a href="/resources/sermons/tag/sermons/Aug 2006"><li>Aug</li></a>
                            <a href="/resources/sermons/tag/sermons/Sep 2006"><li>Sep</li></a>
                            <a href="/resources/sermons/tag/sermons/Oct 2006"><li>Oct</li></a>
                            <a href="/resources/sermons/tag/sermons/Nov 2006"><li>Nov</li></a>
                            <a href="/resources/sermons/tag/sermons/Dec 2006"><li>Dec</li></a>
                        </ul>
                    </div>
                    <div class="one_month">
                        <h4>2005:</h4>
                        <ul class="months">
                            <a href="/resources/sermons/tag/sermons/Jan 2005"><li>Jan</li></a>
                            <a href="/resources/sermons/tag/sermons/Feb 2005"><li>Feb</li></a>
                            <a href="/resources/sermons/tag/sermons/Mar 2005"><li>Mar</li></a>
                            <a href="/resources/sermons/tag/sermons/Apr 2005"><li>Apr</li></a>
                            <a href="/resources/sermons/tag/sermons/May 2005"><li>May</li></a>
                            <a href="/resources/sermons/tag/sermons/Jun 2005"><li>Jun</li></a>
                            <a href="/resources/sermons/tag/sermons/Jul 2005"><li>Jul</li></a>
                            <a href="/resources/sermons/tag/sermons/Aug 2005"><li>Aug</li></a>
                            <a href="/resources/sermons/tag/sermons/Sep 2005"><li>Sep</li></a>
                            <a href="/resources/sermons/tag/sermons/Oct 2005"><li>Oct</li></a>
                            <a href="/resources/sermons/tag/sermons/Nov 2005"><li>Nov</li></a>
                            <a href="/resources/sermons/tag/sermons/Dec 2005"><li>Dec</li></a>
                        </ul>
                    </div>
                </div>

                <div id="speaker_sort" class="sermon_sort_options">
                    <h4>
                        <a href="/resources/sermons/tag/sermons/Matt Carter">Matt Carter</a>
                        <a href="/resources/sermons/tag/sermons/Jeff Mangum">Jeff Mangum</a>
                        <a href="/resources/sermons/tag/sermons/Halim Suh">Halim Suh</a>
                    </h4>
                </div>

                <div id="speaker_sort" class="sermon_sort_options">
                    <ul class="left">
                        <a href="/resources/sermons/tag/sermons/Matt Carter"><li>Matt Carter</li></a>
                        <a href="/resources/sermons/tag/sermons/Jeff Mangum"><li>Jeff Mangum</li></a>
                        <a href="/resources/sermons/tag/sermons/Halim Suh"><li>Halim Suh</li></a>
                    </ul>
                    <div style="clear:both;"></div>
                </div>

                <!--<div class="sermon_search">
                    <input type="text" name="sermon_search" class="sermon_search_bar">
                    <a type="submit" class="sermon_search_submit"><h4>Submit</h4></a>
                </div>-->

                <div style="clear:both;">&nbsp;</div>
            </div>
        </div><!-- /sermon_sort -->

        <div style="clear:both;">&nbsp;</div>

    </div>

    <div style="clear:both;"></div>

    <div id="sermon_scroll_one">
        <div class="twocol">
            <div class="sermon_pagination">
                <h4 class="nomar">PAGES: &nbsp;1</h4>
            </div>
        </div>
    </div>

    <div id="sermon_column">
        <div class="twocol">
            <?php if ($this->renderer->pathExists('item/' . $this->item->type)) : ?>
                <?php echo $this->renderer->render('item.' . $this->item->type . '.full', array('view' => $this, 'item' => $this->item)); ?>
            <?php else : ?>
                <?php echo $this->renderer->render('item.full', array('view' => $this, 'item' => $this->item)); ?>
                <?php echo $this->app->comment->renderComments($this, $this->item); ?>
            <?php endif; ?>
        </div> <!-- /twocol -->
    </div> <!-- /sermon_column -->
</div>