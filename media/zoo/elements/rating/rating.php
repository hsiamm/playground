<?php
/**
* @package   com_zoo Component
* @file      rating.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: ElementRating
		The rating element class
*/
class ElementRating extends Element {

	/*
	   Function: Constructor
	*/
	public function __construct() {

		// call parent constructor
		parent::__construct();

		// set callbacks
		$this->registerCallback('vote');
		$this->registerCallback('reset');
	}

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
		Function: render
			Override. Renders the element.

	   Parameters:
            $params - render parameter

		Returns:
			String - html
	*/
	public function render($params = array()) {

		static $instance;

		// init vars
		$stars      = $this->_config->get('stars');
		$allow_vote = $this->_config->get('allow_vote');

		$disabled     = isset($params['rating_disabled']) ? $params['rating_disabled'] : false;
		$show_message = isset($params['show_message']) ? $params['show_message'] : false;

		// init vars
		$instance = empty($instance) ? 1 : $instance + 1;
		$link     = $this->app->link(array('task' => 'callelement', 'format' => 'raw', 'item_id' => $this->_item->id, 'element' => $this->identifier), false);

		$rating = $this->getRating();
		$votes = (int) $this->_data->get('votes', 0);

		// render layout
		if ($layout = $this->getLayout()) {
			return $this->renderLayout($layout, compact('instance', 'stars', 'allow_vote', 'disabled', 'show_message', 'rating', 'votes', 'link'));
		}

		return null;
	}

	/*
	   Function: edit
	       Renders the edit form field.

	   Returns:
	       String - html
	*/
	public function edit() {

		$controller = $this->app->request->getWord('controller');
		$url = $this->app->link(array('controller' => $controller, 'format' => 'raw', 'type' => $this->getType()->identifier, 'elm_id' => $this->identifier, 'item_id' => $this->getItem()->id), false);

		// render layout
		if ($layout = $this->getLayout('edit.php')) {
			return $this->renderLayout($layout, compact('url'));
		}

	}

	/*
		Function: loadAssets
			Load elements css/js assets.

		Returns:
			Void
	*/
	public function loadAssets() {
		$this->app->document->addScript('elements:rating/assets/js/rating.js');

		return $this;
	}

	public function reset() {

		$query = 'DELETE'
				    .' FROM ' . ZOO_TABLE_RATING
			   	    .' WHERE item_id = '.(int) $this->getItem()->id;

		$this->app->database->query($query);

		$this->_data->set('votes', 0);
		$this->_data->set('value', 0);

		//save item
		$this->app->table->item->save($this->getItem());

		return $this->edit();
	}

	/*
		Function: rating
			Get rating.

		Returns:
			String - Rating number
	*/
	public function getRating() {
		return number_format((double) $this->_data->get('value', 0), 1);
	}

	/*
		Function: vote
			Execute vote.

		Returns:
			String - Message
	*/
	public function vote($vote = null) {

		// init vars
		$max_stars  = $this->_config->get('stars');
		$allow_vote = $this->_config->get('allow_vote');

		$db   = $this->app->database;
		$user = $this->app->user->get();
		$date = $this->app->date->create();
		$vote = (int) $vote;

		for ($i = 1; $i <= $max_stars; $i++) {
			$stars[] = $i;
		}

		if (!$this->app->user->canAccess($user, $allow_vote)) {
			return json_encode(array(
				'value' => 0,
				'message' => JText::_('NOT_ALLOWED_TO_VOTE')
			));
		}

		if (in_array($vote, $stars) && isset($_SERVER['REMOTE_ADDR']) && ($ip = $_SERVER['REMOTE_ADDR'])) {

			// check if ip already exists
			$query = 'SELECT *'
				    .' FROM ' . ZOO_TABLE_RATING
			   	    .' WHERE element_id = '.$db->Quote($this->identifier)
			   	    .' AND item_id = '.(int) $this->_item->id
			   	    .' AND ip = '.$db->Quote($ip);

			$db->query($query);

			// voted already
			if ($db->getNumRows()) {
				return json_encode(array(
					'value' => 0,
					'message' => JText::_("You've already voted")
				));
			}

			// insert vote
			$query    = "INSERT INTO " . ZOO_TABLE_RATING
	   	               ." SET element_id = ".$db->Quote($this->identifier)
			   	       ." ,item_id = ".(int) $this->_item->id
		   	           ." ,user_id = ".(int) $user->id
		   	           ." ,value = ".(int) $vote
	   	               ." ,ip = ".$db->Quote($ip)
   	                   ." ,created = ".$db->Quote($date->toMySQL());

			// execute query
			$db->query($query);

			// calculate rating/votes
			$query = 'SELECT AVG(value) AS rating, COUNT(id) AS votes'
				    .' FROM ' . ZOO_TABLE_RATING
				   	.' WHERE element_id = '.$db->Quote($this->identifier)
				    .' AND item_id = '.$this->_item->id
				    .' GROUP BY item_id';

			if ($res = $db->queryAssoc($query)) {
				$this->_data->set('votes', $res['votes']);
				$this->_data->set('value', $res['rating']);
			} else {
				$this->_data->set('votes', 0);
				$this->_data->set('value', 0);
			}
		}

		//save item
		$this->app->table->item->save($this->getItem());

		return json_encode(array(
			'value' => intval($this->getRating() / $max_stars * 100),
			'message' => sprintf(JText::_('%s rating from %s votes'), $this->getRating(), $this->_data->get('votes'))
		));
	}

}

class ElementRatingData extends ElementData{

	public function encodeData() {

		if ($this->_element->getItem()) {

			// calculate rating/votes
			$query = 'SELECT AVG(value) AS rating, COUNT(id) AS votes'
				    .' FROM ' . ZOO_TABLE_RATING
				   	.' WHERE element_id = '.$this->app->database->Quote($this->_element->identifier)
				    .' AND item_id = '.$this->_element->getItem()->id
				    .' GROUP BY item_id';

			if ($res = $this->app->database->queryAssoc($query)) {
				$this->set('votes', $res['votes']);
				$this->set('value', $res['rating']);
			} else {
				$this->set('votes', 0);
				$this->set('value', 0);
			}
		}
		return parent::encodeData();
	}

}