<?php
/**
 * @version   $Id: view.html.php 414 2009-08-05 20:08:04Z edo888 $
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

jimport('joomla.application.component.view');

/**
 * Working Copy Child View class
 *
 */
class WCPViewChild extends JView {

    /**
     * Display the child
     *
     * @access public
     */
	function display($tpl = null) {
	    JHTML::_('behavior.tooltip');

	    $db =& JFactory::getDBO();

        JToolBarHelper::title(JText::_('WCP Manager') . ': <small><small>[ ' . (JRequest::getVar('task', 'edit') == 'edit' ? JText::_('Edit Child') : JText::_('New Child')) . ' ]</small></small>', 'generic.png');
        JToolBarHelper::save();
        JToolBarHelper::apply();
        JRequest::getVar('task', 'edit') == 'edit' ? JToolBarHelper::cancel('cancel', 'Close') : JToolBarHelper::cancel();
        JToolBarHelper::help('screen.wcp.new', true);

	    // Get data from the model
        $child =& $this->get('Data');

        // Generate Random Site ID
	    if(JRequest::getVar('task', 'edit') == 'add') {
            jimport('joomla.user.helper');
            $secret = JUserHelper::genRandomPassword(16);
        } else {
            $secret = $child->sid;
        }

        global $mainframe;

        // Get child params
        if(JRequest::getVar('task', 'edit') == 'add') {
            $child->path = './child';

            $exclude_files = array();
            $exclude_files[] = './includes';
            $exclude_files[] = './libraries';
            $exclude_files[] = './xmlrpc';
            $exclude_files[] = './configuration.php';
            $exclude_files[] = './administrator/help';
            $exclude_files[] = './administrator/images';
            $exclude_files[] = './administrator/includes';
            $exclude_files[] = './administrator/templates';
            $exclude_files[] = './plugins/editors/tinymce';
            $exclude_files[] = './plugins/system/legacy';
            $exclude_files[] = './templates/beez';
            $exclude_files[] = './templates/ja_purity';
            $exclude_files[] = './templates/rhuk_milkyway';
            $exclude_files[] = './templates/system';

            $dont_copy_files = array();
            $dont_copy_files[] = './cache';
            $dont_copy_files[] = './logs';
            $dont_copy_files[] = './tmp';
            $dont_copy_files[] = './installation';
            $dont_copy_files[] = './administrator/backups';
            $dont_copy_files[] = './administrator/cache';

            // Don't copy other childs
            $db->setQuery('select path from #__wcp');
            $dont_copy_files = array_merge($dont_copy_files, $db->loadResultArray());

            $exclude_tables = array();
            $exclude_tables[] = '#__core_acl_aro';
            $exclude_tables[] = '#__core_acl_aro_groups';
            $exclude_tables[] = '#__core_acl_aro_map';
            $exclude_tables[] = '#__core_acl_aro_sections';
            $exclude_tables[] = '#__core_acl_groups_aro_map';
            $exclude_tables[] = '#__groups';
            $exclude_tables[] = '#__migration_backlinks';
            $exclude_tables[] = '#__wcp';

            $dont_copy_tables = array();
            $dont_copy_tables[] = '#__core_log_items';
            $dont_copy_tables[] = '#__core_log_searches';
            $dont_copy_tables[] = '#__session';
            $dont_copy_tables[] = '#__stats_agents';
            $dont_copy_tables[] = '#__log_queries';

            sort($exclude_files);
            sort($dont_copy_files);
            sort($exclude_tables);
            sort($dont_copy_tables);

            $database = new JObject;
            $database->set('host', $mainframe->getCfg('host'));
            $database->set('user', $mainframe->getCfg('user'));
            $database->set('password', $mainframe->getCfg('password'));
            $database->set('database', $mainframe->getCfg('db'));
            $database->set('prefix', 'wcp_');

            $master_db = new JObject;
            $master_db->set('host', $mainframe->getCfg('host'));
            $master_db->set('user', $mainframe->getCfg('user'));
            $master_db->set('password', $mainframe->getCfg('password'));
            $master_db->set('database', $mainframe->getCfg('db'));
            $master_db->set('prefix', $mainframe->getCfg('dbprefix'));

            $ftp = new JObject;
            $ftp->set('enable', $mainframe->getCfg('ftp_enable'));
            $ftp->set('host', $mainframe->getCfg('ftp_host'));
            $ftp->set('port', $mainframe->getCfg('ftp_port'));
            $ftp->set('user', $mainframe->getCfg('ftp_user'));
            $ftp->set('pass', $mainframe->getCfg('ftp_pass'));
            $ftp->set('root', $mainframe->getCfg('ftp_root'));
        } else {
            $params = new JParameter($child->params);
            $exclude_files = json_decode($params->get('exclude_files'));
            $dont_copy_files = json_decode($params->get('dont_copy_files'));
            $exclude_tables = json_decode($params->get('exclude_tables'));
            $dont_copy_tables = json_decode($params->get('dont_copy_tables'));
            $database = json_decode($params->get('database'));
            $master_db = json_decode($params->get('master_db'));
            $ftp = json_decode($params->get('ftp'));
        }

        $this->assignRef('exclude_files', $exclude_files);
        $this->assignRef('dont_copy_files', $dont_copy_files);
        $this->assignRef('exclude_tables', $exclude_tables);
        $this->assignRef('dont_copy_tables', $dont_copy_tables);
        $this->assignRef('database', $database);
        $this->assignRef('master_db', $master_db);
        $this->assignRef('ftp', $ftp);
        $this->assignRef('secret', $secret);
        $this->assignRef('item', $child);
        parent::display($tpl);
	}

}