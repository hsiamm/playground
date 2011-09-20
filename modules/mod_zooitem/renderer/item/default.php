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

$media_position = $params->get('media_position', 'top');
?>

<?php if ($this->checkPosition('link')) { ?>
    <a href="<?php echo trim($this->renderPosition('link')); ?>">
        <?php echo $this->renderPosition('title'); ?>
    </a>
<?php
} else {
    echo $this->renderPosition('title');
}
?>