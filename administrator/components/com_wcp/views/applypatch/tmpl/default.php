<?php
/**
 * @version   $Id: default.php 412 2009-07-31 02:57:34Z edo888 $
 * @copyright Copyright (C) 2009 Edvard Ananyan. All rights reserved.
 * @author    Edvard Ananyan <edo888@gmail.com>
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$document =& JFactory::getDocument();
$document->addStyleDeclaration('.icon-32-back {background-image:url(./components/com_wcp/images/back.png) !important;}');
$document->addStyleDeclaration('.icon-32-help {background-image:url(./components/com_wcp/images/help.png) !important;}');
?>
<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm">
    <table class="adminform">
    <tr>
        <th colspan="2"><?php echo JText::_('Upload Patch File'); ?></th>
    </tr>
    <tr>
        <td width="120">
            <label for="patch_file"><?php echo JText::_('Patch File'); ?>:</label>
        </td>
        <td>
            <input class="input_box" id="patch_file" name="patch_file" type="file" size="57" />
            <input class="button" type="button" value="<?php echo JText::_('Upload File and Patch'); ?>" onclick="submitbutton()" />
        </td>
    </tr>
    </table>

    <input type="hidden" name="type" value="" />
    <input type="hidden" name="task" value="applyPatch" />
    <input type="hidden" name="submitted" value="1" />
    <input type="hidden" name="option" value="com_wcp" />
    <?php echo JHTML::_('form.token'); ?>
</form>