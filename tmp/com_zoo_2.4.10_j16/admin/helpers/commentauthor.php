<?php
/**
* @package   com_zoo Component
* @file      commentauthor.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
   Class: CommentAuthorHelper
   	  Comment author helper class.
*/
class CommentAuthorHelper extends AppHelper {

	/*
		Function: __construct
			Class Constructor.
	*/
	public function __construct($app) {
		parent::__construct($app);

		// load class
		$this->app->loader->register('CommentAuthor', 'classes:commentauthor.php');
	}

	/*
		Function: create
			Creates a Comment author instance

		Parameters:
			$type - Comment author type

		Returns:
			CommentAuthor
	*/
	public function create($type = '', $args = array()) {

		// load renderer class
		$class = $type ? 'CommentAuthor'.$type : 'CommentAuthor';

		return $this->app->object->create($class, $args);

	}

}