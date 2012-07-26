<?php 
/*
// "K2 Tools" Module by JoomlaWorks for Joomla! 1.5.x - Version 2.1
// Copyright (c) 2006 - 2009 JoomlaWorks Ltd. All rights reserved.
// Released under the GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
// More info at http://www.joomlaworks.gr and http://k2.joomlaworks.gr
// Designed and developed by the JoomlaWorks team
// *** Last update: September 9th, 2009 ***
*/

/*
// mod for K2 Extra fields Filter and Search module by Piotr Konieczny
// piotr@smartwebstudio.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'utilities.php');


class modK2FilterHelper {

	// pulls out specified information about extra fields from the database
	function pull($field_id,$what) {
		$query = 'SELECT t.id, t.name as name, t.value as value, t.type as type FROM #__k2_extra_fields AS t WHERE t.published = 1 AND t.id = "'.$field_id.'"';
		$db = &JFactory::getDBO();
		$db->setQuery($query);
		$result = $db->loadObject();
		
		if(!empty($result)) {
			$extra_fields = get_object_vars($result);
		
			switch ($what) {
				case 'name' :
					$output = $extra_fields['name']; break;
				case 'type' :
					$output = $extra_fields['type']; break;
				case 'value' :
					$output = $extra_fields['value']; break;
				default:
					$output = $extra_fields['value']; break;
			}
		}
		else {
			$output = "";
		}
		
		return $output;
	}
	
	// pulls out extra fields of specified item from the database
	function pullItem($itemID) {
		$query = 'SELECT t.id, t.extra_fields FROM #__k2_items AS t WHERE t.published = 1 AND t.id = "'.$itemID.'"';
		$db = &JFactory::getDBO();
		$db->setQuery($query);
		$extra_fields = get_object_vars($db->loadObject());
		$output = $extra_fields['extra_fields'];
		return $output;
	}
	
	// extracts info from JSON format
	function extractExtraFields($extraFields) {
		require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'lib'.DS.'JSON.php');
		$json=new Services_JSON;
		
		$jsonObjects = $json->decode($extraFields);

		if (count($jsonObjects)<1) return NULL;

		// convert objects to array
		foreach ($jsonObjects as $object){
			if (isset($object->name)) $objects[]=$object->name;
			else if (isset($object->id)) {
				$objects[$object->id]=$object->value;
			}
			else return;
		}
		return $objects;
	}
	
	// from thaderweb.com
	function getExtraField($id){
		$db	=	JFactory::getDBO();
		$query	=	"SELECT id, name, value FROM #__k2_extra_fields WHERE id = $id";
		$db->setQuery($query);
		$rows	=	$db->loadObject();

		return $rows;
	}
	
	function getTags(&$params, $restcata = 0) {
		
		$mainframe = &JFactory::getApplication();
		$user = &JFactory::getUser();
		$aid = (int) $user->get('aid');
		$db = &JFactory::getDBO();

		$jnow = &JFactory::getDate();
		$now = $jnow->toMySQL();
		$nullDate = $db->getNullDate();

		$query = "SELECT i.id FROM #__k2_items as i";
		$query .= " LEFT JOIN #__k2_categories c ON c.id = i.catid";
		$query .= " WHERE i.published=1 ";
		$query .= " AND ( i.publish_up = ".$db->Quote($nullDate)." OR i.publish_up <= ".$db->Quote($now)." ) ";
		$query .= " AND ( i.publish_down = ".$db->Quote($nullDate)." OR i.publish_down >= ".$db->Quote($now)." )";
		$query .= " AND i.trash=0 ";
		if(K2_JVERSION=='16'){
			$query .= " AND i.access IN(".implode(',', $user->authorisedLevels()).") ";
		}
		else {
			$query .= " AND i.access <= {$aid} ";
		}
		$query .= " AND c.published=1 ";
		$query .= " AND c.trash=0 ";
		if(K2_JVERSION=='16'){
			$query .= " AND c.access IN(".implode(',', $user->authorisedLevels()).") ";
		}
		else {
			$query .= " AND c.access <= {$aid} ";
		}

		if($params->get('restrict')) {
			if($params->get('restmode') == 0) {
				$tagCategory = $params->get('restcat');
				$tagCategory = str_replace(" ", "", $tagCategory);
				$tagCategory = explode(",", $tagCategory);
				if(is_array($tagCategory)) {
					$tagCategory = array_filter($tagCategory);
				}
				if ($tagCategory) {
					if(!is_array($tagCategory)){
						$tagCategory = (array)$tagCategory;
					}
					foreach($tagCategory as $tagCategoryID){
						$categories[] = $tagCategoryID;
						if($params->get('restsub')){
							$children = modK2FilterHelper::getCategoryChildren($tagCategoryID);
							$categories = @array_merge($categories, $children);
						}
					}
					$categories = @array_unique($categories);
					JArrayHelper::toInteger($categories);
					if(count($categories)==1){
						$query .= " AND i.catid={$categories[0]}";
					}
					else {
						$query .= " AND i.catid IN(".implode(',', $categories).")";
					}
				}
			}
			
			else {
			
				$tagCategory = $restcata;
				if(is_array($tagCategory)) {
					$tagCategory = array_filter($tagCategory);
				}
				if ($tagCategory) {
					if(!is_array($tagCategory)){
						$tagCategory = (array)$tagCategory;
					}
					foreach($tagCategory as $tagCategoryID){
						$categories[] = $tagCategoryID;
						if($params->get('restsub')){
							$children = modK2FilterHelper::getCategoryChildren($tagCategoryID);
							$categories = @array_merge($categories, $children);
						}
					}
					$categories = @array_unique($categories);
					JArrayHelper::toInteger($categories);
					if(count($categories)==1){
						$query .= " AND i.catid={$categories[0]}";
					}
					else {
						$query .= " AND i.catid IN(".implode(',', $categories).")";
					}
				}
			
			}
		}
		
		if(K2_JVERSION == '16') {
			if($mainframe->getLanguageFilter()) {
				$languageTag = JFactory::getLanguage()->getTag();
				$query .= " AND c.language IN (".$db->Quote($languageTag).", ".$db->Quote('*').") AND i.language IN (".$db->Quote($languageTag).", ".$db->Quote('*').") ";
			}
		}

		$db->setQuery($query);
		$IDs = $db->loadResultArray();

		$query = "SELECT tag.name, tag.id
        FROM #__k2_tags as tag
        LEFT JOIN #__k2_tags_xref AS xref ON xref.tagID = tag.id 
        WHERE xref.itemID IN (".implode(',', $IDs).") 
        AND tag.published = 1 ORDER BY tag.name ASC";
		$db->setQuery($query);
		$rows = $db->loadResultArray();
		$cloud = array();

		if (count($rows)) {
			
			foreach ($rows as $tag) {
				if (@array_key_exists($tag, $cloud)) {
					$cloud[$tag]++;
				} else {
					$cloud[$tag] = 1;
				}
			}

			$counter = 0;
			foreach ($cloud as $key=>$value) {
				$tags[$counter]-> {'tag'} = $key;
				$counter++;
			}

			return $tags;
		}
	}
	
	function getCategoryChildren($catid) {

		static $array = array();
		$mainframe = &JFactory::getApplication();
		$user = &JFactory::getUser();
		$aid = (int) $user->get('aid');
		$catid = (int) $catid;
		$db = &JFactory::getDBO();
		$query = "SELECT * FROM #__k2_categories WHERE parent={$catid} AND published=1 AND trash=0 ";
		if(K2_JVERSION=='16'){
			$query .= " AND access IN(".implode(',', $user->authorisedLevels()).") ";
			if($mainframe->getLanguageFilter()) {
				$languageTag = JFactory::getLanguage()->getTag();
				$query .= " AND language IN (".$db->Quote($languageTag).", ".$db->Quote('*').") ";
			}
		}
		else {
			$query .= " AND access <= {$aid}";
		}
		$query .= " ORDER BY ordering ";

		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}
		foreach ($rows as $row) {
			array_push($array, $row->id);
			if (modK2FilterHelper::hasChildren($row->id)) {
				modK2FilterHelper::getCategoryChildren($row->id);
			}
		}
		return $array;
	}
	
	function hasChildren($id) {

		$mainframe = &JFactory::getApplication();
		$user = &JFactory::getUser();
		$aid = (int) $user->get('aid');
		$id = (int) $id;
		$db = &JFactory::getDBO();
		$query = "SELECT * FROM #__k2_categories  WHERE parent={$id} AND published=1 AND trash=0 ";
		if(K2_JVERSION=='16'){
			$query .= " AND access IN(".implode(',', $user->authorisedLevels()).") ";
			if($mainframe->getLanguageFilter()) {
				$languageTag = JFactory::getLanguage()->getTag();
				$query .= " AND language IN (".$db->Quote($languageTag).", ".$db->Quote('*').") ";
			}
		
		}
		else {
			$query .= " AND access <= {$aid}";
		}

		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}

		if (count($rows)) {
			return true;
		} else {
			return false;
		}
	}
	
	function treeselectbox(&$params, $id = 0, $level = 0, $i) {

		$mainframe = &JFactory::getApplication();
		
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$task = JRequest::getCmd('task');
		
		if($params->get('restrict')) {
			if($params->get('restmode') == 0) {
				$root_id = $params->get('restcat');
				$root_id = str_replace(" ", "", $root_id);
				$root_id = explode(",", $root_id);
			}
			else {
				if($view == "itemlist" && $task == "category") 
					$root_id = JRequest::getInt("id");
				else if($view == "item") {
					$id = JRequest::getInt("id");
					$root_id = modK2FilterHelper::getParent($id);
				}
				else {
					$root_id = JRequest::getVar("restcata");
				}
			}
		}
		else $root_id = "";
		
		$category = JRequest::getInt('category');
		if($category == 0 && $option == "com_k2" && $task == "category") {
			$category = JRequest::getInt('id');
		}
		
		$id = (int) $id;
		$user = &JFactory::getUser();
		$aid = (int) $user->get('aid');
		$db = &JFactory::getDBO();
		
		if (($root_id != 0) && ($level == 0)) {
			if(!is_array($root_id)) {
				$query = "SELECT * FROM #__k2_categories WHERE parent={$root_id} AND published=1 AND trash=0 ";
			}
			else {
				$query = "SELECT * FROM #__k2_categories WHERE (";
				
				foreach($root_id as $k => $root) {
					$query .= "parent={$root}";
					
					if($k+1 != count($root_id))
						$query .= " OR ";
				}
				
				$query .= ") AND published=1 AND trash=0 ";
			}
		} else {
			$query = "SELECT * FROM #__k2_categories WHERE parent={$id} AND published=1 AND trash=0 ";
		}

		if(K2_JVERSION=='16'){
			$query .= " AND access IN(".implode(',', $user->authorisedLevels()).") ";
			if($mainframe->getLanguageFilter()) {
				$languageTag = JFactory::getLanguage()->getTag();
				$query .= " AND language IN (".$db->Quote($languageTag).", ".$db->Quote('*').") ";
			}
		}
		else {
			$query .= " AND access <= {$aid}";
		}

		$query .= " ORDER BY ordering";

		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}
		
		if($level == 0) {
		
		echo "<div class='k2filter-field-category-select k2filter-field-".$i."' >";
		
		if($params->get('showtitles', 1)) {
			echo "<h3>".JText::_("MOD_K2_FILTER_FIELD_SELECT_CATEGORY_HEADER")."</h3>";
		}
		
		$onchange = $params->get('onchange', 0);
		if($onchange) {
			$onchange = " onchange=\"document.K2Filter.submit()\"";
		}
		
		echo "<select name=\"category\"".$onchange.">";
		echo "<option value=\"\">".JText::_("MOD_K2_FILTER_FIELD_SELECT_CATEGORY_DEFAULT")."</option>";
		
		}

		$indent = "";
		for ($i = 0; $i < $level; $i++) {
			$indent .= '&ndash; ';
		}
		
		foreach ($rows as $k => $row) {
			if (($option == 'com_k2') && ($category == $row->id)) {
				$selected = ' selected="selected"';
			} else {
				$selected = '';
			}
			if (modK2FilterHelper::hasChildren($row->id)) {
				echo '<option value="'.$row->id.'"'.$selected.'>'.$indent.$row->name.'</option>';
				modK2FilterHelper::treeselectbox($params, $row->id, $level + 1, $i);
			} else {
				echo '<option value="'.$row->id.'"'.$selected.'>'.$indent.$row->name.'</option>';
			}
		}
		if ($level == 0) {
			echo "
				</select>
				</div>
			";
		}
	}
	
	function treeselectbox_multi(&$params, $id = 0, $level = 0, $i, $elems) {

		$mainframe = &JFactory::getApplication();
		
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$task = JRequest::getCmd('task');
		
		if($params->get('restrict')) {
			if($params->get('restmode') == 0) {
				$root_id = $params->get('restcat');
				$root_id = str_replace(" ", "", $root_id);
				$root_id = explode(",", $root_id);
			}
			else {
				if($view == "itemlist" && $task == "category") 
					$root_id = JRequest::getInt("id");
				else if($view == "item") {
					$id = JRequest::getInt("id");
					$root_id = modK2FilterHelper::getParent($id);
				}
				else {
					$root_id = JRequest::getVar("restcata");
				}
			}
		}
		else $root_id = "";
		
		$category = JRequest::getInt('category');
		if($category == 0 && $option == "com_k2" && $task == "category") {
			$category = JRequest::getInt('id');
		}
		
		$id = (int) $id;
		$user = &JFactory::getUser();
		$aid = (int) $user->get('aid');
		$db = &JFactory::getDBO();
		
		if (($root_id != 0) && ($level == 0)) {
			if(!is_array($root_id)) {
				$query = "SELECT * FROM #__k2_categories WHERE parent={$root_id} AND published=1 AND trash=0 ";
			}
			else {
				$query = "SELECT * FROM #__k2_categories WHERE (";
				
				foreach($root_id as $k => $root) {
					$query .= "parent={$root}";
					
					if($k+1 != count($root_id))
						$query .= " OR ";
				}
				
				$query .= ") AND published=1 AND trash=0 ";
			}
		} else {
			$query = "SELECT * FROM #__k2_categories WHERE parent={$id} AND published=1 AND trash=0 ";
		}

		if(K2_JVERSION=='16'){
			$query .= " AND access IN(".implode(',', $user->authorisedLevels()).") ";
			if($mainframe->getLanguageFilter()) {
				$languageTag = JFactory::getLanguage()->getTag();
				$query .= " AND language IN (".$db->Quote($languageTag).", ".$db->Quote('*').") ";
			}
		}
		else {
			$query .= " AND access <= {$aid}";
		}

		$query .= " ORDER BY ordering";

		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}
		
		if($level == 0) {
		
			if($elems > 0) {
				echo "
				<script type=\"text/javascript\">					
					jQuery(document).ready(function () {
						jQuery(\"div.filter_cat_hidden\").hide();
						jQuery(\"a.expand_filter_cat\").click(function() {
							jQuery(\"div.filter_cat_hidden\").slideToggle(\"fast\");
							return false;
						});
					});
				</script>
				";
			}
		
			echo "<div class='k2filter-field-category-select k2filter-field-".$i."' >";
			echo "<h3>".JText::_("MOD_K2_FILTER_FIELD_SELECT_CATEGORY_HEADER")."</h3>";
		
		}

		$indent = "";
		for ($i = 0; $i < $level; $i++) {
			$indent .= '&nbsp&ndash;';
		}
		
		foreach ($rows as $k => $row) {
		
			if($elems > 0 && ($k+1) > $elems && @$cat_switch == 0 && $level == 0) {
				echo "<div class='filter_cat_hidden'>";
				$cat_switch = 1;
			}
		
			if ($option == 'com_k2') {
				$selected = '';
				if(is_array($category) == true) {
					foreach($category as $cat) {
						if($cat == $row->id)
							$selected = ' checked="checked"';
					}
				}
				else {
					if($category == $row->id)
							$selected = ' checked="checked"';
				}
			} else {
				$selected = '';
			}
			if (modK2FilterHelper::hasChildren($row->id)) {
				echo $indent.'<input name="category[]" type="checkbox" value="'.$row->id.'"'.$selected.' id="'.$row->name . $row->id . '" />';
				echo '<label for="'.$row->name.$row->id.'">'.$row->name.'</label><br />';
				modK2FilterHelper::treeselectbox_multi($params, $row->id, $level + 1, $i, $elems);
			} else {
				echo $indent.'<input name="category[]" type="checkbox" value="'.$row->id.'"'.$selected.' id="'.$row->name . $row->id . '" />';
				echo '<label for="'.$row->name.$row->id.'">'.$row->name.'</label><br />';
			}
		}
		
		if ($level == 0) {
		
			if($elems > 0) {
				echo "</div>
					<p>
						<a href=\"#\" class=\"button expand expand_filter_cat\">".JText::_("MOD_K2_FILTER_MORE")."</a>
					</p>
				";
			}
		
			echo "
				</div>
			";
		}
	}
	
	function getParent($id) {
		$db = &JFactory::getDBO();
		
		$query = "SELECT * FROM #__k2_items WHERE id = {$id}";
		$db->setQuery($query);
		$result = $db->loadObject();
		
		return $result->catid;
	}
	
}
