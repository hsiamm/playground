<?php
/**
 * @package   com_zoo Component
 * @file      frontpage.php
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
if (!$this->application->description) {
    $this->params->set('template.show_description', 0);
}

// show title only if it has content
if (!$this->application->getParams()->get('content.title')) {
    $this->params->set('template.show_title', 0);
}

// show image only if an image is selected
if (!($image = $this->application->getImage('content.image'))) {
    $this->params->set('template.show_image', 0);
}

$css_class = $this->application->getGroup() . '-' . $this->template->name;
?>

<?php if ($this->params->get('template.show_title') || $this->params->get('template.show_description') || $this->params->get('template.show_image')) : ?>

    <?php if ($this->params->get('template.show_title') || $this->application->getParams()->get('template.subtitle')) : ?>	

        <?php if ($this->params->get('template.show_title')) : ?>
            <div class="onecol">
                <h1><?php echo $this->application->getParams()->get('content.title') ?></h1>
            </div>
        <?php endif; ?>

        <?php if ($this->application->getParams()->get('content.subtitle')) : ?>
            <div class="twocol nomar">
                <p class="subtitle"><?php echo $this->application->getParams()->get('content.subtitle') ?></p>
            </div>
        <?php endif; ?>

        <div style="clear:both;"></div>

    <?php endif; ?>

    <?php if ($this->params->get('template.show_description') || $this->params->get('template.show_image')) : ?>
        <div class="description">
            <?php if ($this->params->get('template.show_image')) : ?>
                <img class="image" src="<?php echo $image['src']; ?>" title="<?php echo $this->application->getParams()->get('content.title'); ?>" alt="<?php echo $this->application->getParams()->get('content.title'); ?>" <?php echo $image['width_height']; ?>/>
            <?php endif; ?>
            <?php if ($this->params->get('template.show_description'))
                echo $this->application->description; ?>
        </div>
    <?php endif; ?>

<?php endif; ?>	

<?php
// render items
if (count($this->items)) {
    echo $this->partial('items');
}
?>