<?php 
/**
* @package   com_zoo Component
* @file      _editelement.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// get elements meta data
$metadata = $element->getMetaData();
$form = $element->getConfigForm();
$form->application = $this->application;
$name = $element->getConfig()->get('name', 'New');
$var = 'elements['.$element->identifier.']';

?>

<div class="element-icon edit-element edit-event" title="<?php echo JText::_('Edit element'); ?>"></div>
<div class="element-icon delete-element delete-event" title="<?php echo JText::_('Delete element'); ?>"></div>
<div class="name sort-event" title="<?php echo JText::_('Drag to sort'); ?>"><?php echo $name; ?> <span>(<?php echo $metadata['name']; ?>)</span></div>
<div class="config">
	<?php echo $form->render($var); ?>
	<input type="hidden" name="<?php echo $var; ?>[type]" value="<?php echo $element->getElementType(); ?>" />
</div>