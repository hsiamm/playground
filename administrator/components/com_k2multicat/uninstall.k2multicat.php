<?php
/*------------------------------------------------------------------------
 # com_k2multicat - Assign k2 item to multiple categories
 # ------------------------------------------------------------------------
 # author    US Joomla Pros
 # copyright Copyright (C) 2010 USJoomlaPros.com. All Rights Reserved.
 # @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 # Websites: http://www.USJoomlaPros.com
 # Technical Support:  Forum - http://www.USJoomlaPros.com/forum.html
 -------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');

# get back up of files
$k2adminpath = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS;
$k2path = JPATH_ROOT.DS.'components'.DS.'com_k2'.DS;



JFile::move($k2adminpath.'controllers'.DS.'item.phpkc', $k2adminpath.'controllers'.DS.'item.php');

JFile::move($k2adminpath.'tables'.DS.'k2item.phpkc', $k2adminpath.'tables'.DS.'k2item.php');

JFile::move($k2adminpath.'models'.DS.'item.phpkc', $k2adminpath.'models'.DS.'item.php');
JFile::move($k2adminpath.'models'.DS.'items.phpkc', $k2adminpath.'models'.DS.'items.php');
JFile::move($k2adminpath.'models'.DS.'category.phpkc', $k2adminpath.'models'.DS.'category.php');

JFile::move($k2adminpath.'views'.DS.'item'.DS.'view.html.phpkc', $k2adminpath.'views'.DS.'item'.DS.'view.html.php');
JFile::move($k2adminpath.'views'.DS.'item'.DS.'tmpl'.DS.'default.phpkc',$k2adminpath.'views'.DS.'item'.DS.'tmpl'.DS.'default.php');
JFile::move($k2adminpath.'views'.DS.'items'.DS.'tmpl'.DS.'default.phpkc',$k2adminpath.'views'.DS.'items'.DS.'tmpl'.DS.'default.php');

JFile::move($k2path.'models'.DS.'itemlist.phpkc', $k2path.'models'.DS.'itemlist.php');
JFile::move($k2path.'models'.DS.'item.phpkc', $k2path.'models'.DS.'item.php');

JFile::move($k2path.'views'.DS.'item'.DS.'view.html.phpkc', $k2path.'views'.DS.'item'.DS.'view.html.php');

JFile::copy($k2path.'templates'.DS.'default'.DS.'itemform.phpkc', $k2path.'templates'.DS.'default'.DS.'itemform.php');
JFile::copy($k2path.'templates'.DS.'default'.DS.'category_item.phpkc', $k2path.'templates'.DS.'default'.DS.'category_item.php');

//JFile::copy(JPATH_ROOT.DS.'media'.DS.'k2'.DS.'assets'.DS.'js'.DS.'k2.jskc', JPATH_ROOT.DS.'media'.DS.'k2'.DS.'assets'.DS.'js'.DS.'k2.js');

JFile::move($k2path.'controllers'.DS.'item.phpkc', $k2path.'controllers'.DS.'item.php');


?>
