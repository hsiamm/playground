<?php
/**
* @package   com_zoo
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<?php $edit_hash = $this->app->submission->getSubmissionHash($submission->id, $item->type, $item->id); ?>
<?php $edit_link = $this->app->route->submission($submission, $item->type, $edit_hash, $item->id, 'itemedit'); ?>

<a href="<?php echo JRoute::_($edit_link); ?>" title="<?php echo JText::_('Edit Item'); ?>" class="item-icon edit-item"><?php echo JText::_('Edit Item'); ?></a>