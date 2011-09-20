<?php

/**
 * @package   com_zoo Component
 * @file      item.php
 * @version   2.4.10 June 2011
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

// include assets css/js
if (strtolower(substr($GLOBALS[($this->app->joomla->isVersion('1.5') ? 'mainframe' : 'app')]->getTemplate(), 0, 3)) != 'yoo') {
    $this->app->document->addStylesheet('media:zoo/assets/css/reset.css');
}
$this->app->document->addStylesheet($this->template->resource . 'assets/css/zoo.css');

$css_class = $this->application->getGroup() . '-' . $this->template->name;
?>

<?php if ($this->renderer->pathExists('item/' . $this->item->type)) : ?>
    <?php echo $this->renderer->render('item.' . $this->item->type . '.full', array('view' => $this, 'item' => $this->item)); ?>
<?php else : ?>
    <?php echo $this->renderer->render('item.full', array('view' => $this, 'item' => $this->item)); ?>
    <?php echo $this->app->comment->renderComments($this, $this->item); ?>
<?php endif; ?>