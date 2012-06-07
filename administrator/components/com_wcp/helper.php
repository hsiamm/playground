<?php
/**
 * @version   $Id: helper.php 428 2009-09-13 20:32:48Z edo888 $
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

/**
 * Working Copy helper class
 *
 */
class WCPHelper {

    /**
     * Determines if the site is master
     *
     * @access public
     * @return boolean
     */
    function isMaster() {
        global $mainframe;
        $db =& JFactory::getDBO();

        $db->setQuery('select id from #__wcp where sid = "' . $mainframe->getCfg('secret') . '"');
        $db->query();
        return !(bool) $db->getNumRows();
    }

    /**
     * Returns the internal time, against which changes
     * will be determined
     *
     * @access public
     * @return int
     */
    function getInternalTime() {
        return JRequest::getVar('internal_time', filemtime(JPATH_ROOT.DS.'configuration.php'), 'COOKIE', 'int');
    }

    /**
     * Sets the internal time
     *
     * @access public
     * @param int Internal time
     */
    function setInternalTime($internal_time = null) {
        if($internal_time == null)
            $internal_time = strtotime(JRequest::getVar('internal_time', date('Y-m-d H:i:s', WCPHelper::getInternalTime()), 'POST'));
        setcookie('internal_time', $internal_time, time() + 36000, '/');
    }

    /**
     * Determines the primary key field of the table
     *
     * @access public
     * @param object DBO
     * @param string Table name
     * @return string
     */
    function getPrimaryKeyField($db, $table) {
        $db->setQuery('show columns from ' . $table);
        $fields = $db->loadObjectList();
        foreach($fields as $field) {
            if($field->Key == 'PRI')
                return $field->Field;
        }

        return '';
    }

    /**
     * Determines number of primary key fields
     *
     * @access public
     * @param object DBO
     * @param string Table name
     * @return int
     */
    function getPrimaryKeyCount($db, $table) {
        $table = str_replace('#__', $db->_table_prefix, $table);
        $db->setQuery("select count(*) from information_schema.columns where table_schema = database() and table_name = '$table' and column_key = 'PRI'");
        return $db->loadResult();
    }

    /**
     * Returns connection link to master database
     *
     * @access public
     * @return object JDatabaseMySQL
     */
    function &getMasterDBO() {
        global $mainframe;
        $db =& JFactory::getDBO();
        $db->setQuery("select params from #__wcp where sid = '" . $mainframe->getCfg('secret') . "'");
        $params = $db->loadResult();
        $params = new JParameter($params);
        $master_db = json_decode($params->get('master_db'));

        return new JDatabaseMySQL(array('host' => $master_db->host, 'user' => $master_db->user, 'password' => $master_db->password, 'database' => $master_db->database, 'prefix' => $master_db->prefix));
    }

    /**
     * Get the exclude files list of the child
     *
     * @access public
     * @param string The path, relative to which it will generate the exclude files
     * @return array
     */
    function getExcludeFiles($path = JPATH_ROOT) {
        global $mainframe;

        $db =& JFactory::getDBO();
        $db->setQuery("select path, params from #__wcp where sid = '" . $mainframe->getCfg('secret') . "'");
        $child = $db->loadObject();

        $params = new JParameter($child->params);
        $exclude_files = array_merge(json_decode($params->get('exclude_files')), json_decode($params->get('dont_copy_files')));
        $exclude_files[] = $child->path;
        foreach($exclude_files as $i => $exclude_file) {
            $exclude_files[$i] = str_replace('./', $path . DS, $exclude_file);
            $exclude_files[$i] = str_replace('/', DS, $exclude_files[$i]);
        }

        return $exclude_files;
    }

    /**
     * Get the exclude tables list of the child
     *
     * @access public
     * @return array
     */
    function getExcludeTables() {
        global $mainframe;
        $db =& JFactory::getDBO();

        $db->setQuery("select params from #__wcp where sid = '" . $mainframe->getCfg('secret') . "'");
        $params = $db->loadResult();
        $params = new JParameter($params);

        return array_merge(json_decode($params->get('exclude_tables')), json_decode($params->get('dont_copy_tables')));
    }

    /**
     * Creates a child from master
     *
     * @access public
     * @return boolean True on success, False on failure
     */
    function createChild() {
        // Try to set the script execution time to unlimited, if php is in safe mode there is no workaround
        @set_time_limit(0);

        global $mainframe;
        $master_db =& JFactory::getDBO();
        $child_db = new JDatabaseMySQL(array('host' => JRequest::getVar('host'), 'user' => JRequest::getVar('user'), 'password' => JRequest::getVar('password'), 'database' => JRequest::getVar('database'), 'prefix' => JRequest::getVar('prefix')));
        // Debug: $child_db->debug(1);

        if(!$child_db->connected()) {
            JError::raiseError(0, JText::_('Cannot connect to the child database'));
            return false;
        }

        // Insert new child to #__wcp
        $wcp_table = new TableWCP($master_db);
        $wcp_table->set('sid', JRequest::getVar('sid'));
        $wcp_table->set('name', JRequest::getVar('name'));
        $wcp_table->set('parent_sid', $mainframe->getCfg('secret'));
        $wcp_table->set('path', JRequest::getVar('path'));

        $params = new JParameter('');
        $params->set('exclude_files', json_encode(array_values(array_filter(JRequest::getVar('exclude_files'), 'strlen'))));
        $params->set('dont_copy_files', json_encode(array_values(array_filter(JRequest::getVar('dont_copy_files'), 'strlen'))));
        $params->set('exclude_tables', json_encode(array_values(array_filter(JRequest::getVar('exclude_tables'), 'strlen'))));
        $params->set('dont_copy_tables', json_encode(array_values(array_filter(JRequest::getVar('dont_copy_tables'), 'strlen'))));

        $database = new JObject;
        $database->set('host', JRequest::getVar('host'));
        $database->set('user', JRequest::getVar('user'));
        $database->set('password', JRequest::getVar('password'));
        $database->set('database', JRequest::getVar('database'));
        $database->set('prefix', JRequest::getVar('prefix'));
        $params->set('database', json_encode($database));

        $master_database = new JObject;
        $master_database->set('host', JRequest::getVar('master_host'));
        $master_database->set('user', JRequest::getVar('master_user'));
        $master_database->set('password', JRequest::getVar('master_password'));
        $master_database->set('database', JRequest::getVar('master_database'));
        $master_database->set('prefix', JRequest::getVar('master_prefix'));
        $params->set('master_db', json_encode($master_database));

        $ftp = new JObject;
        $ftp->set('enable', JRequest::getVar('ftp_enable'));
        $ftp->set('host', JRequest::getVar('ftp_host'));
        $ftp->set('port', JRequest::getVar('ftp_port'));
        $ftp->set('user', JRequest::getVar('ftp_user'));
        $ftp->set('pass', JRequest::getVar('ftp_pass'));
        $ftp->set('root', JRequest::getVar('ftp_root'));
        $params->set('ftp', json_encode($ftp));

        $wcp_table->set('params', $params->toString());

        $wcp_table->store();
        // Debug: echo '<pre>', print_r($wcp_table, true), '</pre>';

        // Create #__wcp_log_queries table
        $child_db->setQuery("create table #__log_queries (
                `id` int(11) unsigned not null auto_increment,
                `action` enum('insert', 'update', 'delete') not null,
                `table_name` varchar(50) not null,
                `table_key` varchar(50) not null,
                `value` varchar(50) not null,
                `date` timestamp not null default current_timestamp,
                primary key (`id`),
                unique key `id` (`id`),
                unique key `repeat` (`table_name`, `value`)
            ) engine=MyISAM default charset=utf8");
        $child_db->query();

        // Get all joomla tables from master
        $master_db->setQuery("show tables like '".$master_db->_table_prefix."%'");
        $master_tables = $master_db->loadResultArray();
        // Debug: echo '<pre>', print_r($master_tables, true), '</pre>';

        if(JRequest::getVar('copy_db') == 1) {
            // Copy all tables w/ data to the child
            foreach($master_tables as $master_table) {
                $master_table_ddl = array_pop($master_db->getTableCreate($master_table));
                $child_table = str_replace($master_db->_table_prefix, '#__', $master_table);
                $child_table_ddl = preg_replace('/'.$master_table.'/', $child_table, $master_table_ddl, 1);
                // Debug: echo '<pre>', $child_table_ddl, '</pre>';

                $child_db->setQuery($child_table_ddl);
                $child_db->query();

                if(!in_array($child_table, json_decode($params->get('dont_copy_tables')))) {
                    $master_db->setQuery('select * from ' . $master_table);
                    $master_rows = $master_db->loadObjectList();
                    foreach($master_rows as $master_row)
                        $child_db->insertObject($child_table, $master_row);

                    // Create triggers for child table if number of primary key fields is one
                    $child_table = str_replace('#__', $child_db->_table_prefix, $child_table);
                    if(self::getPrimaryKeyCount($child_db, $child_table) == 1) {
                        $key = self::getPrimaryKeyField($child_db, $child_table);
                        $child_db->setQuery("create trigger on_insert_$child_table after insert on $child_table for each row " .
                            "replace into #__log_queries (action, table_name, table_key, value) values('insert', '$child_table', '$key', new.$key)");
                        $child_db->query();

                        $child_db->setQuery("create trigger on_update_$child_table after update on $child_table for each row " .
                            "replace into #__log_queries (action, table_name, table_key, value) values('update', '$child_table', '$key', old.$key)");
                        $child_db->query();

                        $child_db->setQuery("create trigger on_delete_$child_table after delete on $child_table for each row " .
                            "replace into #__log_queries (action, table_name, table_key, value) values('delete', '$child_table', '$key', old.$key)");
                        $child_db->query();
                    }

                    // Increase child table auto_increment values
                    $child_db->setQuery("select auto_increment from information_schema.tables where table_schema = database() and table_name = '$child_table'");
                    $table_auto_increment = $child_db->loadResult();
                    if($table_auto_increment != '') {
                        $table_auto_increment *= 10; // TODO: Select different multiplier depending on $table_auto_increment value
                        $child_db->setQuery("alter table $child_table auto_increment = $table_auto_increment");
                        $child_db->query();
                    }
                }
            }
        } else {
            JError::raiseNotice(0, JText::_('You still need to copy all the tables to the child database manually.'));
        }

        if(JRequest::getVar('copy_files') == 1) {
            $dont_copy_files = json_decode($params->get('dont_copy_files'));
            foreach($dont_copy_files as $i => $dont_copy_file) {
                $dont_copy_files[$i] = str_replace('./', JPATH_ROOT . DS, $dont_copy_file);
                $dont_copy_files[$i] = str_replace('/', DS, $dont_copy_files[$i]);

                if(is_dir(JPATH_ROOT.DS.$dont_copy_file))
                    JFolder::create(JPATH_ROOT.DS.JRequest::getVar('path').DS.$dont_copy_file);
            }

            $master_files = JFolderWCP::files(JPATH_ROOT, array_merge($dont_copy_files, array('.svn', 'CVS')));
            // Debug: echo '<pre>', print_r($master_files, true), '</pre>';

            foreach($master_files as $master_file) {
                $dest = str_replace(JPATH_ROOT, JPATH_ROOT.DS.JRequest::getVar('path'), $master_file);
                if(!is_dir(dirname($dest)))
                    JFolder::create(dirname($dest));
                JFile::copy($master_file, $dest);

                // Set last modified date of child file to the same as in master
                touch($dest, filemtime($master_file));
            }
        } else {
            JError::raiseNotice(0, JText::_('You still need to copy all the files to the child directory manually.'));
        }

        // Configure the child
        $config = new JRegistry('config');
        $config_array = array();

        // SITE SETTINGS
        $config_array['offline'] = $mainframe->getCfg('offline');
        $config_array['editor'] = $mainframe->getCfg('editor');
        $config_array['list_limit'] = $mainframe->getCfg('list_limit');
        $config_array['helpurl'] = $mainframe->getCfg('helpurl');

        // DEBUG
        $config_array['debug'] = $mainframe->getCfg('debug');
        $config_array['debug_lang'] = $mainframe->getCfg('debug_lang');

        // SEO SETTINGS
        $config_array['sef'] = $mainframe->getCfg('sef');
        $config_array['sef_rewrite'] = $mainframe->getCfg('sef_rewrite');
        $config_array['sef_suffix'] = $mainframe->getCfg('sef_suffix');

        // FEED SETTINGS
        $config_array['feed_limit'] = $mainframe->getCfg('feed_limit');
        $config_array['feed_email'] = $mainframe->getCfg('feed_email');

        // SERVER SETTINGS
        $config_array['secret'] = JRequest::getVar('sid', 0, 'post', 'string');
        $config_array['gzip'] = $mainframe->getCfg('gzip');
        $config_array['error_reporting'] = $mainframe->getCfg('error_reporting');
        $config_array['xmlrpc_server'] = $mainframe->getCfg('xmlrpc_server');
        $config_array['log_path'] = $mainframe->getCfg('log_path'); // TODO: change it for child
        $config_array['tmp_path'] = $mainframe->getCfg('tmp_path'); //  TODO: change it for child
        $config_array['live_site'] = $mainframe->getCfg('live_site'); // TODO: change it for child
        $config_array['force_ssl'] = $mainframe->getCfg('force_ssl');

        // LOCALE SETTINGS
        $config_array['offset'] = $mainframe->getCfg('offset');

        // CACHE SETTINGS
        $config_array['caching'] = $mainframe->getCfg('caching');
        $config_array['cachetime'] = $mainframe->getCfg('cachetime');
        $config_array['cache_handler'] = $mainframe->getCfg('cache_handler');
        $config_array['memcache_settings'] = $mainframe->getCfg('memcache_settings');

        // FTP SETTINGS
        $config_array['ftp_enable'] = $mainframe->getCfg('ftp_enable');
        $config_array['ftp_host'] = $mainframe->getCfg('ftp_host');
        $config_array['ftp_port'] = $mainframe->getCfg('ftp_port');
        $config_array['ftp_user'] = $mainframe->getCfg('ftp_user');
        $config_array['ftp_pass'] = $mainframe->getCfg('ftp_pass');
        $config_array['ftp_root'] = $mainframe->getCfg('ftp_root');

        // DATABASE SETTINGS
        $config_array['dbtype'] = $mainframe->getCfg('dbtype');
        $config_array['host'] = JRequest::getVar('host', 'localhost', 'post', 'string');
        $config_array['user'] = JRequest::getVar('user', '', 'post', 'string');
        $config_array['password'] = JRequest::getVar('password', '', 'post', 'string');
        $config_array['db'] = JRequest::getVar('database', '', 'post', 'string');
        $config_array['dbprefix'] = JRequest::getVar('prefix', 'wcp_', 'post', 'string');

        // MAIL SETTINGS
        $config_array['mailer'] = $mainframe->getCfg('mailer');
        $config_array['mailfrom'] = $mainframe->getCfg('mailfrom');
        $config_array['fromname'] = $mainframe->getCfg('fromname');
        $config_array['sendmail'] = $mainframe->getCfg('sendmail');
        $config_array['smtpauth'] = $mainframe->getCfg('smtpauth');
        $config_array['smtpuser'] = $mainframe->getCfg('smtpuser');
        $config_array['smtppass'] = $mainframe->getCfg('smtppass');
        $config_array['smtphost'] = $mainframe->getCfg('smtphost');

        // META SETTINGS
        $config_array['MetaAuthor'] = $mainframe->getCfg('MetaAuthor');
        $config_array['MetaTitle'] = $mainframe->getCfg('MetaTitle');
        $config_array['sitename'] = $mainframe->getCfg('sitename');
        $config_array['offline_message'] = $mainframe->getCfg('offline_message');

        // SESSION SETTINGS
        $config_array['lifetime'] = $mainframe->getCfg('lifetime');
        $config_array['session_handler'] = $mainframe->getCfg('session_handler');

        // Load config array
        $config->loadArray($config_array);

        // Get the path of the child configuration file
        $fname = JPATH_CONFIGURATION.DS.JRequest::getVar('path').DS.'configuration.php';

        // Get the config registry in PHP class format and write it to configuation.php
        JFile::write($fname, $config->toString('PHP', 'config', array('class' => 'JConfig')));

        self::setInternalTime(time());

        return true;
    }

    /**
     * Applies made changes to child
     *
     * @access public
     * @return boolean
     */
    function applyChild() {
        list($cid) = JRequest::getVar('cid');
        $master_db =& JFactory::getDBO();
        $child_db = new JDatabaseMySQL(array('host' => JRequest::getVar('host'), 'user' => JRequest::getVar('user'), 'password' => JRequest::getVar('password'), 'database' => JRequest::getVar('database'), 'prefix' => JRequest::getVar('prefix')));

        if(!$child_db->connected()) {
            JError::raiseError(0, JText::_('Connot connect to the child for re-configuring it'));
            return false;
        }

        // Update child settings in jos_wcp and #__wcp
        $wcp_table = new TableWCP($master_db);
        $wcp_table->load((int) $cid);
        $wcp_table->set('sid', JRequest::getVar('sid'));
        $wcp_table->set('name', JRequest::getVar('name'));
        $wcp_table->set('path', JRequest::getVar('path'));

        $params = new JParameter('');
        $params->set('exclude_files', json_encode(array_values(array_filter(JRequest::getVar('exclude_files'), 'strlen'))));
        $params->set('dont_copy_files', json_encode(array_values(array_filter(JRequest::getVar('dont_copy_files'), 'strlen'))));
        $params->set('exclude_tables', json_encode(array_values(array_filter(JRequest::getVar('exclude_tables'), 'strlen'))));
        $params->set('dont_copy_tables', json_encode(array_values(array_filter(JRequest::getVar('dont_copy_tables'), 'strlen'))));

        $database = new JObject;
        $database->set('host', JRequest::getVar('host'));
        $database->set('user', JRequest::getVar('user'));
        $database->set('password', JRequest::getVar('password'));
        $database->set('database', JRequest::getVar('database'));
        $database->set('prefix', JRequest::getVar('prefix'));
        $params->set('database', json_encode($database));

        $master_database = new JObject;
        $master_database->set('host', JRequest::getVar('master_host'));
        $master_database->set('user', JRequest::getVar('master_user'));
        $master_database->set('password', JRequest::getVar('master_password'));
        $master_database->set('database', JRequest::getVar('master_database'));
        $master_database->set('prefix', JRequest::getVar('master_prefix'));
        $params->set('master_db', json_encode($master_database));

        $ftp = new JObject;
        $ftp->set('enable', JRequest::getVar('ftp_enable'));
        $ftp->set('host', JRequest::getVar('ftp_host'));
        $ftp->set('port', JRequest::getVar('ftp_port'));
        $ftp->set('user', JRequest::getVar('ftp_user'));
        $ftp->set('pass', JRequest::getVar('ftp_pass'));
        $ftp->set('root', JRequest::getVar('ftp_root'));
        $params->set('ftp', json_encode($ftp));

        $wcp_table->set('params', $params->toString());

        // Save changes to master
        $wcp_table->store();

        // Save changes to child
        $wcp_table->_db = $child_db;
        $wcp_table->store();
        // Debug: echo '<pre>', print_r($wcp_table, true), '</pre>';

        // Re-configure child
        $config = new JRegistry('config');

        // Get the path of the child configuration file
        $fname = JPATH_CONFIGURATION.DS.JRequest::getVar('path').DS.'configuration.php';

        $config->loadObject(new JConfig);
        $config_array = $config->toArray();
        // Debug: echo '<pre>', print_r($config_array, true), '</pre>';

        $config_array['secret'] = JRequest::getVar('sid');

        // DATABASE SETTINGS
        $config_array['host'] = JRequest::getVar('host', 'localhost', 'post', 'string');
        $config_array['user'] = JRequest::getVar('user', '', 'post', 'string');
        $config_array['password'] = JRequest::getVar('password', '', 'post', 'string');
        $config_array['db'] = JRequest::getVar('database', '', 'post', 'string');
        $config_array['dbprefix'] = JRequest::getVar('prefix', 'wcp_', 'post', 'string');

        // Load config array
        $config->loadArray($config_array);

        // Get the config registry in PHP class format and write it to configuation.php
        JFile::write($fname, $config->toString('PHP', 'config', array('class' => 'JConfig')));
    }

    /**
     * Removes child
     *
     * @access public
     * @return bool
     */
    function removeChild() {
        // Try to set the script execution time to unlimited, if php is in safe mode there is no workaround
        @set_time_limit(0);

        $db =& JFactory::getDBO();
        $wcp_table = new TableWCP($db);

        $cid = JRequest::getVar('cid');
        foreach($cid as $id) {
            $wcp_table->load($id);
            // Debug: echo '<pre>', print_r($wcp_table, true), '</pre>';

            // Delete tables
            $params = new JParameter($wcp_table->params);
            $database = json_decode($params->get('database'));
            // Debug: echo '<pre>', print_r($database, true), '</pre>';
            $child_db = new JDatabaseMySQL(array('host' => $database->host, 'user' => $database->user, 'password' => $database->password, 'database' => $database->database, 'prefix' => $database->prefix));
            // Debug: $child_db->debug(1);

            if(!$child_db->connected())
                JError::raiseWarning(0, JText::_('Cannot connect to child database to delete tables'));
            else {
                $child_db->setQuery("show tables like '" . $child_db->_table_prefix . "%'");
                $child_tables = $child_db->loadResultArray();
                // Debug: echo '<pre>', print_r($child_tables, true), '</pre>';
                foreach($child_tables as $child_table) {
                    $child_db->setQuery('drop table ' . $child_table);
                    $child_db->query();
                }
            }

            // Delete files
            if($wcp_table->path != '')
                JFolder::delete(JPATH_ROOT.DS.$wcp_table->path);

            // Delete database entry
            $wcp_table->delete($id);
        }

    }

    /**
     * Get file system differences between master and child
     *
     * @access public
     * @param string The path, in which it will try to find modified files
     * @return array
     */
    function getDifferences($path = JPATH_ROOT) {
        global $mainframe;
        $diffs = array();
        $db =& JFactory::getDBO();

        // Get internal timer
        $internal_timer = self::getInternalTime();

        // Get exclude files list
        $exclude_files = self::getExcludeFiles($path);

        $db->setQuery('select path from #__wcp where sid = "' . $mainframe->getCfg('secret') . '"');
        $child_path = $db->loadResult();
        $master_root = JPath::clean(str_replace(str_replace(array('./', '/'), DS, $child_path), '', $path));

        $child_files = JFolderWCP::files($path, array_merge($exclude_files, array('.svn', 'CVS')));
        foreach($child_files as $child_file) {
            // Make file path relative
            $child_file = str_replace($path, '.', $child_file);

            // Make file path unix format
            $child_file = str_replace(DS, '/', $child_file);

            $orig_m_time = $m_time = filemtime($path . DS . $child_file);
            if(file_exists($master_root . DS . $child_file))
                $orig_m_time = filemtime($master_root . DS . $child_file);
            if($m_time > $internal_timer and $m_time >= $orig_m_time)
                $diffs[] = array($child_file, date('r', $m_time));
        }

        // Debug: echo '<pre>', print_r($diffs, true), '</pre>';
        return $diffs;
    }

    /**
     * Get table differences between master and child
     *
     * @access public
     * @return array
     */
    function getTableDifferences() {
        $diffs = array();
        $db =& JFactory::getDBO();

        // Get internal timer
        $internal_timer = date('Y-m-d H:i:s', self::getInternalTime());

        // Correct time zones for MySQL
        $db->setQuery("set session time_zone = '" . date('P', time()) . "'");
        $db->query();

        $db->setQuery("select id, action, table_name, table_key, value, unix_timestamp(date) as mdate from #__log_queries where date > '$internal_timer' order by date asc");
        $diffs = $db->loadObjectList();

        return $diffs;
    }

    /**
     * Get database differences between master and child
     *
     * @access public
     * @return array
     */
    function getDatabaseDifferences() {
        global $mainframe;
        $diffs = array();
        $master_db =& self::getMasterDBO();
        $child_db =& JFactory::getDBO();

        if(!$master_db->connected()) {
            JError::raiseNotice(0, JText::_('Cannot connect to master database to get database differences'));
            $master_tables = array();
        } else {
            $master_db->setQuery("show tables like '" . $master_db->_table_prefix . "%'");
            $master_tables = $master_db->loadResultArray();
            foreach($master_tables as $i => $table)
                $master_tables[$i] = str_replace($master_db->_table_prefix, '#__', $table);
        }

        $child_db->setQuery("show tables like '" . $child_db->_table_prefix . "%'");
        $child_tables = $child_db->loadResultArray();
        foreach($child_tables as $i => $table)
            $child_tables[$i] = str_replace($child_db->_table_prefix, '#__', $table);

        $exclude_tables = self::getExcludeTables();

        // Get all added/deleted/updated tables

        $internal_timer = date('Y-m-d H:i:s', self::getInternalTime());

        // Correct time zones for MySQL
        $child_db->setQuery("set session time_zone = '" . date('P', time()) . "'");
        $child_db->query();

        $child_db->setQuery("select table_name from information_schema.tables where table_schema = database() and table_name like '$child_db->_table_prefix%' and create_time > '$internal_timer'");
        $tables_added = $child_db->loadResultArray();
        foreach($tables_added as $i => $table)
            $tables_added[$i] = str_replace($child_db->_table_prefix, '#__', $table);
        $tables_added = array_diff($tables_added, $exclude_tables);

        //$tables_added = array_diff($child_tables, $master_tables, $exclude_tables);
        // Debug: echo '<pre>', print_r($tables_added, true), '</pre>';

        $tables_deleted = array_diff($master_tables, $child_tables, $exclude_tables);
        // Debug: echo '<pre>', print_r($tables_deleted, true), '</pre>';

        $child_db->setQuery("select table_name from information_schema.tables where table_schema = database() and table_name like '$child_db->_table_prefix%' and update_time > '$internal_timer' and table_name not in (select distinct event_object_table from information_schema.triggers where event_object_schema = database())");
        $tables_updated = $child_db->loadResultArray();
        foreach($tables_updated as $i => $table)
            $tables_updated[$i] = str_replace($child_db->_table_prefix, '#__', $table);
        $tables_updated = array_diff($tables_updated, $tables_added, $exclude_tables);

        // Compare changes with master - see if tables already exist
        if($master_db->connected())
            if(isset($tables_added[0])) {
                foreach($tables_added as $table)
                    $tables_added_list[] = $master_db->Quote(str_replace('#__', $master_db->_table_prefix, $table));
                $tables_added_list = implode(',', $tables_added_list);
                $master_db->setQuery("select table_name from information_schema.tables where table_schema = database() and table_name in ($tables_added_list)");
                $tables_created = $master_db->loadResultArray();
                foreach($tables_created as $i => $table)
                    $tables_created[$i] = str_replace($master_db->_table_prefix, '#__', $table);
                $tables_added = array_diff($tables_added, $tables_created);
            }

        // Compare changes with master - see if child table is updated after master table
        /* Need to be handled with internal timer comparison
        if($master_db->connected())
            if(isset($tables_updated[0]))
                foreach($tables_updated as $table) {
                    $child_db->setQuery("select update_time from information_schema.tables where table_schema = database() and table_name = '" . str_replace('#__', $child_db->_table_prefix, $table) . "'");
                    $table_update_time = $child_db->loadResult();
                    $master_db->setQuery("select table_name from information_schema.tables where table_schema = database() and table_name = '" . str_replace('#__', $master_db->_table_prefix, $table) . "' and update_time > '$table_update_time'");
                    $master_tables_updated = $master_db->loadResultArray();
                    foreach($master_tables_updated as $i => $table)
                        $master_tables_updated[$i] = str_replace($master_db->_table_prefix, '#__', $table);
                    $tables_updated = array_diff($tables_updated, $master_tables_updated);
                }
        */

        // Debug: echo '<pre>', print_r($tables_updated, true), '</pre>';

        foreach($tables_added as $table) {
            $diff = new JObject;
            $diff->set('id', 'add ' . $table);
            $diff->set('action', 'add table');
            $diff->set('table_name', str_replace('#__', $child_db->_table_prefix, $table));
            $child_db->setQuery("select create_time from information_schema.tables where table_schema = database() and table_name = '" . str_replace('#__', $child_db->_table_prefix, $table) . "'");
            $diff->set('mdate', date('r', strtotime($child_db->loadResult())));
            $diffs[] = $diff;
        }

        foreach($tables_deleted as $table) {
            $diff = new JObject;
            $diff->set('id', 'delete ' . $table);
            $diff->set('action', 'delete table');
            $diff->set('table_name', str_replace('#__', $child_db->_table_prefix, $table));
            $diff->set('mdate', '-');
            $diffs[] = $diff;
        }

        foreach($tables_updated as $table) {
            $diff = new JObject;
            $diff->set('id', 'update ' . $table);
            $diff->set('action', 'update table');
            $diff->set('table_name', str_replace('#__', $child_db->_table_prefix, $table));
            $child_db->setQuery("select update_time from information_schema.tables where table_schema = database() and table_name = '" . str_replace('#__', $child_db->_table_prefix, $table) . "'");
            $diff->set('mdate', date('r', strtotime($child_db->loadResult())));
            $diffs[] = $diff;
        }

        return $diffs;
    }


    /**
     * Create patch from the child
     *
     * @access public
     * @return boolean
     */
    function createPatch() {
        $changes = JRequest::getVar('cid');

        $files = $tables = $rows = array();
        foreach($changes as $i => $change)
            if(file_exists(JPATH_ROOT.DS.$change))
                $files[] = JPATH_ROOT.DS.$change;
            elseif(is_numeric($change))
                $rows[] = $change;
            elseif(true)
                $tables[] = $change;

        // Debug: echo '<pre>', print_r($files, true), '</pre>';
        // Debug: echo '<pre>', print_r($tables, true), '</pre>';
        // Debug: echo '<pre>', print_r($rows, true), '</pre>';

        // Tables patch
        $sql = array();
        $db =& JFactory::getDBO();
        $db->setQuery('select action, table_name, table_key, value from #__log_queries where id in (' . implode(',', $rows) . ')');
        $rows = $db->loadObjectList();
        if(is_array($rows)) {
            foreach($rows as $row) {
                $db->setQuery("select * from $row->table_name where $row->table_key = '$row->value'");
                $data = $db->loadAssoc();
                $row->table_name = str_replace($db->_table_prefix, '#__', $row->table_name);
                switch($row->action) {
                    case 'insert':
                    case 'update':
                        foreach($data as $key => $val)
                            $data[$key] = $db->isQuoted($key) ? $db->Quote($val) : (int) $val; // TODO: make sure NULL values will not cause issues

                        $data = implode(',', $data);
                        $sql[] = "replace into $row->table_name values ($data)";
                        break;
                    case 'delete':
                        $sql[] = "delete from $row->table_name where $row->table_key = '$row->value'";
                        break;
                }
            }
        }

        // Database patch
        foreach($tables as $table) {
            list($action, $table) = sscanf($table, '%s %s');
            switch($action) {
                case 'add':
                    list($table_ddl) = array_values($db->getTableCreate($table));
                    $table_ddl = preg_replace('/'.$db->_table_prefix.'/', '#__', $table_ddl, 1);
                    $sql[] = str_replace("\n", '', $table_ddl);
                    $db->setQuery('select * from ' . $table);
                    $rows = $db->loadAssocList();
                    foreach($rows as $row) {
                        foreach($row as $key => $val)
                            $row[$key] = $db->isQuoted($key) ? $db->Quote($val) : (int) $val;

                        $row = implode(',', $row);
                        $sql[] = "insert into $table values ($row)";
                    }
                    break;
                case 'update':
                    $sql[] = 'truncate table ' . $table;
                    $db->setQuery('select * from ' . $table);
                    $rows = $db->loadAssocList();
                    foreach($rows as $row) {
                        foreach($row as $key => $val)
                            $row[$key] = $db->isQuoted($key) ? $db->Quote($val) : (int) $val;

                        $row = implode(',', $row);
                        $sql[] = "insert into $table values ($row)";
                    }
                    break;
                case 'delete':
                    $sql[] = 'drop table if exists ' . $table;
                    break;
            }
        }

        // Debug: echo '<pre>', print_r($sql, true), '</pre>';

        $sql = implode(";\n", $sql) . ';';
        $patch_id = uniqid('patch_');
        $patch_file_sql = JPATH_ROOT.DS.$patch_id.'.sql';
        JFile::write($patch_file_sql, $sql);
        $files[] = $patch_file_sql;

        // Creating the patch package
        jimport('joomla.filesystem.archive');
        $patch_file = $patch_id.'.tar.gz';
        JArchive::create(JPATH_ROOT.DS.'tmp'.DS.$patch_file, $files, 'gz', '', JPATH_ROOT);

        // Delete sql file
        JFile::delete($patch_file_sql);

        // Loading download form
        $document =& JFactory::getDocument();
        $document->addStyleDeclaration('.icon-48-download {background-image:url(./templates/khepri/images/header/icon-48-install.png);}');
        JToolBarHelper::title(JText::_('WCP Manager') . ': <small><small>[ ' . JText::_('Download Patch') . ' ]</small></small>', 'download.png');
        JToolBarHelper::custom('cancel', 'back.png', 'back.png', 'Back', '', false);
        JToolBarHelper::help('screen.wcp.createPatch', true);

        echo '<form action="index.php" method="post" name="adminForm">';
        echo JText::_('Download will start automatically') . ' <a href="' . JURI::root() . 'tmp/' . $patch_file . '"> ' . JText::_('Start download manually') . '</a>';
        echo '<iframe src="' . JURI::root() . 'tmp/' . $patch_file . '" style="display:none;"></iframe>';
        echo '<input type="hidden" name="task" value="" />';
        echo '</form>';

        // Return to Create Patch interface
        $document->setMetaData('REFRESH', '5; url='.JURI::base().'index.php?option=com_wcp&view=differences', true);

        return true;
    }

    /**
     * Apply the patch to the master
     *
     * @access public
     * @return boolean
     */
    function applyPatch() {
        // Get the uploaded file information
        $userfile = JRequest::getVar('patch_file', null, 'files', 'array');

        // If there is no uploaded file, we have a problem...
        if(empty($userfile['name'])) {
            JError::raiseWarning(0, JText::_('No file selected'));
            return false;
        }

        // Check if there was a problem uploading the file.
        if($userfile['error'] or $userfile['size'] < 1) {
            JError::raiseWarning(0, JText::_('Cannot upload the file'));
            return false;
        }

        // Build the appropriate paths
        $tmp_dest = JPATH_ROOT.DS.'tmp'.DS.$userfile['name'];
        $tmp_src  = $userfile['tmp_name'];

        // Move uploaded file
        JFile::upload($tmp_src, $tmp_dest);

        // Unpack the patch file
        $patch_src = $tmp_dest;
        $patch_dest = JPATH_ROOT.DS.'tmp'.DS.uniqid('patch_');
        jimport('joomla.filesystem.archive');
        JArchive::extract($patch_src, $patch_dest);

        // Run queries from sql file
        $sql_file = $patch_dest.DS.str_replace('.tar.gz', '.sql', $userfile['name']);
        if(file_exists($sql_file)) {
            $db =& JFactory::getDBO();
            $sql = file($sql_file);
            foreach($sql as $query) {
                $db->setQuery($query);
                $db->query();
            }

            // Remove sql file
            JFile::delete($sql_file);
        }

        // Replace files
        $files = JFolderWCP::files($patch_dest);
        // Debug: echo '<pre>', print_r($files, true), '</pre>';
        foreach($files as $file) {
            // Debug: echo '<pre>', $file, ' -> ', str_replace($patch_dest, JPATH_ROOT, $file), '</pre>';
            $file_dest = str_replace($patch_dest, JPATH_ROOT, $file);
            if(file_exists($file_dest))
                JFile::delete($file_dest);
            if(!is_dir(dirname($file_dest)))
                JFolder::create(dirname($file_dest));
            JFile::move($file, $file_dest);
        }

        // Remove tmp files
        JFile::delete($patch_src);
        JFolder::delete($patch_dest);

        return true;
    }

    /**
     * Commit changes to the master
     *
     * @access public
     * @return boolean
     */
    function commit() {
        global $mainframe;
        $changes = JRequest::getVar('cid');
        $db =& JFactory::getDBO();
        $master_db =& self::getMasterDBO();

        $files = $tables = $rows = array();
        foreach($changes as $i => $change)
            if(file_exists(JPATH_ROOT.DS.$change))
                $files[] = $change;
            elseif(is_numeric($change))
                $rows[] = $change;
            elseif(true)
                $tables[] = $change;

        // Debug: echo '<pre>', print_r($files, true), '</pre>';
        // Debug: echo '<pre>', print_r($tables, true), '</pre>';
        // Debug: echo '<pre>', print_r($rows, true), '</pre>';

        // Commit files
        $db->setQuery('select path from #__wcp where sid = "' . $mainframe->getCfg('secret') . '"');
        $path = $db->loadResult();
        $master_root = JPath::clean(str_replace(str_replace(array('./', '/'), DS, $path), '', JPATH_ROOT));
        foreach($files as $file) {
            if(!is_dir(dirname($master_root.DS.$file)))
                JFolder::create(dirname($master_root.DS.$file));
            if(!JFile::copy(JPATH_ROOT.DS.$file, $master_root.DS.$file))
                JError::raiseWarning(21, 'Failed to copy ' . JPATH_ROOT.DS.$file . ' to ' . $master_root.DS.$file);
            else
                $mainframe->enqueueMessage("$file commited successfully");
        }

        // Commit database
        foreach($tables as $table) {
            list($action, $table) = sscanf($table, '%s %s');
            switch($action) {
                case 'add':
                    list($table_ddl) = array_values($db->getTableCreate($table));
                    $table_ddl = preg_replace('/'.str_replace('#__', $db->_table_prefix, $table).'/', $table, $table_ddl, 1);
                    $master_db->setQuery($table_ddl);
                    $master_db->query();
                    $db->setQuery('select * from ' . $table);
                    $table_rows = $db->loadObjectList();
                    foreach($table_rows as $row)
                        $master_db->insertObject($table, $row);
                    break;
                case 'update':
                    $master_db->setQuery('truncate table ' . $table);
                    $master_db->query();
                    $db->setQuery('select * from ' . $table);
                    $table_rows = $db->loadObjectList();
                    foreach($table_rows as $row)
                        $master_db->insertObject($table, $row);
                    break;
                case 'delete':
                    $master_db->setQuery('drop table if exists ' . $table);
                    $master_db->query();
                    break;
            }
        }

        // Commit rows
        foreach($rows as $row) {
            $db->setQuery('select action, table_name, table_key, value from #__log_queries where id = ' . $row);
            $change = $db->loadObject();
            if(empty($change->action))
                continue;

            switch($change->action) {
                case 'insert':
                case 'update':
                    $db->setQuery("select * from $change->table_name where $change->table_key = '$change->value'");
                    $original = $db->loadAssoc();

                    foreach($original as $key => $val)
                        $original[$key] = $db->isQuoted($key) ? $db->Quote($val) : (int) $val; // TODO: make sure NULL values will not cause issues

                    $original = implode(',', $original);
                    $master_db->setQuery("replace into " . str_replace($db->_table_prefix, '#__', $change->table_name) . " values ($original)");
                    $master_db->query();

                    // Remove from query log - remember: id is changed after store
                    $db->setQuery("delete from #__log_queries where table_name = '$change->table_name' and table_key = '$change->table_key' and value = '$change->value'");
                    $db->query();
                    break;
                case 'delete':
                    $master_db->setQuery("delete from " . str_replace($db->_table_prefix, '#__', $change->table_name) . " where $change->table_key = '$change->value'");
                    $master_db->query();

                    // Remove from query log - remember: id is changed after delete
                    $db->setQuery("delete from #__log_queries where table_name = '$change->table_name' and table_key = '$change->table_key' and value = '$change->value'");
                    $db->query();
                    break;
            }
        }

        // TODO: Move internal timer forward
        // TODO: Use touch to change last modified date of files

        return true;
    }

    /**
     * Merge childs
     *
     * @access public
     * @return boolean
     */
    function merge() {
        // Try to set the script execution time to unlimited, if php is in safe mode there is no workaround
        @set_time_limit(0);

        $cid = JRequest::getVar('cid', array(), 'POST', 'array');
        if(count($cid) != 2) {
            JError::raiseWarning(0, JText::_('Select 2 childs at a time'));
            return false;
        }

        $db =& JFactory::getDBO();

        // Get connection to child site databases
        $db->setQuery('select params from #__wcp where id = ' . (int) $cid[0]);
        $child1_params = $db->loadResult();
        $db->setQuery('select params from #__wcp where id = ' . (int) $cid[1]);
        $child2_params = $db->loadResult();

        $params = new JParameter($child1_params);
        $child1_db = json_decode($params->get('database'));
        $child1_db = new JDatabaseMySQL(array('host' => $child1_db->host, 'user' => $child1_db->user, 'password' => $child1_db->password, 'database' => $child1_db->database, 'prefix' => $child1_db->prefix));

        $params = new JParameter($child2_params);
        $child2_db = json_decode($params->get('database'));
        $child2_db = new JDatabaseMySQL(array('host' => $child2_db->host, 'user' => $child2_db->user, 'password' => $child2_db->password, 'database' => $child2_db->database, 'prefix' => $child2_db->prefix));

        if($child1_db->connected() and $child2_db->connected()) {
            # Merge table rows
            // Get all the changes made on the child 2
            $child2_db->setQuery('select * from #__log_queries');
            $changes = $child2_db->loadObjectList();
            foreach($changes as $change) {
                // If the change on child 2 is made after the same change on the child 1 or there is no
                // such change on child 1, commit the change to child 1
                $child1_db->setQuery("select date from #__log_queries where table_name = '" . str_replace($child2_db->_table_prefix, $child1_db->_table_prefix, $change->table_name) . "' and value = '$change->value'");
                $date = $child1_db->loadResult();
                if(empty($date) or strtotime($change->date) > strtotime($date)) {
                    // Commit the change to child 1
                    $change->table_name = str_replace($child2_db->_table_prefix, '#__', $change->table_name);
                    switch($change->action) {
                        case 'insert':
                        case 'update':
                            $child2_db->setQuery("select * from $change->table_name where $change->table_key = '$change->value'");
                            $row = $child2_db->loadObject();
                            $child1_db->updateObject($change->table_name, $row, $change->table_key);
                            if($child1_db->getAffectedRows() == 0)
                                $child1_db->insertObject($change->table_name, $row, $change->table_key);
                            break;
                        case 'delete':
                            // Do nothing
                            //$child1_db->setQuery("delete from $change->table_name where $change->table_key = '$change->value'");
                            //$child1_db->query();
                            break;
                    }
                    // Debug: echo $change->table_name, '.', $change->table_key, '=', $change->value, '<br />';

                }
            }

            # Merge database
            // Get all tables from child1 and child2
            $child1_db->setQuery("show tables like '" . $child1_db->_table_prefix . "%'");
            $child1_tables = $child1_db->loadResultArray();
            foreach($child1_tables as $i => $table)
                $child1_tables[$i] = str_replace($child1_db->_table_prefix, '#__', $table);

            $child2_db->setQuery("show tables like '" . $child2_db->_table_prefix . "%'");
            $child2_tables = $child2_db->loadResultArray();
            foreach($child2_tables as $i => $table)
                $child2_tables[$i] = str_replace($child2_db->_table_prefix, '#__', $table);

            // Copy proper tables from child 2 to child 1
            $create_tables = array_diff($child2_tables, $child1_tables);
            foreach($create_tables as $table) {
                $table_ddl = end($child2_db->getTableCreate($table));
                $table_ddl = preg_replace('/'.str_replace($child2_db->_table_prefix, '#__', $table).'/', $table, $table_ddl, 1);

                // Create table
                $child1_db->setQuery($table_ddl);
                $child1_db->query();

                // Add rows
                $child2_db->setQuery("select * from $table");
                $rows = $child2_db->loadObjectList();
                foreach($rows as $row)
                    $child1_db->insertObject($table, $row);
            }

        } else {
            JError::raiseWarning(0, JText::_('Cannot connect to the child database'));
        }

        # Merge files
        $db->setQuery('select path from #__wcp where id = ' . (int) $cid[0]);
        $child1_path = $db->loadResult();

        $db->setQuery('select path from #__wcp where id = ' . (int) $cid[1]);
        $child2_path = $db->loadResult();

        // Get all files from child 2 not in exclude list and if newer apply to child 1
        $params = new JParameter($child2_params);
        $exclude_files = array_merge(json_decode($params->get('exclude_files')), json_decode($params->get('dont_copy_files')));
        foreach($exclude_files as $i => $exclude_file) {
            $exclude_files[$i] = str_replace('./', realpath(JPATH_ROOT . DS . $child2_path) . DS, $exclude_file);
            $exclude_files[$i] = str_replace('/', DS, $exclude_files[$i]);
        }

        // Debug: echo '<pre>', print_r($exclude_files, true), '</pre>';

        $child2_files = JFolderWCP::files(realpath(JPATH_ROOT . DS . $child2_path), array_merge($exclude_files, array('.svn', 'CVS')));

        // Update files on child 1 if newer
        foreach($child2_files as $child2_file) {
            $child1_file = str_replace(realpath(JPATH_ROOT . DS . $child2_path), realpath(JPATH_ROOT . DS . $child1_path), $child2_file);
            if(!file_exists($child1_file) or filemtime($child2_file) > filemtime($child1_file)) {
                // Create appropriate directories and copy file to child 1
                if(!is_dir(dirname($child1_file)))
                    JFolder::create(dirname($child1_file));

                JFile::copy($child2_file, $child1_file);
                touch($child1_file, filemtime($child2_file));
            }
        }

        // Debug: echo '<pre>', print_r($child2_files, true), '</pre>';

        // Change child 1 name
        $db->setQuery('select name from #__wcp where id = ' . (int) $cid[0]);
        $child1_name = $db->loadResult();
        $db->setQuery('select name from #__wcp where id = ' . (int) $cid[1]);
        $child2_name = $db->loadResult();

        $wcp_table = new TableWCP($db);
        $wcp_table->load((int) $cid[0]);
        $wcp_table->name = $child1_name . ' merged w/ ' . $child2_name;
        $wcp_table->store();

        return true;
    }

    /**
     * Revert the child
     *
     * @access public
     * @return boolean
     */
    function revertChild() {
        global $mainframe;
        $changes = JRequest::getVar('cid');
        $db =& JFactory::getDBO();
        $master_db =& self::getMasterDBO();

        $files = $tables = $rows = array();
        foreach($changes as $i => $change)
            if(file_exists(JPATH_ROOT.DS.$change))
                $files[] = $change;
            elseif(is_numeric($change))
                $rows[] = $change;
            elseif(true)
                $tables[] = $change;

        // Debug: echo '<pre>', print_r($files, true), '</pre>';
        // Debug: echo '<pre>', print_r($tables, true), '</pre>';
        // Debug: echo '<pre>', print_r($rows, true), '</pre>';

        // Revert files
        $db->setQuery('select path from #__wcp where sid = "' . $mainframe->getCfg('secret') . '"');
        $path = $db->loadResult();
        $master_root = JPath::clean(str_replace(str_replace(array('./', '/'), DS, $path), '', JPATH_ROOT));
        foreach($files as $i => $file)
            if(!JFile::copy($master_root . DS . $file, JPATH_ROOT . DS . $file))
                JError::raiseNotice(0, "Cannot revert " . $master_root . DS . $file . ", original file doesn't exist");
            else
                touch(JPATH_ROOT . DS . $file, filemtime($master_root . DS . $file));

        // Revert database
        foreach($tables as $table) {
            list($action, $table) = sscanf($table, '%s %s');
            switch($action) {
                case 'add':
                    $db->setQuery('drop table if exists ' . $table);
                    $db->query();
                    break;
                case 'update':
                    if(!$master_db->connected())
                        JError::raiseNotice(0, "Cannot connect to master database to revert table $table");
                    else {
                        $db->setQuery('truncate table ' . $table);
                        $db->query();
                        $master_db->setQuery('select * from ' . $table);
                        $rows = $master_db->loadObjectList();
                        foreach($rows as $row)
                            $db->insertObject($table, $row);
                    }
                    break;
                case 'delete':
                    if(!$master_db->connected())
                        JError::raiseNotice(0, "Cannot connect to master database to revert table $table");
                    else {
                        list($table_ddl) = array_values($master_db->getTableCreate($table));
                        $table_ddl = preg_replace('/'.str_replace('#__', $master_db->_table_prefix, $table).'/', $table, $table_ddl, 1);
                        $db->setQuery($table_ddl);
                        $db->query();
                        $master_db->setQuery('select * from ' . $table);
                        $rows = $master_db->loadObjectList();
                        foreach($rows as $row)
                            $db->insertObject($table, $row);
                    }
                    break;
            }
        }

        // Revert rows
        foreach($rows as $row) {
            $db->setQuery('select action, table_name, table_key, value from #__log_queries where id = ' . $row);
            $change = $db->loadObject();
            if(empty($change->action))
                continue;

            switch($change->action) {
                case 'insert':
                    $db->setQuery("delete from $change->table_name where $change->table_key = '$change->value'");
                    $db->query();

                    // Remove from query log - remember: id is changed after delete
                    $db->setQuery("delete from #__log_queries where table_name = '$change->table_name' and table_key = '$change->table_key' and value = '$change->value'");
                    $db->query();
                    break;
                case 'update':
                case 'delete':
                    if(!$master_db->connected())
                        JError::raiseNotice(0, "Cannot connect to master database to revert row " . $change->table_name . "." . $change->table_key . "=" . $change->value);
                    else {
                        $master_db->setQuery("select * from " . str_replace($db->_table_prefix, '#__', $change->table_name) . " where $change->table_key = '$change->value'");
                        $original = $master_db->loadAssoc();

                        if(count($original) == 0) {
                            // The original row doesn't exist in master table, deleting row from child_db
                            $db->setQuery("delete from $change->table_name where $change->table_key = '$change->value'");
                            $db->query();

                            // Remove from query log - remember: id is changed after delete
                            $db->setQuery("delete from #__log_queries where table_name = '$change->table_name' and table_key = '$change->table_key' and value = '$change->value'");
                            $db->query();

                            break;
                        }

                        foreach($original as $key => $val)
                            $original[$key] = $master_db->isQuoted($key) ? $master_db->Quote($val) : (int) $val; // TODO: make sure NULL values will not cause issues

                        $original = implode(',', $original);
                        $db->setQuery("replace into $change->table_name values ($original)");
                        $db->query();

                        // Remove from query log - remember: id is changed after store
                        $db->setQuery("delete from #__log_queries where table_name = '$change->table_name' and table_key = '$change->table_key' and value = '$change->value'");
                        $db->query();
                    }
                    break;
            }
        }

        return true;
    }

    /**
     * Synchronize the child
     *
     * @access public
     * @return boolean
     */
    function syncChild() {
        global $mainframe;
        $db =& JFactory::getDBO();
        $master_db =& self::getMasterDBO();

        if(!$master_db->connected()) {
            JError::raiseWarning(0, JText::_('Cannot connect to master databasa for synchronizing the child'));
            return false;
        }

        $db->setQuery('select path from #__wcp where sid = "' . $mainframe->getCfg('secret') . '"');
        $path = $db->loadResult();

        # Synchronizing files
        // Get all files on master and child, which are newer than the internal timer
        // then update to child the newer ones, but keep those which are already modified
        // on child
        $master_root = JPath::clean(str_replace(str_replace(array('./', '/'), DS, $path), '', JPATH_ROOT));
        $diffs_master = self::getDifferences($master_root);
        $diffs_child = self::getDifferences();

        foreach($diffs_master as $i => $diff_master)
            $diffs_master[$i] = $diff_master[0];

        foreach($diffs_child as $i => $diff_child)
            $diffs_child[$i] = $diff_child[0];

        $diffs = array_diff($diffs_master, $diffs_child);
        // Debug: echo '<pre>', print_r($diffs, ture), '</pre>';

        foreach($diffs as $file) {
            if(!is_dir(dirname(JPATH_ROOT.DS.$file)))
                JFolder::create(dirname(JPATH_ROOT.DS.$file));
            if(!JFile::copy($master_root.DS.$file, JPATH_ROOT.DS.$file))
                JError::raiseWarning(21, 'Failed to copy ' . $master_root.DS.$file . ' to ' . JPATH_ROOT.DS.$file);
            else
                $mainframe->enqueueMessage("$file synced successfully");
        }

        // TODO: Treat configuration.php and other special files cases separately

        # Synchronizing tables
        // As we don't know which table rows are modified on the master website,
        // we need to keep those, which are modified on the child, and replace
        // the rest from the master to child
        $master_db->setQuery("show tables like '" . $master_db->_table_prefix . "%'");
        $master_tables = $master_db->loadResultArray();
        foreach($master_tables as $master_table) {
            if(in_array(str_replace($master_db->_table_prefix, '#__', $master_table), self::getExcludeTables()))
                continue;

            $child_table = str_replace($master_db->_table_prefix, $db->_table_prefix, $master_table);
            $db->setQuery("show tables like '$child_table'");
            $db->query();
            if($db->getNumRows() == 0) {
                // If the table doesn't exist on the child, create it and copy all the data
                // and create triggers for new tables

                $master_table_ddl = array_pop($master_db->getTableCreate($master_table));
                $child_table_ddl = preg_replace('/'.$master_table.'/', $child_table, $master_table_ddl, 1);

                // Create child table
                $db->setQuery($child_table_ddl);
                $db->query();

                $master_db->setQuery('select * from '.$master_table);
                $master_rows = $master_db->loadObjectList();
                foreach($master_rows as $master_row)
                    $db->insertObject($child_table, $master_row);

                // Create triggers for child table
                if(self::getPrimaryKeyCount($db, $child_table) == 1) {
                    $key = self::getPrimaryKeyField($db, $child_table);
                    $db->setQuery("create trigger on_insert_$child_table after insert on $child_table for each row " .
                        "replace into #__log_queries (action, table_name, table_key, value) values('insert', '$child_table', '$key', new.$key)");
                    $db->query();

                    $db->setQuery("create trigger on_update_$child_table after update on $child_table for each row " .
                        "replace into #__log_queries (action, table_name, table_key, value) values('update', '$child_table', '$key', old.$key)");
                    $db->query();

                    $db->setQuery("create trigger on_delete_$child_table after delete on $child_table for each row " .
                        "replace into #__log_queries (action, table_name, table_key, value) values('delete', '$child_table', '$key', old.$key)");
                    $db->query();
                }

                // Increase child table auto_increment values
                $db->setQuery("select auto_increment from information_schema.tables where table_schema = database() and table_name = '$child_table'");
                $table_auto_increment = $db->loadResult();
                if($table_auto_increment != '') {
                    $table_auto_increment *= 10; // TODO: Select different multiplier depending on $table_auto_increment value
                    $db->setQuery("alter table $child_table auto_increment = $table_auto_increment");
                    $db->query();
                }

                // Add note
                $mainframe->enqueueMessage("Table $child_table created");

            } else {
                // Table exists on the child, replace all non-modified rows from master

                $key = self::getPrimaryKeyField($db, $child_table);

                // Get modified rows of the table
                $db->setQuery("select value from #__log_queries where table_name = '$child_table'");
                $modified_rows = $db->loadResultArray();
                foreach($modified_rows as $i => $val)
                    $modified_rows[$i] = $db->Quote($val);
                $modified_rows = implode(',', $modified_rows);
                // Debug: echo '<pre>', print_r($modified_rows, true), '</pre>';

                if($modified_rows !== '')
                    $master_db->setQuery("select * from $master_table where $key not in ($modified_rows)");
                else
                    $master_db->setQuery("select * from $master_table");
                $master_rows = $master_db->loadObjectList();
                foreach($master_rows as $master_row) {
                    // Try to insert
                    $db->insertObject($child_table, $master_row, $key);

                    // Update object
                    $db->updateObject($child_table, $master_row, $key);

                    // delete triggered query
                    $internal_timer = date('Y-m-d H:i:s', self::getInternalTime());
                    $db->setQuery("delete from #__log_queries where table_name = '$child_table' and value = '" . $master_row->$key . "' and date > '$internal_timer'");
                    $db->query();
                }

                // Add note
                $mainframe->enqueueMessage("Table $child_table synchronized");

            }

        }

        // TODO: Use touch to change last modified date of files

        // Move internal timer forward
        self::setInternalTime(time());

        return true;
    }

}

/**
 * JFolder extension class
 *
 */
class JFolderWCP {
    /**
     * Utility function to read the files in a folder.
     *
     * @param string The full path of the folder to read
     * @param array The exclude list
     * @return array Files and folders in the given folder
     * @access public
     */
    function files($path, $exclude = array('.svn', 'CVS')) {
        // Initialize variables
        $arr = array();

        // read the source directory
        $handle = opendir($path);
        while(($file = readdir($handle)) !== false) {
            if($file != '.' and $file != '..' and !in_array($file, $exclude)) {
                $dir = $path . DS . $file;
                if(!in_array($dir, $exclude)) {
                    if(is_dir($dir))
                        $arr = array_merge($arr, JFolderWCP::files($dir, $exclude));
                    else
                        $arr[] = $dir;
                }
            }
        }
        closedir($handle);

        return $arr;
    }
}