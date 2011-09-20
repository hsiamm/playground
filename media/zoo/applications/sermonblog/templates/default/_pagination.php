<?php
/**
 * @package   com_zoo Component
 * @file      _pagination.php
 * @version   2.4.10 June 2011
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<?php if ($pagination = $this->pagination->render($this->pagination_link)) { ?>
    <div class="sermon_pagination">
        <h4 class="nomar">PAGES: &nbsp;<?php echo $pagination; ?></h4>
    </div>
<?php } else { ?>
    <div class="sermon_pagination">
        <h4 class="nomar">PAGES: &nbsp;1</h4>
    </div>
<?php } ?>
