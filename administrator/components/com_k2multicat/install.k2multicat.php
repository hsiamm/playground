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
$db = JFactory::getDBO();



# get back up of files

$sourcepath =   JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2multicat'.DS.'extras';
$k2adminpath = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS;

$k2path = JPATH_ROOT.DS.'components'.DS.'com_k2'.DS;


  # Bakup files
 if(!file_exists($k2adminpath.'controllers'.DS.'item.phpkc')){
 	JFile::move($k2adminpath.'controllers'.DS.'item.php', $k2adminpath.'controllers'.DS.'item.phpkc');
 }
 
 
 if(!file_exists($k2adminpath.'tables'.DS.'k2item.phpkc')){
 	 JFile::move($k2adminpath.'tables'.DS.'k2item.php', $k2adminpath.'tables'.DS.'k2item.phpkc');
 }
 
 
 if(!file_exists($k2adminpath.'models'.DS.'item.phpkc')){
 	 JFile::move($k2adminpath.'models'.DS.'item.php', $k2adminpath.'models'.DS.'item.phpkc');
 }
 if(!file_exists($k2adminpath.'models'.DS.'items.phpkc')){
	 JFile::move($k2adminpath.'models'.DS.'items.php', $k2adminpath.'models'.DS.'items.phpkc');
 }
 if(!file_exists($k2adminpath.'models'.DS.'category.phpkc')){
	 JFile::move($k2adminpath.'models'.DS.'category.php', $k2adminpath.'models'.DS.'category.phpkc');
 }


  
 if(!file_exists($k2adminpath.'views'.DS.'item'.DS.'view.html.phpkc')){
	 JFile::move($k2adminpath.'views'.DS.'item'.DS.'view.html.php', $k2adminpath.'views'.DS.'item'.DS.'view.html.phpkc');
 }
 if(!file_exists($k2adminpath.'views'.DS.'item'.DS.'tmpl'.DS.'default.phpkc')){
	 JFile::move($k2adminpath.'views'.DS.'item'.DS.'tmpl'.DS.'default.php',$k2adminpath.'views'.DS.'item'.DS.'tmpl'.DS.'default.phpkc');
 }
 if(!file_exists($k2adminpath.'views'.DS.'items'.DS.'tmpl'.DS.'default.phpkc')){
	 JFile::move($k2adminpath.'views'.DS.'items'.DS.'tmpl'.DS.'default.php',$k2adminpath.'views'.DS.'items'.DS.'tmpl'.DS.'default.phpkc');
 }
  
 
 
 if(!file_exists($k2path.'models'.DS.'itemlist.phpkc')){
	JFile::move($k2path.'models'.DS.'itemlist.php', $k2path.'models'.DS.'itemlist.phpkc');
 }
 if(!file_exists($k2path.'models'.DS.'item.phpkc')){
	JFile::move($k2path.'models'.DS.'item.php', $k2path.'models'.DS.'item.phpkc');
 }
  

 if(!file_exists($k2path.'sef_ext'.DS.'com_k2.phpkc')){
	 JFile::move($k2path.'sef_ext'.DS.'com_k2.php', $k2path.'sef_ext'.DS.'com_k2.phpkc');
 }
  
 if(!file_exists($k2path.'views'.DS.'item'.DS.'view.html.phpkc')){
	JFile::move($k2path.'views'.DS.'item'.DS.'view.html.php', $k2path.'views'.DS.'item'.DS.'view.html.phpkc');
 }
 
 
 if(!file_exists($k2path.'templates'.DS.'default'.DS.'itemform.phpkc')){
	JFile::move($k2path.'templates'.DS.'default'.DS.'itemform.php', $k2path.'templates'.DS.'default'.DS.'itemform.phpkc');
 }
 
 if(!file_exists($k2path.'templates'.DS.'default'.DS.'category_item.phpkc')){
	JFile::move($k2path.'templates'.DS.'default'.DS.'category_item.php', $k2path.'templates'.DS.'default'.DS.'category_item.phpkc');
 }
 
 
 
 
/* if(!file_exists(JPATH_ROOT.DS.'media'.DS.'k2'.DS.'assets'.DS.'js'.DS.'k2.jskc')){
	JFile::move(JPATH_ROOT.DS.'media'.DS.'k2'.DS.'assets'.DS.'js'.DS.'k2.js', JPATH_ROOT.DS.'media'.DS.'k2'.DS.'assets'.DS.'js'.DS.'k2.jskc');
 }
  */
 
 if(!file_exists($k2path.'controllers'.DS.'item.phpkc')){
   JFile::move($k2path.'controllers'.DS.'item.php', $k2path.'controllers'.DS.'item.phpkc');
 }




  # Copy files

  JFile::copy($sourcepath.DS.'admin'.DS.'controllers'.DS.'item.php', $k2adminpath.'controllers'.DS.'item.php');
  
  JFile::copy($sourcepath.DS.'admin'.DS.'tables'.DS.'k2item.php', $k2adminpath.'tables'.DS.'k2item.php');
  
  JFile::copy($sourcepath.DS.'admin'.DS.'models'.DS.'item.php', $k2adminpath.'models'.DS.'item.php');
  JFile::copy($sourcepath.DS.'admin'.DS.'models'.DS.'items.php', $k2adminpath.'models'.DS.'items.php');
  JFile::copy($sourcepath.DS.'admin'.DS.'models'.DS.'category.php', $k2adminpath.'models'.DS.'category.php');

  JFile::copy($sourcepath.DS.'admin'.DS.'views'.DS.'item'.DS.'view.html.php', $k2adminpath.'views'.DS.'item'.DS.'view.html.php');
  JFile::copy($sourcepath.DS.'admin'.DS.'views'.DS.'item'.DS.'tmpl'.DS.'default.php',$k2adminpath.'views'.DS.'item'.DS.'tmpl'.DS.'default.php');
  JFile::copy($sourcepath.DS.'admin'.DS.'views'.DS.'items'.DS.'tmpl'.DS.'default.php',$k2adminpath.'views'.DS.'items'.DS.'tmpl'.DS.'default.php');

  JFile::copy($sourcepath.DS.'site'.DS.'models'.DS.'itemlist.php', $k2path.'models'.DS.'itemlist.php');
  JFile::copy($sourcepath.DS.'site'.DS.'models'.DS.'item.php', $k2path.'models'.DS.'item.php');

  JFile::copy($sourcepath.DS.'site'.DS.'sef_ext'.DS.'com_k2.php', $k2path.'sef_ext'.DS.'com_k2.php');
  
  JFile::copy($sourcepath.DS.'site'.DS.'views'.DS.'item'.DS.'view.html.php', $k2path.'views'.DS.'item'.DS.'view.html.php');
 
  JFile::copy($sourcepath.DS.'site'.DS.'templates'.DS.'default'.DS.'itemform.php', $k2path.'templates'.DS.'default'.DS.'itemform.php');
  JFile::copy($sourcepath.DS.'site'.DS.'templates'.DS.'default'.DS.'category_item.php', $k2path.'templates'.DS.'default'.DS.'category_item.php');
  
/*  JFile::copy($sourcepath.DS.'site'.DS.'media'.DS.'k2'.DS.'assets'.DS.'js'.DS.'k2.js', JPATH_ROOT.DS.'media'.DS.'k2'.DS.'assets'.DS.'js'.DS.'k2.js');*/
  
  JFile::copy($sourcepath.DS.'site'.DS.'controllers'.DS.'item.php', $k2path.'controllers'.DS.'item.php');
  

  $sql = "ALTER TABLE `#__k2_items` CHANGE `catid` `catid` varchar( 255 ) ";
  $db->setQuery( $sql);
  $db->Query();
  
  $sql = "ALTER TABLE `#__k2_items` ADD `sefcatid` INT NOT NULL AFTER `catid` ";
  $db->setQuery( $sql);
  $db->Query();
  


?>
