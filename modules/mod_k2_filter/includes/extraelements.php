<?php

defined('_JEXEC') or die('Restricted access');

if(K2_JVERSION=='16'){
	jimport('joomla.form.formfield');
	class JFormFieldExtraElements extends JFormField {

		var	$type = 'extraelements';

		function getInput(){
			return JElementExtraElements::fetchElement($this->name, $this->value, $this->element, $this->options['control']);
		}
	}
}

jimport('joomla.html.parameter.element');

class JElementExtraElements extends JElement {
	var $_name = 'extraelements';

	function fetchElement($name, $value, &$node, $control_name)
	{
			
			$db = &JFactory::getDBO();
			
			$query = "SELECT t.*, g.name AS group_name ";
			$query .= "FROM #__k2_extra_fields AS t ";
			$query .= "LEFT JOIN #__k2_extra_fields_groups AS g ON g.id = t.group ";
			$query .= "WHERE t.published = 1 ";
			$query .= "ORDER BY group_name, t.ordering ";
			
			$db->setQuery( $query );
			$list = $db->loadObjectList();
			
			$group = $list[0]->group_name;
			array_splice( $list, 0, 0, $group );
			
			for($i = 1; $i < count($list); $i++) {
				$new_group = $list[$i]->group_name;
				if($new_group != $group) {
					array_splice( $list, $i, 0, $new_group );
					$group = $new_group;
				}
			}

			foreach ( $list as $item ) {
				if(is_object($item)) {
					$mitems[] = JHTML::_('select.option',  $item->id, '   '.$item->name." [".$item->id."]" );
				}
				else {
					$mitems[] = JHTML::_('select.option',  '', '   --------- '.$item.' ---------' );
				}
			}
			
			$mitems[] = JHTML::_('select.option',  10000, '   ---' );
			$mitems[] = JHTML::_('select.option',  10001, '   Not extra field 1' );
			$mitems[] = JHTML::_('select.option',  10002, '   Not extra field 2' );
			$mitems[] = JHTML::_('select.option',  10003, '   Not extra field 3' );
			$mitems[] = JHTML::_('select.option',  10004, '   Not extra field 4' );
			$mitems[] = JHTML::_('select.option',  10005, '   Not extra field 5' );
			$mitems[] = JHTML::_('select.option',  10006, '   Not extra field 6' );
			$mitems[] = JHTML::_('select.option',  10007, '   Not extra field 7' );
			$mitems[] = JHTML::_('select.option',  10008, '   Not extra field 8' );
			$mitems[] = JHTML::_('select.option',  10009, '   Not extra field 9' );

			if(K2_JVERSION=='16'){
				$fieldName = $name.'[]';
			}
			else {
				$fieldName = $control_name.'['.$name.'][]';
			}

			$output = JHTML::_('select.genericlist',  $mitems, $fieldName, 'class="inputbox" style="width:90%;" multiple="multiple" size="10"', 'value', 'text', $value );
			return $output;
	}
}

?>