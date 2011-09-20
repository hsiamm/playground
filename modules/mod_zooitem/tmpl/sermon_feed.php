<?php
/**
 * @package   ZOO Item
 * @file      list-h.php
 * @version   2.4.2
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<rss xmlns:media="http://search.yahoo.com/mrss/" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0"> 
    <channel>
        <title>The Austin Stone - Sermon Audio</title>
        <link>http://www.austinstone.org/</link>
        <language>en-us</language>
        <copyright>&#169;  The Austin Stone Community Church</copyright>
        <itunes:subtitle>Weekly sermons from The Austin Stone Community Church in Austin, TX</itunes:subtitle>
        <itunes:author>The Austin Stone</itunes:author>
        <itunes:summary>Weekly sermon podcast from The Austin Stone Community Church. The Austin Stone is a community of Jesus-followers living lives on mission. Find us online at austinstone.org.</itunes:summary>

        <description>Weekly sermon podcast from The Austin Stone Community Church. The Austin Stone is a community of Jesus-followers living lives on mission. Find us online at austinstone.org.</description>
        <itunes:owner>
            <itunes:name>The Austin Stone Community Church</itunes:name>
            <itunes:email>resources@austinstone.org</itunes:email>
        </itunes:owner>
        <itunes:image href="http://www.austinstone.org/images/sermonaudio.jpg" />
        <itunes:category text="Religion &amp; Spirituality">
        </itunes:category>
        <itunes:category text="Society &amp; Culture">
        </itunes:category>
        <itunes:keywords>
            matt,carter,sermon,god,jesus,stone,church,halim,suh,missional,community,austin
        </itunes:keywords>

        <?php if (!empty($items)) : ?>
            <?php $i = 0;
            foreach ($items as $item) : ?>
                <?php echo $renderer->render('item.' . $layout, compact('item', 'params')); ?>
                <?php $i++;
            endforeach; ?>
        <?php endif; ?>

    </channel>
</rss>
