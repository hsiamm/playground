<?php
/**
* @package   com_zoo Component
* @file      item.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: ItemRenderer
		The class for rendering items and its assigned positions.
*/
class ItemRenderer extends AppRenderer {

	protected $_item;
    protected $_form;
    protected $_config;
	protected $_config_file = 'positions.config';
	protected $_xml_file = 'positions.xml';

	/*
		Function: render
			Render objects using a layout file.

		Parameters:
			$layout - Layout name.
			$args - Arguments to be passed to into the layout scope.

		Returns:
			String
	*/
	public function render($layout, $args = array()) {

		// set item
		$this->_item = isset($args['item']) ? $args['item'] : null;

        // set form
		$this->_form = isset($args['form']) ? $args['form'] : null;

        if ($this->_form) {
            $this->_item = $this->_form->getItem();
        }

		// trigger beforedisplay event
		if (!$this->_form && $this->_item) {
			$this->app->event->dispatcher->notify($this->app->event->create($this->_item, 'item:beforedisplay'));
		}

		// render layout
		$result = parent::render($layout, $args);
		
		// trigger afterdisplay event
		if (!$this->_form && $this->_item) {
			$this->app->event->dispatcher->notify($this->app->event->create($this->_item, 'item:afterdisplay', array('html' => &$result)));
		}
		
		return $result;

	}

	/*
		Function: checkPosition
			Check if position generates output.

		Parameters:
			$position - Position name.

		Returns:
			Boolean
	*/
	public function checkPosition($position) {

		foreach ($this->_getConfigPosition($position) as $data) {
            if ($element = $this->_item->getElement($data['element'])) {
                if ($element->hasValue((array) $data)) {
                    return true;
                }
            }
        }

		return false;
	}

	/*
		Function: renderPosition
			Render position output.

		Parameters:
			$position - Position name.
			$args - Arguments to be passed to into the position scope.

		Returns:
			Void
	*/
	public function renderPosition($position, $args = array()) {

		// init vars
		$elements = array();
		$output   = array();
		$user	  = $this->app->user->get();

		// get style
		$style = isset($args['style']) ? $args['style'] : 'default';

		// store layout
		$layout = $this->_layout;

		// render elements
		foreach ($this->_getConfigPosition($position) as $data) {
            if ($element = $this->_item->getElement($data['element'])) {

				if (!$element->canAccess()) {
					continue;
				}

                // set params
                $params = array_merge($data, $args);

                // check value
                if ($element->hasValue($params)) {
                    $elements[] = compact('element', 'params');
                }
            }
        }

        foreach ($elements as $i => $data) {
            $params  = array_merge(array('first' => ($i == 0), 'last' => ($i == count($elements)-1)), $data['params']);

			// trigger elements beforedisplay event
			$render = true;
			$this->app->event->dispatcher->notify($this->app->event->create($this->_item, 'element:beforedisplay', array('render' => &$render, 'element' => $data['element'], 'params' => $params)));

			if ($render) {
				$output[$i] = parent::render("element.$style", array('element' => $data['element'], 'params' => $params));

				// trigger elements afterdisplay event
				$this->app->event->dispatcher->notify($this->app->event->create($this->_item, 'element:afterdisplay', array('html' => &$output[$i], 'element' => $data['element'], 'params' => $params)));
			}
        }

		// restore layout
		$this->_layout = $layout;

		return implode("\n", $output);
	}

	/*
		Function: checkSubmissionPosition
			Check if position generates output.

		Parameters:
			$position - Position name.

		Returns:
			Boolean
	*/
	public function checkSubmissionPosition($position) {
		$data_array = $this->_getConfigPosition($position);
		if (!$this->_form->getSubmission()->isInTrustedMode()) {
			foreach($data_array as $data) {
				if ($element = $this->_item->getElement($data['element'])) {
					$metadata = $element->getMetaData();
					if ($metadata['trusted'] != 'true') {
						return true;
					}
				}
			}
			return false;
		}
		return (bool) count($data_array);
	}

	/*
		Function: renderSubmissionPosition
			Render submission position output.

		Parameters:
			$position - Position name.
			$args - Arguments to be passed to into the position scope.

		Returns:
			Void
	*/
	public function renderSubmissionPosition($position, $args = array()) {

		// init vars
		$elements = array();
		$output   = '';
        $trusted_mode = !$this->app->user->get()->guest && $this->_form->getSubmission()->isInTrustedMode();
		$show_tooltip = $this->_form->getSubmission()->showTooltip();

		// get style
		$style = isset($args['style']) ? $args['style'] : 'default';

		// store layout
		$layout = $this->_layout;

		// render elements
        foreach ($this->_getConfigPosition($position) as $data) {
            if (($element = $this->_item->getElement($data['element'])) && $field = $this->_form->getFormField($data['element'])) {

				if (!$element->canAccess()) {
					continue;
				}

                $metadata = $element->getMetaData();
                if (!$trusted_mode && $metadata['trusted'] == 'true') {
                    continue;
                }

                // bind field data to elements
                if ($field_data = $field->hasError() ? $field->getTaintedValue() : $field->getValue()) {

					if (!$trusted_mode) {
						$field_data = $this->app->submission->filterData($field_data);
					}

                    $element->bindData($field_data);
                } else {
                    $element->bindData();
                }

                // set params
                $params = array_merge((array) $data, $args);

                // check value
                $elements[] = compact('element', 'params', 'field');
            }
        }

        foreach ($elements as $i => $data) {
            $params  = array_merge(array('first' => ($i == 0), 'last' => ($i == count($elements)-1)), compact('trusted_mode', 'show_tooltip'), $data['params']);
            $output .= parent::render("element.$style", array('element' => $data['element'], 'field' => $data['field'], 'params' => $params));
        }

		// restore layout
		$this->_layout = $layout;

		return $output;
	}

	/*
		Function: getPositions
			Retrieve positions of a layout.

		Parameter:
			$dir - point separated path to layout, last part is layout

		Returns:
			Array
	*/
	public function getPositions($dir) {

		// init vars
		$positions = array();

		$parts  = explode('.', $dir);
		$layout = array_pop($parts);
		$path   = implode('/', $parts);

		// parse positions xml
		if ($xml = $this->app->xml->loadFile(JPath::find($this->_getPath($path), $this->_xml_file))) {
			foreach ($xml->children() as $pos) {
				if ((string) $pos->attributes()->layout == $layout) {
					$positions['name'] = $layout;

					foreach ($pos->children() as $child) {

						if ($child->getName() == 'name') {
							$positions['name'] = (string) $child;
						}

						if ($child->getName() == 'position') {
							if ($child->attributes()->name) {
								$name = (string) $child->attributes()->name;
								$positions['positions'][$name] = (string) $child;
							}
						}
					}

					break;
				}
			}
		}

		return $positions;
	}

	/*
		Function: getConfig
			Retrieve position configuration.

		Parameter:
			$dir - path to config file

		Returns:
			AppParameter
	*/
	public function getConfig($dir) {

		// config file
		if (empty($this->_config)) {

			if ($file = $this->_path->path('default:'.$dir.'/'.$this->_config_file)) {
				$content = JFile::read($file);
			} else {
				$content = null;
			}

			$this->_config = $this->app->parameter->create($content);
		}

		return $this->_config;
	}

	/*
		Function: saveConfig
			Save position configuration.

		Parameter:
			$config - Configuration
			$file - File to save configuration

		Returns:
			Boolean
	*/
	public function saveConfig($config, $file) {

		if (JFile::exists($file) && !is_writable($file)) {
			throw new AppException(sprintf('The config file is not writable (%s)', $file));
		}

		if (!JFile::exists($file) && !is_writable(dirname($file))) {
			throw new AppException(sprintf('Could not create config file (%s)', $file));
		}

		// Joomla 1.6 JFile::write expects $buffer to be reference
		$config_string = (string) $config;
		return JFile::write($file, $config_string);
	}

	public function pathExists($dir) {
		return (bool) $this->_getPath($dir);
	}

    protected function _getConfigPosition($position) {
        $application = $this->_item->getApplication();
		$type		 = $this->_item->getType();
		$config		 = $this->getConfig('item')->get($application->getGroup().'.'.$type->id.'.'.$this->_layout);

        return $config && isset($config[$position]) ? $config[$position] : array();
    }

}