<?php
/**
* @package   com_zoo Component
* @file      zooinstall.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

class ZooInstall {

	public static function doInstall(JInstaller &$installer) {

		// create applications folder
		if (!JFolder::exists(JPATH_ROOT . '/media/zoo/applications/')) {
			JFolder::create(JPATH_ROOT . '/media/zoo/applications/');
		}

		// initialize zoo framework
		require_once($installer->getPath('extension_administrator').'/config.php');

		$zoo = App::getInstance('zoo');

		// fix joomla 1.5 bug
		if ($zoo->joomla->isVersion('1.5')) {
			$installer->getDBO = $installer->getDBO();
		}

		// copy checksums file
		if (JFile::exists($installer->getPath('source').'/checksums')) {
			JFile::copy($installer->getPath('source').'/checksums', $zoo->path->path('component.admin:').'/checksums');
		}

		// applications
		$applications = array();
		foreach (JFolder::folders($installer->getPath('source').'/applications', '.', false, true) as $folder) {
			try {

				if ($manifest = $zoo->install->findManifest($folder)) {

					$name = (string) $manifest->name;
					$status = $zoo->install->installApplicationFromFolder($folder);
					$applications[] = compact('name', 'status');

				}

			} catch (AppException $e) {

				$name = basename($folder);
				$status = false;
				$applications[] = compact('name', 'status');

			}
		}

		self::displayResults($applications, 'Applications', 'Application');

		// additional extensions

		// init vars
		$error = false;
		$extensions = array();

		// get plugin files
		$plugin_files = array();
		foreach ($zoo->filesystem->readDirectoryFiles(JPATH_PLUGINS, JPATH_PLUGINS.'/', '/\.php$/', true) as $file) {
			$plugin_files[] = basename($file);
		}

		// get manifest xml
		$manifest = $zoo->xml->loadFile($installer->getPath('manifest'));

		// get extensions
		if (isset($manifest->additional[0])) {
			$add = $manifest->additional[0];
			if (count($add->children())) {
			    $exts = $add->children();
			    foreach ($exts as $ext) {
					$ext_installer = new JInstaller();
					$ext_installer->setOverwrite(true);

					$update = false;
					if (($ext->getName() == 'module' && (JFolder::exists(JPATH_ROOT.'/modules/'.$ext->attributes()->name) || JFolder::exists(JPATH_ROOT.'/administrator/modules/'.$ext->attributes()->name)))
						|| ($ext->getName() == 'plugin' && in_array($ext->attributes()->name.'.php', $plugin_files))) {
						$update = true;
					}

					$folder = $installer->getPath('source').'/'.$ext->attributes()->folder;
					$folder = rtrim($folder, "\\/") . '/';
					if (JFolder::exists($folder)) {
					    if ($update) {
							foreach ($zoo->filesystem->readDirectoryFiles($folder, $folder, '/positions\.config$/', true) as $file) {
								JFile::delete($file);
							}
						}

				    	$extensions[] = array(
							'id' => (string) $ext->attributes()->name,
							'name' => (string) $ext,
							'type' => $ext->getName(),
							'folder' => $folder,
							'installer' => $ext_installer,
							'status' => false,
				    		'update' => $update
				    	);
				    }
			    }
			}
		}

		// install additional extensions
		for ($i = 0; $i < count($extensions); $i++) {
			if (is_dir($extensions[$i]['folder'])) {
				if (@$extensions[$i]['installer']->install($extensions[$i]['folder'])) {
					$extensions[$i]['status'] = $extensions[$i]['update'] ? 2 : 1;
					if ($extensions[$i]['status'] == 1) {
						switch ($extensions[$i]['id']) {

							// enable ZOO Quick Icons module
							case 'mod_zooquickicon':
								$zoo->module->enable($extensions[$i]['id'], 'icon');
								break;

							// enable ZOO search plugin
							case 'zoosearch':
								$zoo->plugin->enable($extensions[$i]['id']);
								break;
						}
					}
				} else {
					$error = true;
					break;
				}
			}
		}

		// rollback on installation errors
		if ($error) {
			$installer->abort(JText::_('Component').' '.JText::_('Install').': '.JText::_('Error'), 'component');
			for ($i = 0; $i < count($extensions); $i++) {
				if ($extensions[$i]['status']) {
					$extensions[$i]['installer']->abort(JText::_($extensions[$i]['type']).' '.JText::_('Install').': '.JText::_('Error'), $extensions[$i]['type']);
					$extensions[$i]['status'] = false;
				}
			}

			return false;
		}

		self::displayResults($extensions, 'Extensions', 'Extension');

		try {
			
			// clean ZOO installation
			$zoo->modifications->clean();
			
		} catch (Exception $e) {}
	
		if ($zoo->update->required()) {
			$zoo->error->raiseNotice(0, JText::_('ZOO requires an update. Please click <a href="'.$zoo->link().'">here</a>.'));
		}

		return true;
	}

	public static function doUninstall(JInstaller &$installer) {

		// initialize zoo framework
		require_once($installer->getPath('extension_administrator').'/config.php');

		// init vars
		$error = false;
		$extensions = array();
		$db = $zoo->database;

		// remove media folder
		if (JFolder::exists(JPATH_ROOT . '/media/zoo/applications/')) {
			JFolder::delete(JPATH_ROOT . '/media/zoo/applications/');
		}

		// migrate xml parser to zoos xml parser
		$manifest = $zoo->xml->loadString(($zoo->joomla->isVersion('1.5') ? $installer->getManifest()->document->toString() : $installer->getManifest()->asFormattedXML()));

		// additional extensions
		if (isset($manifest->additional[0])) {
			$add = $manifest->additional[0];
			if (count($add->children())) {
				$exts = $add->children();
				foreach ($exts as $ext) {

					// set query
					if ($zoo->joomla->isVersion('1.5')) {
						switch ($ext->getName()) {
							case 'plugin':
								$query = 'SELECT * FROM #__plugins WHERE element='.$db->Quote($ext->attributes()->name);
								break;
							case 'module':
								$query = 'SELECT * FROM #__modules WHERE module='.$db->Quote($ext->attributes()->name);
								break;
						}
					} else {
						$query = 'SELECT *, extension_id as id FROM #__extensions WHERE element = '.$db->Quote($ext->attributes()->name);
					}

					// query extension id and client id
					$res = $db->queryObject($query);

					$extensions[] = array(
						'name' => (string) $ext,
						'type' => $ext->getName(),
						'id' => isset($res->id) ? $res->id : 0,
						'client_id' => isset($res->client_id) ? $res->client_id : 0,
						'installer' => new JInstaller(),
						'status' => false);
				}
			}
		}

		// uninstall additional extensions
		for ($i = 0; $i < count($extensions); $i++) {
			if ($extensions[$i]['id'] > 0 && $extensions[$i]['installer']->uninstall($extensions[$i]['type'], $extensions[$i]['id'], $extensions[$i]['client_id'])) {
				$extensions[$i]['status'] = 1;
			}
		}

		self::displayResults($extensions, 'Extensions', 'Extension', 'Uninstalled successfully', 'Uninstall FAILED');

	}

	public static function displayResults($result, $name, $type, $msg_success = 'Installed successfully', $msg_failure = 'NOT Installed') {

		?>

		<h3><?php echo JText::_($name); ?></h3>
		<table class="adminlist">
			<thead>
				<tr>
					<th class="title"><?php echo JText::_($type); ?></th>
					<th width="60%"><?php echo JText::_('Status'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
			</tfoot>
			<tbody>
				<?php
					foreach ($result as $i => $ext) : ?>
					<tr class="row<?php echo $i++ % 2; ?>">
						<td class="key"><?php echo $ext['name']; ?></td>
						<td>
							<?php $style = $ext['status'] ? 'font-weight: bold; color: green;' : 'font-weight: bold; color: red;'; ?>
							<?php $msg_success = $ext['status'] == 2 ? 'Updated successfully' : $msg_success; ?>
							<span style="<?php echo $style; ?>"><?php echo $ext['status'] ? JText::_($msg_success) : JText::_($msg_failure); ?></span>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<?php

	}

}