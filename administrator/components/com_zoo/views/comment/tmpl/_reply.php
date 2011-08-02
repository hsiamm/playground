<?php
/**
* @package   com_zoo Component
* @file      _reply.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>
<tr id="edit-comment-editor">
	<td colspan="4">
		<div class="head">Reply to Comment</div>
		<div class="content">
			<textarea name="content" cols="" rows=""></textarea>
		</div>
		<div class="actions">
			<button class="save" type="button"><?php echo JText::_('Submit Reply'); ?></button>
			<a href="#" class="cancel"><?php echo JText::_('Cancel'); ?></a>
		</div>
		<input type="hidden" name="cid" value="0" />
		<input type="hidden" name="parent_id" value="<?php echo $this->cid; ?>" />
	</td>
</tr>