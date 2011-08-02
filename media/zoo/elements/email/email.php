<?php
/**
* @package   com_zoo Component
* @file      email.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// register ElementRepeatable class
App::getInstance('zoo')->loader->register('ElementRepeatable', 'elements:repeatable/repeatable.php');

/*
   Class: ElementEmail
       The email element class
*/
class ElementEmail extends ElementRepeatable implements iRepeatSubmittable {

	/*
		Function: _hasValue
			Checks if the repeatables element's value is set.

	   Parameters:
			$params - render parameter

		Returns:
			Boolean - true, on success
	*/	
	protected function _hasValue($params = array()) {
		$value = $this->_data->get('value');
		return $this->_containsEmail($value);
	}	
	
	/*
		Function: getText
			Gets the email text.

		Returns:
			String - text
	*/
	public function getText() {
		$text = $this->_data->get('text', '');
		return empty($text) ? $this->_data->get('value', '') : $text;
	}

	/*
		Function: render
			Renders the repeatable element.

	   Parameters:
            $params - render parameter

		Returns:
			String - html
	*/
	protected function _render($params = array()) {

		// init vars
		$mode 		= $this->_containsEmail($this->getText());
		$subject	= $this->_data->get('subject', '');
		$subject 	= !empty($subject) ? 'subject=' . $subject : '';
		$body		= $this->_data->get('body', '');
		$body 		= !empty($body) ? 'body=' . $body : '';
		$mailto 	= $this->_data->get('value', '');
		$text	 	= $this->getText();
						
		if ($subject && $body) {
			$mailto	.= '?' . $subject . '&' . $body;
		} elseif ($subject || $body) {
			$mailto	.= '?' . $subject . $body;
		}
		
		return ltrim($this->app->html->_('email.cloak', $mailto, true, $text, $mode));

	}

	/*
	   Function: _edit
	       Renders the repeatable edit form field.

	   Returns:
	       String - html
	*/		
	protected function _edit(){
        if ($layout = $this->getLayout('edit.php')) {
            return $this->renderLayout($layout,
                array(
                    'element' => $this->identifier,
                    'index' => $this->index(),
                    'text' => $this->_data->get('text'),
                    'email' => $this->_data->get('value'),
                    'subject' => $this->_data->get('subject', ''),
                    'body' => $this->_data->get('body', '')
                )
            );
        }
	}	

	/*
	   Function: _containsEmail
	       Checks for an email address in a text.

	   Returns:
	       Boolean - true if text contains email address, else false
	*/
	protected function _containsEmail($text) {
		return preg_match('/[\w!#$%&\'*+\/=?`{|}~^-]+(?:\.[!#$%&\'*+\/=?`{|}~^-]+)*@(?:[A-Z0-9-]+\.)+[A-Z]{2,6}/i', $text);
	}

	/*
		Function: _renderSubmission
			Renders the element in submission.

	   Parameters:
            $params - submission parameters

		Returns:
			String - html
	*/
	public function _renderSubmission($params = array()) {

        // get params
        $params       = $this->app->data->create($params);
        $trusted_mode = $params->get('trusted_mode');

        if ($layout = $this->getLayout('submission.php')) {
            return $this->renderLayout($layout,
                array(
                    'element' => $this->identifier,
                    'index' => $this->index(),
                    'text' => $this->_data->get('text'),
                    'email' => $this->_data->get('value'),
                    'subject' => $this->_data->get('subject', ''),
                    'body' => $this->_data->get('body', ''),
                    'trusted_mode' => $trusted_mode
                )
            );
        }        
	}

	/*
		Function: _validateSubmission
			Validates the submitted element

	   Parameters:
            $value  - AppData value
            $params - AppData submission parameters

		Returns:
			Array - cleaned value
	*/
	public function _validateSubmission($value, $params) {
        $values    = $value;

        $validator = $this->app->validator->create('string', array('required' => false));
        $text      = $validator->clean($values->get('text'));
        $subject   = $validator->clean($values->get('subject'));
        $body      = $validator->clean($values->get('body'));
        
        $validator = $this->app->validator->create('email', array('required' => $params->get('required')), array('required' => 'Please enter an email address.'));
        $value     = $validator->clean($values->get('value'));

		return compact('value', 'text', 'subject', 'body');
    }


}