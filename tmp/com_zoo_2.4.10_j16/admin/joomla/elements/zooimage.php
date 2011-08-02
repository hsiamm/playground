<?php
/**
* @package   com_zoo Component
* @file      zooimage.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// load config
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

class JElementZooImage extends JElement {

	var	$_name = 'ZooImage';

	function fetchElement($name, $value, &$node, $control_name) {

		// load js
		App::getInstance('zoo')->document->addScript('assets:js/image.js');

		// init vars
		$params = $this->_parent;
		$width 	= $params->getValue($name.'_width');
		$height = $params->getValue($name.'_height');
	
		// create image select html
		$html[] = '<input class="image-select" type="text" name="'.$control_name.'['.$name.']'.'" value="'.$value.'" />';
		$html[] = '<div class="image-measures">';
		$html[] = JText::_('Width').' <input type="text" name="'.$control_name.'['.$name.'_width]'.'" value="'.$width.'" style="width:30px;" />';
		$html[] = JText::_('Height').' <input type="text" name="'.$control_name.'['.$name.'_height]'.'" value="'.$height.'" style="width:30px;" />';
		$html[] = '</div>';
		
		return implode("\n", $html);
	}

}