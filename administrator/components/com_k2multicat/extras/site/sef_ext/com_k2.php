<?php
/**
 * @version		$Id: com_k2.php 1259 2011-10-24 16:48:35Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2011 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

if (!function_exists('getCategoryPath')) {

  function getCategoryPath($catid, $begin = false) {
    static $array = array();
    if (intval($catid)==0) {
      return false;
    }
    if ($begin) {
      $array = array();
    }

    $user = &JFactory::getUser();
    $aid = (int) $user->get('aid');
    $catid = (int) $catid;
    $db = &JFactory::getDBO();
    $query = "SELECT * FROM #__k2_categories WHERE id={$catid} AND published=1";
	
	if(version_compare( JVERSION, '1.6.0', 'ge' )) {
		$query .= " AND access IN(".implode(',', $user->authorisedLevels()).") ";
	}
	else {
		$query .= " AND access<={$aid} ";
	}

    $db->setQuery($query);
    $rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
      echo $db->stderr();
      return false;
    }

    foreach ($rows as $row) {
      array_push($array, $row->alias);
      getCategoryPath($row->parent, false);
    }

    return array_reverse($array);
  }

}

// ------------------  standard plugin initialize function - don't change ---------------------------
global $sh_LANG;
$shLangName = '';
$shLangIso = '';
$title = array();
$shItemidString = '';
$dosef = shInitializePlugin($lang, $shLangName, $shLangIso, $option);
if ($dosef == false)
return;

$shHomePageFlag = false;
$shHomePageFlag = !$shHomePageFlag ? shIsHomepage($string) : $shHomePageFlag;

// remove common URL from GET vars list, so that they don't show up as query string in the URL
shRemoveFromGETVarsList('option');
shRemoveFromGETVarsList('lang');
if (! empty($Itemid))
shRemoveFromGETVarsList('Itemid');
if (! empty($limit))
shRemoveFromGETVarsList('limit');
if (isset($limitstart))
shRemoveFromGETVarsList('limitstart'); // limitstart can be zero

// start by inserting the menu element title (just an idea, this is not required at all)
$task = isset($task) ? @$task : null;
$view = isset($view) ? @$view : null;
$Itemid = isset($Itemid) ? @$Itemid : null;

// Set dummy task for module feeds
if($view == 'itemlist' && isset($moduleID)) {
	$task = 'module';
}

// K2 parameters
$params = &JComponentHelper::getParams('com_k2');
$authorPrefix = $params->get('sh404SefLabelUser', 'blog');
$itemlistPrefix = $params->get('sh404SefLabelCat', '');
$itemPrefix = $params->get('sh404SefLabelItem', 2);
$sh404SefTitleAlias =  $params->get('sh404SefTitleAlias', 'alias');

$menu = &JSite::getMenu();
$menuparams = NULL;
$menuparams = $menu->getParams($Itemid);

if (isset($task) && (
$task == 'calendar' || $task == 'edit' || $task == 'add' || $task == 'save' || $task == 'deleteAttachment' || $task == 'extraFields' || $task == 'checkin' || $task == 'vote' || $task == 'getVotesNum' || $task == 'getVotesPercentage' || $task == 'comment' || $task == 'download'))
$dosef = false;

if ($view == 'item' && $task == 'tag')
$dosef = false;

if ($view == 'comments')
$dosef = false;

switch ($view) {

  case 'item':
    if (isset($id) && $id > 0 && $task != 'download') {
		$id = (int)$id;
			if (!shTranslateUrl($option, $shLangName)) {
				$query = 'SELECT '.$sh404SefTitleAlias.', catid FROM #__k2_items WHERE id = '.$id;
			} else {
				$query = 'SELECT id, '.$sh404SefTitleAlias.', catid FROM #__k2_items WHERE id = '.$id;
			}
      
      $database->setQuery($query);
      if (shTranslateUrl($option, $shLangName))
      $row = $database->loadObject();
      else
      $row = $database->loadObject(false);

      switch($itemPrefix) {
        case 0:
          break;
        case 1:
          $fullPath = getCategoryPath($row->catid, true);
          $title[] = array_pop( $fullPath);
          break;
        default:
        case 2:
          $fullPath = getCategoryPath($row->catid, true);
          foreach ($fullPath as $path) {
            $title[] = $path;
          }
          break;
      }

      $title[] = $row->alias;
      shMustCreatePageId( 'set', true);
    }
    break;

  case 'itemlist':

    switch ($task) {

      case 'category':

        if (! empty($itemlistPrefix)) {
          $title[] = $itemlistPrefix;
        }
        $fullPath = getCategoryPath($id, true);
        foreach ($fullPath as $path) {
          $title[] = $path;
        }
        shMustCreatePageId( 'set', true);
        break;

      case 'user':
        $user = &JFactory::getUser($id);
        if (! empty($authorPrefix)) {
          $title[] = $authorPrefix;
        }
        $title[] = $user->name;
        break;

      case 'tag':
        $title[] = 'tag';
        $tag=str_replace('%20','-',$tag);
        $tag=str_replace('+','-',$tag);
        $title[] = $tag;
        shMustCreatePageId( 'set', true);
        break;

      case 'search':
        $title[] = 'search';
        if (! empty($searchword))
        $title[] = $searchword;
        break;

      case 'date':
        $title[] = 'date';
        if (! empty($year))
        $title[] = $year;

        if (! empty($month))
        $title[] = $month;

        if (! empty($day))
        $title[] = $day;
        break;
        
      case 'module':
   		$query = 'SELECT title FROM #__modules WHERE id = '.(int)$moduleID;
        $database->setQuery($query);
        $moduleTitle = $database->loadResult();
        $moduleTitle = str_replace(' ', '-', $moduleTitle);
        $title[] = 'feed';
        $title[] = $moduleTitle;
        break;

      default:
        if (isset($Itemid)) {
          $title[] = $menu->getItem($Itemid)->alias;
          shMustCreatePageId( 'set', true);
        }
        break;

    }

    break;

  case 'latest':
    if (isset($Itemid)) {
      $title[] = $menu->getItem($Itemid)->alias;
      shMustCreatePageId( 'set', true);
    }
    break;

}
shRemoveFromGETVarsList('layout');
shRemoveFromGETVarsList('task');
shRemoveFromGETVarsList('tag');
shRemoveFromGETVarsList('searchword');
shRemoveFromGETVarsList('view');
shRemoveFromGETVarsList('Itemid');
shRemoveFromGETVarsList('year');
shRemoveFromGETVarsList('month');
shRemoveFromGETVarsList('day');
shRemoveFromGETVarsList('id');
shRemoveFromGETVarsList('format'); 
shRemoveFromGETVarsList('moduleID');

// ------------------  standard plugin finalize function - don't change ---------------------------
if ($dosef) {
  $string = shFinalizePlugin($string, $title, $shAppendString, $shItemidString, (isset($limit) ? @$limit : null), (isset($limitstart) ? @$limitstart : null), (isset($shLangName) ? @$shLangName : null));
}
// ------------------  standard plugin finalize function - don't change ---------------------------
