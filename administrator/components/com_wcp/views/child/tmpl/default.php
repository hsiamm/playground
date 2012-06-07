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
$document->addStyleDeclaration('.icon-32-save {background-image:url(./components/com_wcp/images/add.png) !important;}');
$document->addStyleDeclaration('.icon-32-apply {background-image:url(./components/com_wcp/images/commit.png) !important;}');
$document->addStyleDeclaration('.icon-32-cancel {background-image:url(./components/com_wcp/images/delete.png) !important;}');
$document->addStyleDeclaration('.icon-32-help {background-image:url(./components/com_wcp/images/help.png) !important;}');
?>
<script type="text/javascript">
function submitbutton(pressbutton) {
    var form = document.adminForm;
    if (pressbutton == 'cancel') {
        submitform(pressbutton);
        return;
    }

    // do field validation
    if (form.name.value == ''){
        alert("<?php echo JText::_('Child must have a title', true); ?>");
    } else {
        submitform(pressbutton);
    }
}

function addExcludeFile() {
	var n = document.getElementsByName('exclude_files[]').length + 1;
	var a = document.getElementById('exclude_files_add');

    var newEntry = document.createElement('input');
    newEntry.setProperty('class', 'inputbox');
    newEntry.setProperty('type', 'text');
    newEntry.setProperty('name', 'exclude_files[]');
    newEntry.setProperty('id', 'exclude_files_' + n);
    newEntry.setProperty('size', '60');
    newEntry.setProperty('style', 'margin-bottom:2px;');

    var lineBreak = document.createElement('br');
    var spacer = document.createElement('span');
    spacer.innerHTML = '&nbsp;';

    lineBreak.inject(a, 'before');
    newEntry.inject(a, 'before');
    spacer.inject(a, 'before');
}

function addDoNotCopyFile() {
    var n = document.getElementsByName('dont_copy_files[]').length + 1;
    var a = document.getElementById('dont_copy_files_add');

    var newEntry = document.createElement('input');
    newEntry.setProperty('class', 'inputbox');
    newEntry.setProperty('type', 'text');
    newEntry.setProperty('name', 'dont_copy_files[]');
    newEntry.setProperty('id', 'dont_copy_files_' + n);
    newEntry.setProperty('size', '60');
    newEntry.setProperty('style', 'margin-bottom:2px;');

    var lineBreak = document.createElement('br');
    var spacer = document.createElement('span');
    spacer.innerHTML = '&nbsp;';

    lineBreak.inject(a, 'before');
    newEntry.inject(a, 'before');
    spacer.inject(a, 'before');
}

function addExcludeTable() {
    var n = document.getElementsByName('exclude_tables[]').length + 1;
    var a = document.getElementById('exclude_tables_add');

    var newEntry = document.createElement('input');
    newEntry.setProperty('class', 'inputbox');
    newEntry.setProperty('type', 'text');
    newEntry.setProperty('name', 'exclude_tables[]');
    newEntry.setProperty('id', 'exclude_tables_' + n);
    newEntry.setProperty('size', '60');
    newEntry.setProperty('style', 'margin-bottom:2px;');

    var lineBreak = document.createElement('br');
    var spacer = document.createElement('span');
    spacer.innerHTML = '&nbsp;';

    lineBreak.inject(a, 'before');
    newEntry.inject(a, 'before');
    spacer.inject(a, 'before');
}

function addDoNotCopyTable() {
    var n = document.getElementsByName('dont_copy_tables[]').length + 1;
    var a = document.getElementById('dont_copy_tables_add');

    var newEntry = document.createElement('input');
    newEntry.setProperty('class', 'inputbox');
    newEntry.setProperty('type', 'text');
    newEntry.setProperty('name', 'dont_copy_tables[]');
    newEntry.setProperty('id', 'dont_copy_tables_' + n);
    newEntry.setProperty('size', '60');
    newEntry.setProperty('style', 'margin-bottom:2px;');

    var lineBreak = document.createElement('br');
    var spacer = document.createElement('span');
    spacer.innerHTML = '&nbsp;';

    lineBreak.inject(a, 'before');
    newEntry.inject(a, 'before');
    spacer.inject(a, 'before');
}
</script>

<form action="index.php" method="post" name="adminForm">

    <table class="noshow">
    <tr>
        <td width="60%">
            <fieldset class="adminform">
                <legend><?php echo JText::_('General'); ?></legend>

                <table class="admintable">
                <tr>
                    <td width="200" class="key">
                        <label for="name">
                            <?php echo JText::_('Name'); ?>
                        </label>
                    </td>
                    <td>
                        <input class="inputbox" type="text" name="name" id="name" size="60" value="<?php echo @$this->item->name; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="200" class="key">
                        <label for="path">
                            <?php echo JText::_('Path'); ?>
                        </label>
                    </td>
                    <td>
                        <input class="inputbox" type="text" name="path" id="path" size="60" value="<?php echo $this->item->path; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="200" class="key">
                        <?php echo JText::_('Site ID'); ?>
                    </td>
                    <td>
                        <b><?php echo $this->secret; ?></b>
                    </td>
                </tr>
                <?php if(JRequest::getVar('task') == 'add'): ?>
                <tr>
                    <td width="200" class="key">
                        <label for="name">
                            <?php echo JText::_('Copy Database'); ?>
                        </label>
                    </td>
                    <td>
                        <?php echo JHTML::_('select.booleanlist', 'copy_db', 'class="inputbox"', 1); ?>
                    </td>
                </tr>
                <tr>
                    <td width="200" class="key">
                        <label for="name">
                            <?php echo JText::_('Copy Files'); ?>
                        </label>
                    </td>
                    <td>
                        <?php echo JHTML::_('select.booleanlist', 'copy_files', 'class="inputbox"', ini_get('safe_mode') ? 0 : 1); ?>
                        <?php if(ini_get('safe_mode')): ?>
                        &nbsp;
                        <span class="error hasTip" title="<?php echo JText::_('Warning - PHP safe mode is on'); ?>::<?php JText::printf('EXEC_TIME_WARNING', ini_get('max_execution_time')); ?>">
                            <img src="<?php echo JURI::root(); ?>includes/js/ThemeOffice/warning.png" border="0"  alt="" />
                        </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endif; ?>
                </table>
            </fieldset>

            <fieldset class="adminform">
                <legend><?php echo JText::_('Child Database Settings'); ?></legend>

                <table class="admintable">
                <tr>
                    <td width="200" class="key">
                        <label for="host">
                            <?php echo JText::_('Host'); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="host" id="host" size="30" value="<?php echo $this->database->host; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="200" class="key">
                        <label for="user">
                            <?php echo JText::_('Username'); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="user" id="user" size="30" value="<?php echo $this->database->user; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="200" class="key">
                        <label for="password">
                            <?php echo JText::_('Password'); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="password" name="password" id="password" size="30" value="<?php echo $this->database->password; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="200" class="key">
                        <label for="database">
                            <?php echo JText::_('Database'); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="database" id="database" size="30" value="<?php echo $this->database->database; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="200" class="key">
                        <label for="prefix">
                            <?php echo JText::_('Prefix'); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="prefix" id="prefix" size="30" value="<?php echo $this->database->prefix; ?>" />
                    </td>
                </tr>
                </table>
            </fieldset>

            <fieldset class="adminform">
                <legend><?php echo JText::_('Master Database Settings'); ?></legend>

                <table class="admintable">
                <tr>
                    <td width="200" class="key">
                        <label for="master_host">
                            <?php echo JText::_('Host'); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="master_host" id="master_host" size="30" value="<?php echo $this->master_db->host; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="200" class="key">
                        <label for="master_user">
                            <?php echo JText::_('Username'); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="master_user" id="master_user" size="30" value="<?php echo $this->master_db->user; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="200" class="key">
                        <label for="master_password">
                            <?php echo JText::_('Password'); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="password" name="master_password" id="master_password" size="30" value="<?php echo $this->master_db->password; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="200" class="key">
                        <label for="master_database">
                            <?php echo JText::_('Database'); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="master_database" id="master_database" size="30" value="<?php echo $this->master_db->database; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="200" class="key">
                        <label for="master_prefix">
                            <?php echo JText::_('Prefix'); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="master_prefix" id="master_prefix" size="30" value="<?php echo $this->master_db->prefix; ?>" />
                    </td>
                </tr>
                </table>
            </fieldset>

            <fieldset class="adminform">
                <legend><?php echo JText::_('FTP Settings'); ?></legend>

                <table class="admintable">
                <tr>
                    <td width="200" class="key">
                        <?php echo JText::_('Enabled'); ?>
                    </td>
                    <td>
                        <?php echo JHTML::_('select.booleanlist', 'ftp_enable', 'class="inputbox"', $this->ftp->enable); ?>
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="ftp_host">
                            <?php echo JText::_('Host'); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="ftp_host" id="ftp_host" size="30" value="<?php echo $this->ftp->host; ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="ftp_port">
                            <?php echo JText::_('Port'); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="ftp_port" id="ftp_port" size="30" value="<?php echo $this->ftp->port; ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="ftp_user">
                            <?php echo JText::_('Username'); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="ftp_user" id="ftp_user" size="30" value="<?php echo $this->ftp->user; ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="ftp_pass">
                            <?php echo JText::_('Password'); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="password" name="ftp_pass" id="ftp_pass" size="30" value="<?php echo $this->ftp->pass; ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="ftp_root">
                            <?php echo JText::_('Root'); ?>
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="ftp_root" id="ftp_root" size="30" value="<?php echo $this->ftp->root; ?>" />
                    </td>
                </tr>
                </table>
            </fieldset>
        </td>
        <td width="40%">
            <fieldset class="adminform">
                <legend><?php echo JText::_('Exclude Files'); ?></legend>

                <table class="admintable">
                <tr>
                    <td width="200" class="key" valign="top">
                        <label for="exclude_files_1">
                            <?php echo JText::_('Path'); ?>
                        </label>
                    </td>
                    <td>
                        <?php for($i = 0, $n = count($this->exclude_files); $i < $n; $i++): ?>
                        <input class="inputbox" type="text" name="exclude_files[]" id="exclude_files_<?php echo $i + 1; ?>" size="60" style="margin-bottom:2px;" value="<?php echo $this->exclude_files[$i]; ?>" />
                        <?php if($i + 1 < $n): ?><br />
                        <?php else: ?><a href="javascript:addExcludeFile();" id="exclude_files_add"><?php echo JText::_('Add row'); ?></a>
                        <?php endif; ?>
                        <?php endfor; ?>
                    </td>
                </tr>
                </table>
            </fieldset>

            <fieldset class="adminform">
                <legend><?php echo JText::_('Do Not Copy Files'); ?></legend>

                <table class="admintable">
                <tr>
                    <td width="200" class="key" valign="top">
                        <label for="dont_copy_files_1">
                            <?php echo JText::_('Path'); ?>
                        </label>
                    </td>
                    <td>
                        <?php for($i = 0, $n = count($this->dont_copy_files); $i < $n; $i++): ?>
                        <input class="inputbox" type="text" name="dont_copy_files[]" id="dont_copy_files_<?php echo $i + 1; ?>" size="60" style="margin-bottom:2px;" value="<?php echo $this->dont_copy_files[$i]; ?>" />
                        <?php if($i + 1 < $n): ?><br />
                        <?php else: ?><a href="javascript:addDoNotCopyFile();" id="dont_copy_files_add"><?php echo JText::_('Add row'); ?></a>
                        <?php endif; ?>
                        <?php endfor; ?>
                    </td>
                </tr>
                </table>
            </fieldset>

            <fieldset class="adminform">
                <legend><?php echo JText::_('Exclude Tables'); ?></legend>

                <table class="admintable">
                <tr>
                    <td width="200" class="key" valign="top">
                        <label for="exclude_tables_1">
                            <?php echo JText::_('Table Name'); ?>
                        </label>
                    </td>
                    <td>
                        <?php for($i = 0, $n = count($this->exclude_tables); $i < $n; $i++): ?>
                        <input class="inputbox" type="text" name="exclude_tables[]" id="exclude_tables_<?php echo $i + 1; ?>" size="60" style="margin-bottom:2px;" value="<?php echo $this->exclude_tables[$i]; ?>" />
                        <?php if($i + 1 < $n): ?><br />
                        <?php else: ?><a href="javascript:addExcludeTable();" id="exclude_tables_add"><?php echo JText::_('Add row'); ?></a>
                        <?php endif; ?>
                        <?php endfor; ?>
                    </td>
                </tr>
                </table>
            </fieldset>

            <fieldset class="adminform">
                <legend><?php echo JText::_('Do Not Copy Tables'); ?></legend>

                <table class="admintable">
                <tr>
                    <td width="200" class="key" valign="top">
                        <label for="dont_copy_tables_1">
                            <?php echo JText::_('Table Name'); ?>
                        </label>
                    </td>
                    <td>
                        <?php for($i = 0, $n = count($this->dont_copy_tables); $i < $n; $i++): ?>
                        <input class="inputbox" type="text" name="dont_copy_tables[]" id="dont_copy_tables_<?php echo $i + 1; ?>" size="60" style="margin-bottom:2px;" value="<?php echo $this->dont_copy_tables[$i]; ?>" />
                        <?php if($i + 1 < $n): ?><br />
                        <?php else: ?><a href="javascript:addDoNotCopyTable();" id="dont_copy_tables_add"><?php echo JText::_('Add row'); ?></a>
                        <?php endif; ?>
                        <?php endfor; ?>
                    </td>
                </tr>
                </table>
            </fieldset>
        </td>
    </tr>
    </table>

    <div class="clr"></div>

    <input type="hidden" name="option" value="com_wcp" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
    <input type="hidden" name="sid" value="<?php echo $this->secret; ?>" />
    <?php echo JHTML::_('form.token'); ?>

</form>