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
$document->addStyleDeclaration('.icon-32-new {background-image:url(./components/com_wcp/images/add.png) !important;}');
$document->addStyleDeclaration('.icon-32-sync {background-image:url(./components/com_wcp/images/sync.png) !important;}');
$document->addStyleDeclaration('.icon-32-commit {background-image:url(./components/com_wcp/images/commit.png) !important;}');
$document->addStyleDeclaration('.icon-32-revert {background-image:url(./components/com_wcp/images/revert.png) !important;}');
$document->addStyleDeclaration('.icon-32-refresh {background-image:url(./components/com_wcp/images/refresh.png) !important;}');
$document->addStyleDeclaration('.icon-32-back {background-image:url(./components/com_wcp/images/back.png) !important;}');
$document->addStyleDeclaration('.icon-32-help {background-image:url(./components/com_wcp/images/help.png) !important;}');
?>
<form action="index.php" method="post" name="adminForm">
    <table>
        <tr>
            <td width="100%">
                <?php echo JText::_('Internal Time'); ?>:
                <input type="text" title="Internal Time against which differences will be compared" size="22" class="text_area" value="<?php echo date('Y-m-d H:i:s', WCPHelper::getInternalTime()); ?>" name="internal_time" />
                <button onclick="submitbutton('refreshDiff')">Go</button>
            </td>
        </tr>
    </table>

    <table class="adminlist">
    <thead>
        <tr>
            <th width="5">
                <?php echo JText::_('NUM'); ?>
            </th>
            <th width="20">
                <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items)+count($this->db_items)+count($this->table_items); ?>);" />
            </th>
            <th>
                <?php echo JText::_('Path') . ' / ' . JText::_('Table Event'); ?>
            </th>
            <th width="180">
                <?php echo JText::_('Modified Date'); ?>
            </th>
        </tr>
    </thead>
    <?php
    $k = $i = 0;
    for($i = 0, $n = count($this->items); $i < $n; $i++) {
        $row = new JObject;
        $row->set('path', $this->items[$i][0]);
        $row->set('mdate', $this->items[$i][1]);
        $checked = JHTML::_('grid.id', $i, $row->path);
        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $i + 1; ?>
            </td>
            <td>
                <?php echo $checked; ?>
            </td>
            <td>
                <?php echo $row->path; ?>
            </td>
            <td align="center">
                <?php echo $row->mdate; ?>
            </td>
        </tr>
        <?php
        $k = 1 - $k;
    }

    for($j = 0, $n = count($this->db_items); $j < $n; $j++, $i++) {
        $row = new JObject;
        $row->set('id', $this->db_items[$j]->id);
        $row->set('action', '<font color="#0000cc">'.$this->db_items[$j]->action . '</font> <b>' . $this->db_items[$j]->table_name . '</b>');
        $row->set('mdate', $this->db_items[$j]->mdate);
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
                <?php echo $row->action; ?>
            </td>
            <td align="center">
                <?php echo $row->mdate; ?>
            </td>
        </tr>
        <?php
        $k = 1 - $k;
    }

    for($j = 0, $n = count($this->table_items); $j < $n; $j++, $i++) {
        $row = new JObject;
        $row->set('id', $this->table_items[$j]->id);
        $row->set('action', '<font color="#cc0000">'.$this->table_items[$j]->action . '</font> <b>' . $this->table_items[$j]->table_name . '</b>.<i>' . $this->table_items[$j]->table_key . '</i> = ' . $this->table_items[$j]->value);
        $row->set('mdate', date('r', $this->table_items[$j]->mdate));
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
                <?php echo $row->action; ?>
            </td>
            <td align="center">
                <?php echo $row->mdate; ?>
            </td>
        </tr>
        <?php
        $k = 1 - $k;
    }
    ?>
    </table>

<input type="hidden" name="option" value="com_wcp" />
<input type="hidden" name="task" value="<?php echo JRequest::getVar('task'); ?>" />
<input type="hidden" name="boxchecked" value="0" />
<?php echo JHTML::_('form.token'); ?>

</form>