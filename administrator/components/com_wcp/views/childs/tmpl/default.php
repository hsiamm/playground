<?php
/**
 * @version   $Id: default.php 426 2009-08-19 14:30:31Z edo888 $
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
$document->addStyleDeclaration('.icon-32-new {background-image:url(./components/com_wcp/images/add.png) !important;}');
$document->addStyleDeclaration('.icon-32-apply {background-image:url(./components/com_wcp/images/apply.png) !important;}');
$document->addStyleDeclaration('.icon-32-edit {background-image:url(./components/com_wcp/images/edit.png) !important;}');
$document->addStyleDeclaration('.icon-32-delete {background-image:url(./components/com_wcp/images/delete.png) !important;}');
$document->addStyleDeclaration('.icon-32-diff {background-image:url(./components/com_wcp/images/diff.png) !important;}');
$document->addStyleDeclaration('.icon-32-merge {background-image:url(./components/com_wcp/images/merge.png) !important;}');
$document->addStyleDeclaration('.icon-32-help {background-image:url(./components/com_wcp/images/help.png) !important;}');
?>
<form action="index.php" method="post" name="adminForm">
    <table class="adminlist">
    <thead>
        <tr>
            <th width="5">
                <?php echo JText::_('NUM'); ?>
            </th>
            <th width="20">
                <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
            </th>
            <th>
                <?php echo JHTML::_('grid.sort', JText::_('Name'), 'w.name', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th>
                <?php echo JHTML::_('grid.sort', JText::_('Path'), 'w.path', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th width="5">
                <?php echo JHTML::_('grid.sort', JText::_('ID'), 'w.id', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
        </tr>
    </thead>
    <?php
    $k = 0;
    for($i = 0, $n = count($this->items); $i < $n; $i++) {
        $row =& $this->items[$i];
        $checked = JHTML::_('grid.id', $i, $row->id);
        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $i + 1; ?>
            </td>
            <td>
                <?php echo $checked; ?>
            </td>
            <td>
                <a href="index.php?option=com_wcp&task=edit&cid[]=<?php echo $row->id; ?>"><?php echo $row->name; ?></a>
            </td>
            <td>
                <a href="<?php echo JURI::root().$row->path; ?>"><?php echo $row->path; ?></a>
            </td>
            <td>
                <?php echo $row->id; ?>
            </td>
        </tr>
        <?php
        $k = 1 - $k;
    }
    ?>
    </table>

<input type="hidden" name="option" value="com_wcp" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_('form.token'); ?>

</form>