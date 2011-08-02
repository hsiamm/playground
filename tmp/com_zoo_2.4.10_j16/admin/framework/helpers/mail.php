<?php
/**
* @package   com_zoo Component
* @file      mail.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: MailHelper
		Mail helper class.
*/
class MailHelper extends AppHelper {

	/*
		Function: __construct
			Class Constructor.
	*/
	public function __construct($app) {
		parent::__construct($app);

		// load class
		$this->app->loader->register('AppMail', 'classes:mail.php');
	}

	/*
		Function: create
			Retrieve a mail object

		Returns:
			AppMail
	*/
	public function create() {
		return new AppMail($this->app);
	}
	
}