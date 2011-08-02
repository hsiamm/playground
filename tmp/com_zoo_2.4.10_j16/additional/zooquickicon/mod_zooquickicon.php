<?php
/**
* @package   ZOO Quick Icons
* @file      mod_zooquickicon.php
* @version   2.4.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// load config
jimport('joomla.filesystem.file');
if (!JFile::exists(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php')) {
	return;
}

require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

$zoo = App::getInstance('zoo');

$applications = $zoo->table->application->all(array('order' => 'name'));

if (empty($applications)) {
	return;
}

$float = $zoo->system->language->isRTL() ? 'right' : 'left';

?>

<div id="cpanel">
	<?php foreach ($applications as $application) : ?>
	<div class="icon-wrapper" style="float:<?php echo $float; ?>;">
		<div class="icon">
			<a href="<?php echo JRoute::_('index.php?option='.$zoo->component->self->name.'&changeapp='.$application->id); ?>">
				<img style="width:48px; height:48px;" alt="<?php echo $application->name; ?>" src="<?php echo $application->getIcon(); ?>" />
				<span><?php echo $application->name; ?></span>
			</a>
		</div>
	</div>
	<?php endforeach; ?>
</div>