<?php
/**
* @package   com_zoo Component
* @file      zooapplication.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

// load config
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

class JFormFieldZooApplication extends JFormField {

	protected $type = 'ZooApplication';

	public function getInput() {

		$app = App::getInstance('zoo');

		$app->html->_('behavior.modal', 'a.modal');
		$app->document->addStylesheet('joomla:elements/zooapplication.css');
		$app->document->addScript('joomla:elements/zooapplication.js');
	
		// init vars
		$params		= $app->parameter->create($this->form->getValue('params'));
		$table		= $app->table->application;
		$field_name = "{$this->formControl}[{$this->group}][%s]";

		// set modes
		$modes = array();

		if ($this->element->attributes()->categories) {
			$modes[] = $app->html->_('select.option', 'categories', JText::_('Categories'));
		}

		if ($this->element->attributes()->types) {
			$modes[] = $app->html->_('select.option', 'types', JText::_('Types'));
		}

		if ($this->element->attributes()->items) {
			$modes[] = $app->html->_('select.option', 'item', JText::_('Item'));
		}
		
		// create application/category select
		$cats    = array();
		$types   = array();
		$options = array($app->html->_('select.option', '', '- '.JText::_('Select Application').' -'));

		foreach ($table->all(array('order' => 'name')) as $application) {

			// application option
			$options[] = $app->html->_('select.option', $application->id, $application->name);

			// hide other options
			$hidden = $this->value != $application->id ? ' hidden' : null;

			// create category select
			if ($this->element->attributes()->categories) {
				$category_name = sprintf($field_name, 'category');				
				$attribs = "class=\"category app-{$application->id}{$hidden}\" role=\"{$category_name}\"";
				$opts    = $this->element->attributes()->frontpage ? array($app->html->_('select.option', '', '&#8226;	'.JText::_('Frontpage'))) : array();
				$cats[]  = $app->html->_('zoo.categorylist', $application, $opts, ($this->value == $application->id ? $category_name : null), $attribs, 'value', 'text', $params->get('category'));
			}

			// create types select
			if ($this->element->attributes()->types) {
				$opts = array();

				foreach ($application->getTypes() as $type) {
					$opts[] = $app->html->_('select.option', $type->id, $type->name);
				}

				$type_name = sprintf($field_name, 'type');
				$attribs = "class=\"type app-{$application->id}{$hidden}\" role=\"{$type_name}\"";
				$types[] = $app->html->_('select.genericlist', $opts, $type_name, $attribs, 'value', 'text', $params->get('type'));
			}
		}

		// create html
		$html[] = '<div id="'.$this->fieldname.'" class="zoo-application">';
		$html[] = $app->html->_('select.genericlist', $options, sprintf($field_name, $this->fieldname), 'class="application"', 'value', 'text', $this->value);

		// create mode select
		if (count($modes) > 1) {
			$html[] = $app->html->_('select.genericlist', $modes, sprintf($field_name, 'mode'), 'class="mode"', 'value', 'text', $params->get('mode'));
		}

		// create categories html
		if (!empty($cats)) {
			$html[] = '<div class="categories">'.implode("\n", $cats).'</div>';
		}

		// create types html
		if (!empty($types)) {
			$html[] = '<div class="types">'.implode("\n", $types).'</div>';
		}
				
		// create items html
		$link = '';
		if ($this->element->attributes()->items) {

			$item_name = JText::_('Select Item');
			
			if ($item_id = $params->get('item_id')) {
				$item = $app->table->item->get($item_id);
				$item_name = $item->name;
			}
			
			$link = $app->link(array('controller' => 'item', 'task' => 'element', 'tmpl' => 'component', 'func' => 'selectZooItem', 'object' => $this->fieldname), false);
		
			$html[] = '<div class="item">';
			$html[] = '<input type="text" id="'.$this->fieldname.'_name" value="'.htmlspecialchars($item_name, ENT_QUOTES, 'UTF-8').'" disabled="disabled" />';
			$html[] = '<a class="modal" title="'.JText::_('Select Item').'"  href="#" rel="{handler: \'iframe\', size: {x: 850, y: 500}}">'.JText::_('Select').'</a>';
			$html[] = '<input type="hidden" id="'.$this->fieldname.'_id" name="'.sprintf($field_name, 'item_id').'" value="'.(int)$item_id.'" />';
			$html[] = '</div>';
			
		}
		
		$html[] = '</div>';
				
		$javascript  = 'jQuery(function($) { jQuery("#'.$this->fieldname.'").ZooApplication({ url: "'.$link.'", msgSelectItem: "'.JText::_('Select Item').'" }); });';
		$javascript  = "<script type=\"text/javascript\">\n// <!--\n$javascript\n// -->\n</script>\n";
		
		return implode("\n", $html).$javascript;
	}

}