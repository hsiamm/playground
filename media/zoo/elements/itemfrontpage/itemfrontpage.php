<?php
/**
* @package   com_zoo
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

/*
	Class: ElementItemFrontpage
		The item frontpage element class
*/
class ElementItemFrontpage extends Element implements iSubmittable {

	protected $_frontpage;

	/*
		Function: hasValue
			Checks if the element's value is set.

	   Parameters:
			$params - render parameter

		Returns:
			Boolean - true, on success
	*/
	public function hasValue($params = array()) {
		return true;
	}

	/*
	   Function: edit
	       Renders the edit form field.

	   Returns:
	       String - html
	*/
	public function edit() {
		return null;
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
		return in_array(0, $this->_item->getRelatedCategoryIds());
	}

	/*
		Function: renderSubmission
			Renders the element in submission.

	   Parameters:
            $params - AppData submission parameters

		Returns:
			String - html
	*/
	public function renderSubmission($params = array()) {
		$frontpage = isset($this->_frontpage) ? $this->_frontpage : in_array(0, $this->_item->getRelatedCategoryIds());
		return $this->app->html->_('select.booleanlist', $this->getControlName('value'), null, $frontpage);
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

		// connect to submission aftersave event
		$this->app->event->dispatcher->connect('submission:saved', array($this, 'aftersubmissionsave'));

        return array('value' => (bool) $value->get('value'));
	}

	/*
		Function: afterSubmissionSave
			Callback after item submission is saved

		Returns:
			void
	*/
	public function afterSubmissionSave() {
		if ($this->_frontpage) {
			$this->app->database->query("INSERT IGNORE INTO ".ZOO_TABLE_CATEGORY_ITEM." (item_id, category_id) VALUES (".(int) $this->_item->id .", 0)");
		} else {
			$this->app->database->query("DELETE FROM ".ZOO_TABLE_CATEGORY_ITEM." WHERE item_id=".(int) $this->_item->id ." AND category_id=0");
		}
	}

	/*
		Function: bindData
			Set data through data array.

		Parameters:
			$data - array

		Returns:
			Void
	*/
	public function bindData($data = array()) {
		$this->_frontpage = (bool) @$data['value'];
	}

}