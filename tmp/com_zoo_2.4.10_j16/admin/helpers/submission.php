<?php
/**
* @package   com_zoo Component
* @file      submission.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/*
	Class: SubmissionHelper
		Helper class for submission
*/
class SubmissionHelper extends AppHelper {

	/*
		Function: filterData
			Remove html from data

		Parameters:
			$data - Data

		Returns:
			String
	*/
	public function filterData($data) {
        
		if (is_array($data) || $data instanceof Traversable) {

			$result = array();
			foreach ($data as $key => $value) {
				$result[$key] = $this->filterData($value);
			}
			return $result;

        } elseif (is_object($data)) {
            
            $result = new stdClass();
            foreach (get_object_vars($data) as $key => $value) {
				$result->$key = $this->filterData($value);
			}
			return $result;

		} else {

			// remove all html tags or escape if in [code] tag
			$data = preg_replace_callback('/\[code\](.+?)\[\/code\]/is', create_function('$matches', 'return htmlspecialchars($matches[0]);'), $data);
			$data = strip_tags($data);

			return $data;

		}
	}

	/*
		Function: translateIDToAlias
			Translate submission id to alias.

		Parameters:
			$id - Submission id

		Returns:
			Mixed - Null or Submission alias string
	*/
	public function translateIDToAlias($id){

		if ($submission = $this->app->table->submission->get($id)) {
			return $submission->alias;
		}

		return null;
	}

	/*
		Function: translateAliasToID
			Translate submission alias to id.

		Return:
			Int - The submission id or 0 if not found
	*/
	public function translateAliasToID($alias) {

		// init vars
		$db = $this->app->database;

		// search alias
		$query = 'SELECT id'
			    .' FROM '.ZOO_TABLE_SUBMISSION
			    .' WHERE alias = '.$db->Quote($alias);
		
		return $db->queryResult($query);
	}

	/*
		Function: getAlias
			Get unique submission alias.

		Parameters:
			$id - Submission id
			$alias - Submission alias

		Returns:
			Mixed - Null or Submission alias string
	*/
	public function getUniqueAlias($id, $alias = '') {

		if (empty($alias) && $id) {
			$alias = JFilterOutput::stringURLSafe($this->app->table->submission->get($id)->name);
		}

		if (!empty($alias)) {
			$i = 2;
			$new_alias = $alias;
			while ($this->checkAliasExists($new_alias, $id)) {
				$new_alias = $alias . '-' . $i++;
			}
			return $new_alias;
		}

		return $alias;
	}

	/*
 		Function: checkAliasExists
 			Method to check if a alias already exists.
	*/
	public function checkAliasExists($alias, $id = 0) {

		$xid = intval($this->translateAliasToID($alias));
		if ($xid && $xid != intval($id)) {
			return true;
		}

		return false;
	}

	/*
		Function: getSubmissionHash
			Retrieve hash of submission, type, item.

		Parameters:
			$submission_id - Submission id
			$type_id - Type id
			$item_id - Item id

		Returns:
			String
	*/
	public function getSubmissionHash($submission_id, $type_id, $item_id = 0) {

		// get secret from config
		$secret = $this->app->system->config->getValue('config.secret');

        $item_id = empty($item_id) ? 0 : $item_id;

		return md5($submission_id.$type_id.$item_id.$secret);
	}

	/*
		Function: sendNotificationMail
			Send notification email

		Parameters:
			$submission - Submission
			$recipients - Array email => name
			$layout - The layout

		Returns:
			Void
	*/
	public function sendNotificationMail($item, $recipients, $layout) {

		// workaround to make sure JSite is loaded
		$this->app->loader->register('JSite', 'root:includes/application.php');

		// init vars
		$website_name = $this->app->system->application->getCfg('sitename');
		$item_link	  = JURI::root().'administrator/index.php?'.http_build_query(array(
				'option' => $this->app->component->self->name,
				'controller' => 'item',
				'task' => 'edit',
				'cid[]' => $item->id,
			), '', '&');

		// send email to $recipients
		foreach ($recipients as $email => $name) {

			if (empty($email)) {
				continue;
			}

			$mail = $this->app->mail->create();
			$mail->setSubject(JText::_("New Submission notification")." - ".$item->name);
			$mail->setBodyFromTemplate($item->getApplication()->getTemplate()->resource.$layout, compact(
				'item',
				'submission',
				'website_name',
				'email',
				'name',
				'item_link'
			));
			$mail->addRecipient($email);
			$mail->Send();
		}
	}

}

/*
	Class: SubmissionHelperException
*/
class SubmissionHelperException extends AppException {}