<?php
/**
* @package   com_zoo Component
* @file      user.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: UserHelper
		User helper class. Wrapper for JUser.
*/
class UserHelper extends AppHelper {

	/*
		Function: get
			Retrieve a user object
			If no identifier is supplied the user from current session is returned

		Parameters:
			$id - User identifier

		Returns:
			Mixed
	*/
	public function get($id = null) {

		// get database
		$db = $this->app->database;

		// check if user id exists
		if (!is_null($id) && !$db->queryResult('SELECT id FROM #__users WHERE id = '.$db->getEscaped($id))) {
			return null;
		}

		// get user
		$user = $this->_call(array('JFactory', 'getUser'), array($id));

		// add super administrator var to user
		$user->superadmin = isset($user->usertype) && in_array(strtolower($user->usertype), array('superadministrator', 'super administrator'));

		return $user;
	}
	
	/*
		Function: getByUsername
			Method to retrieve a user object by username.
			
		Parameters:
			$username - Username
		
		Return:
			Mixed
	*/
	public function getByUsername($username) {

		// get database
		$db = $this->app->database;

		// search username
		if ($id = $db->queryResult('SELECT id FROM #__users WHERE username = '.$db->Quote($username))) {
			return $this->get($id);
		}

		return null;
	}

	/*
		Function: getByEmail
			Method to retrieve a user object by email.
			
		Parameters:
			$email - User email address
		
		Return:
			Mixed
	*/
	public function getByEmail($email) {

		// get database
		$db = $this->app->database;

		// search email
		if ($id = $db->queryResult('SELECT id FROM #__users WHERE email = '.$db->Quote($email))) {
			return $this->get($id);
		}

		return null;
	}

	/*
		Function: getState
			Retrieve a value of a user state variable.

		Returns:
			Mixed
	*/
	public function getState($key) {
		$registry = $this->app->session->get('registry');

		if (!is_null($registry)) {
			return $registry->getValue($key);
		}

		return null;
	}

	/*
		Function: setState
			Set a value of a user state variable.

		Returns:
			Mixed
	*/
	public function setState($key, $value) {
		$registry = $this->app->session->get('registry');

		if (!is_null($registry)) {
			return $registry->setValue($key, $value);
		}

		return null;
	}
	
	/*
		Function: getStateFromRequest
			Retrieve a value of a user state variable.

		Returns:
			Mixed
	*/
	public function getStateFromRequest($key, $request, $default = null, $type = 'none') {
		
		$old = $this->getState($key);
		$cur = (!is_null($old)) ? $old : $default;
		$new = $this->app->request->getVar($request, null, 'default', $type);

		if ($new !== null) {
			$this->setState($key, $new);
		} else {
			$new = $cur;
		}

		return $new;
	}

	/*
 		Function: checkUsernameExists
 			Method to check if a username already exists.

		Parameters:
			$username - Username
			$id - User identifier

		Returns:
			Boolean
	*/
	public function checkUsernameExists($username, $id = 0) {
		$user = $this->getByUsername($username);
		return $user && $user->id != intval($id);
	}

	/*
 		Function: checkEmailExists
 			Method to check if a email already exists.

		Parameters:
			$email - User email address
			$id - User identifier

		Returns:
			Boolean
	*/
	public function checkEmailExists($email, $id = 0) {
		$user = $this->getByEmail($email);
		return $user && $user->id != intval($id);
	}

}