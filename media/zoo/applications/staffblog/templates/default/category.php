<?php
/**
 * @package   com_zoo Component
 * @file      category.php
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

// show description only if it has content
if (!$this->category->description) {
    $this->params->set('template.show_description', 0);
}

// show image only if an image is selected
if (!($image = $this->category->getImage('content.image'))) {
    $this->params->set('template.show_image', 0);
}

$css_class = $this->application->getGroup() . '-' . $this->template->name;
?>

<div class="onecol">
    <h1>Our <?php echo $this->category->name; ?>s</h1>
</div>

<div style="clear:both;"></div>
<div class="bump">&nbsp;</div>
<div style="clear:both;"></div>

<?php
// render items
if (count($this->items)) {
    echo $this->partial('items');
}
?>
