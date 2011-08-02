<?php
/**
* @package   com_zoo Component
* @file      relateditems.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: ElementRelatedItems
		The related items element class
*/
class ElementRelatedItems extends Element implements iSubmittable {

	protected $_related_items;

	/*
		Function: hasValue
			Checks if the element's value is set.

	   Parameters:
			$params - render parameter

		Returns:
			Boolean - true, on success
	*/		
	public function hasValue($params = array()) {
		$items = $this->_getRelatedItems();
		return !empty($items);
	}		
	
	/*
		Function: render
			Renders the element.

	   Parameters:
            $params - render parameter

		Returns:
			String - html
	*/
	public function render($params = array()) {
		
		// init vars
		$params   = $this->app->data->create($params);
		$items    = array();
		$output   = array();
		$renderer = $this->app->renderer->create('item')->addPath(array($this->app->path->path('component.site:'), $this->_item->getApplication()->getTemplate()->getPath()));
		$layout   = $params->get('layout');

		$items = $this->_getRelatedItems();

		// sort items
		$order = $params->get('order');
		if (in_array($order, array('alpha', 'ralpha'))) {
			usort($items, create_function('$a,$b', 'return strcmp($a->name, $b->name);'));
		} elseif (in_array($order, array('date', 'rdate'))) {
			usort($items, create_function('$a,$b', 'return (strtotime($a->created) == strtotime($b->created)) ? 0 : (strtotime($a->created) < strtotime($b->created)) ? -1 : 1;'));
		} elseif (in_array($order, array('hits', 'rhits'))) {
			usort($items, create_function('$a,$b', 'return ($a->hits == $b->hits) ? 0 : ($a->hits < $b->hits) ? -1 : 1;'));
		} elseif ($order == 'random') {
			shuffle($items);
		} else {
			
		}
		
		if (in_array($order, array('ralpha', 'rdate', 'rhits'))) {
			$items = array_reverse($items);
		}

		// create output
		foreach($items as $item) {
			$path   = 'item';
			$prefix = 'item.';
			$type   = $item->getType()->id;
			if ($renderer->pathExists($path.DIRECTORY_SEPARATOR.$type)) {
				$path   .= DIRECTORY_SEPARATOR.$type;
				$prefix .= $type.'.';
			}

			if (in_array($layout, $renderer->getLayouts($path))) {
				$output[] = $renderer->render($prefix.$layout, array('item' => $item));
			} elseif ($params->get('link_to_item', false) && $item->getState()) {
				$url	  = $this->app->route->item($item);
				$output[] = '<a href="'.JRoute::_($url).'" title="'.$item->name.'">'.$item->name.'</a>';
			} else {
				$output[] = $item->name;
			}
		}
		
		return $this->app->element->applySeparators($params->get('separated_by'), $output);
	}
	
	protected function _getRelatedItems($published = true) {

		if ($this->_related_items == null) {

			// init vars
			$table = $this->app->table->item;
			$this->_related_items = array();

			// get items
			$items = $this->_data->get('item', array());

			// check if items have already been retrieved
			foreach ($items as $key => $id) {
				if ($table->isInitialized($id)) {
					$this->_related_items[$id] = $table->get($id);
					unset($items[$key]);
				}
			}

			if (!empty($items)) {
				// get dates
				$db   = $this->app->database;
				$date = $this->app->date->create();
				$now  = $db->Quote($date->toMySQL());
				$null = $db->Quote($db->getNullDate());
				$items_string = implode(', ', $items);
				$conditions = $table->key.' IN ('.$items_string.')'
							. ($published ? ' AND state = 1'
							.' AND '.$this->app->user->getDBAccessString()
							.' AND (publish_up = '.$null.' OR publish_up <= '.$now.')'
							.' AND (publish_down = '.$null.' OR publish_down >= '.$now.')' : '');
				$order = 'FIELD('.$table->key.','.$items_string.')';
				$this->_related_items += $table->all(compact('conditions', 'order'));
			}

		}

		return $this->_related_items;
	}
	
	/*
	   Function: edit
	       Renders the edit form field.

	   Returns:
	       String - html
	*/
	public function edit() {
		return $this->_edit(false);
	}

	/*
		Function: renderSubmission
			Renders the element in submission.

	   Parameters:
            $params - submission parameters

		Returns:
			String - html
	*/
	public function renderSubmission($params = array()) {

		// load assets
		$this->app->html->_('behavior.modal', 'a.modal');
		$this->app->document->addScript('elements:relateditems/relateditems.js');

		return $this->_edit();
		
	}

	protected function _edit($published = true) {

		// build element id
        $id = 'a' . str_replace('-','_',$this->identifier);

		$query = array('controller' => 'item', 'task' => 'element', 'tmpl' => 'component', 'func' => 'selectRelateditem', 'object' => $id);

		// filter types
		$selectable_types = $this->_config->get('selectable_types', array());
		foreach ($selectable_types as $key => $selectable_type) {
			$query["type_filter[$key]"] = $selectable_type;
		}

		// filter items
		if ($this->_item) {
			$query['item_filter'] = $this->_item->id;
		}

		if ($layout = $this->getLayout('edit.php')) {
            return $this->renderLayout($layout,
                array(
                    'element' => $this->identifier,
                    'id' => $id,
                    'data' => $this->_getRelatedItems($published),
					'link' => $this->app->link($query)
                )
            );
        }
	}

	/*
		Function: validateSubmission
			Validates the submitted element

	   Parameters:
            $value  - AppData value
            $params - AppData submission parameters

		Returns:
			Array - cleaned value
	*/
	public function validateSubmission($value, $params) {

        $options     = array('required' => $params->get('required'));
		$messages    = array('required' => 'Please select at least one related item.');

        $validator = $this->app->validator->create('foreach', null, $options, $messages);
        $clean = $validator->clean($value->get('item'));

		$table = $this->app->table->item;
		$selectable_types = $this->_config->get('selectable_types', array());
        if (!empty($selectable_types)) {
			foreach ($clean as $item) {
				if (!empty($item) && !in_array($table->get($item)->type, $this->_config->get('selectable_types', array()))) {
					throw new AppValidatorException('Please choose a correct related item.');
				}
			}
		}

		return array('item' => $clean);
	}

	/*
		Function: loadAssets
			Load elements css/js assets.

		Returns:
			Void
	*/
	public function loadAssets() {
		$this->app->html->_('behavior.modal', 'a.modal');
		$this->app->document->addScript('elements:relateditems/relateditems.js');
	}
	
	/*
	   Function: loadConfig
	       Converts the XML to a data array and calls the bind method.

	   Parameters:
	      XML - The XML for this Element
	*/
	public function loadConfig($xml) {

		parent::loadConfig($xml);
		
		if (isset($xml->selectable_type)) {
			$types = array();
			
			foreach ($xml->selectable_type as $selectable_type) {
				$types[] = (string) $selectable_type->attributes()->value;
			}
			
			$this->_config->set('selectable_types', $types);
		}
	}

	/*
		Function: getConfigForm
			Get parameter form object to render input form.

		Returns:
			Parameter Object
	*/
	public function getConfigForm() {
		
		$form = parent::getConfigForm();
		$form->addElementPath(dirname(__FILE__));

		return $form;
	}
			
	/*
	   Function: getConfigXML
   	      Get elements XML.

	   Returns:
	      Object - AppXMLElement
	*/
	public function getConfigXML($ignore = array()) {

		$xml = parent::getConfigXML(array('selectable_types'));
		
		foreach ($this->_config->get('selectable_types', array()) as $selectable_type) {		
			if ($selectable_type['value'] != '') {
				$xml->addChild('selectable_type')->addAttribute('value', $selectable_type);	
			}
		}
		
		return $xml;
	}
	
}

class ElementRelatedItemsData extends ElementData{

	public function encodeData() {		
		$xml = $this->app->xml->create($this->_element->getElementType())->addAttribute('identifier', $this->_element->identifier);
		foreach($this->_data->get('item', array()) as $item) {
			$xml->addChild('item', $item, null, true);
		}
		return $xml;			
	}
		
	public function decodeXML(AppXMLElement $element_xml) {
		$data = array();
		if (isset($element_xml->item)) {
			$items = array();
			foreach ($element_xml->item as $related_item) {			
				$items[] = (string) $related_item;
			}
			$this->_data->set('item', $items);
		}
	}		
	
}