<?php
/**
* @package   com_zoo
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div>
    <div class="row">
        <?php echo $this->app->html->_('control.text', $this->getControlName('location'), $this->get('location'), 'maxlength="255" title="'.JText::_('Location').'" placeholder="'.JText::_('Location').'"'); ?>
    </div>
</div>