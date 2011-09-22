<?php
/**
 * @package   ZOO Item
 * @file      default.php
 * @version   2.4.2
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
date_default_timezone_set('America/Chicago');
?>

<item>
    <title>
    <?php echo $this->renderPosition('title'); 
    if ($this->checkPosition('categories'))
            echo '[' . trim($this->renderPosition('categories')) . ']'; ?>
    </title>
    <author><?php echo trim($this->renderPosition('speaker')); ?></author>
    <pubDate><?php echo date(r, strtotime($this->renderPosition('date'))); ?></pubDate>
    <description><?php echo $this->renderPosition('passage'); ?></description>
    <artwork></artwork>
    <itunes:author><?php echo trim($this->renderPosition('speaker')); ?></itunes:author>
    <itunes:subtitle><?php echo trim($this->renderPosition('passage')); ?></itunes:subtitle>
    <itunes:summary><?php echo str_replace('&nbsp;','',$this->renderPosition('description')); ?></itunes:summary>
    <enclosure url="http://files.austinstone.org/audio/mp3/<?php echo trim($this->renderPosition('mp3')); ?>" length="<?php echo trim($this->renderPosition('size')); ?>" type="audio/x-mp3" />
    <link>http://files.austinstone.org/audio/mp3/<?php echo trim($this->renderPosition('mp3')); ?></link>
    <guid>http://austinstone.org<?php $arr = explode('"',$this->renderPosition('guid'),3); echo $arr[1]; ?></guid>
    <itunes:duration><?php echo $this->renderPosition('duration'); ?></itunes:duration>
</item>