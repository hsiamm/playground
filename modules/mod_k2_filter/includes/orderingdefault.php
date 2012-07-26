<?php

defined('_JEXEC') or die('Restricted access');

if(K2_JVERSION=='16'){
	jimport('joomla.form.formfield');
	class JFormFieldOrderingDefault extends JFormField {

		var	$type = 'orderingdefault';

		function getInput(){
			return JElementOrderingDefault::fetchElement($this->name, $this->value, $this->element, $this->options['control']);
		}
	}
}

jimport('joomla.html.parameter.element');

class JElementOrderingDefault extends JElement {
	var $_name = 'orderingdefault';

	function fetchElement($name, $value, &$node, $control_name)
	{
	
			$mitems[] = JHTML::_('select.option',  'date', JText::_('MOD_K2_FILTER_ORDERING_DATE') );
			$mitems[] = JHTML::_('select.option',  'alpha', JText::_('MOD_K2_FILTER_ORDERING_TITLE') );
			$mitems[] = JHTML::_('select.option',  'order', JText::_('MOD_K2_FILTER_ORDERING_ORDER') );
			$mitems[] = JHTML::_('select.option',  'featured', JText::_('MOD_K2_FILTER_ORDERING_FEATURED') );
			$mitems[] = JHTML::_('select.option',  'hits', JText::_('MOD_K2_FILTER_ORDERING_HITS') );
			$mitems[] = JHTML::_('select.option',  'rand', JText::_('MOD_K2_FILTER_ORDERING_RANDOM') );
			$mitems[] = JHTML::_('select.option',  'best', JText::_('MOD_K2_FILTER_ORDERING_RATING') );
			$mitems[] = JHTML::_('select.option',  'id', JText::_('MOD_K2_FILTER_ORDERING_ID') );
	
			$db = &JFactory::getDBO();
			$query = "SELECT t.id, t.name, t.value, t.type, t.group, t.published, t.ordering FROM #__k2_extra_fields AS t ORDER BY t.ordering";
			$db->setQuery( $query );
			$list = $db->loadObjectList();
			
			foreach ( $list as $item ) {
				$mitems[] = JHTML::_('select.option',  $item->id, '   '.$item->name );
			}
			
			if(K2_JVERSION=='16'){
				$fieldName = $name;
			}
			else {
				$fieldName = $control_name.'['.$name.']';
			}

			$output = JHTML::_('select.genericlist',  $mitems, $fieldName, null, 'value', 'text', $value );
			return $output;
	}
}

?>