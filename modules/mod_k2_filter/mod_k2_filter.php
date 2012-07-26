<?php 

/*
// K2 Multiple Extra fields Filter and Search module by Andrey M
// molotow11@gmail.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).DS.'helper.php');

// Main params
$moduleclass_sfx = $params->get('moduleclass_sfx', '');			// Module Class Suffix
$getTemplate = $params->get('getTemplate','Default');
$resultf = $params->get('resultf', '');					// Results phrase
$noresult = $params->get('noresult', '');					// Noresults
$page_heading = $params->get('page_heading', '');

$field_id = $params->get('field_id', '');						// Select Extra field
$cols = $params->get('cols', '');
$elems = $params->get('elems', 0);
$ajax_results = $params->get('ajax_results', 0);

$filter_template = $params->get('results_template', 0);
$template_selector = $params->get('template_selector', 0);

//Restriction params 
$restrict = $params->get('restrict', 0);
$restmode = $params->get('restmode', 0);
$restcat = $params->get('restcat', '');
$restsub = $params->get('restsub', 1);

//Ordering
$ordering = $params->get('ordering', 0);	
$ordering_default = $params->get('ordering_default', '');
$ordering_extra = $params->get('ordering_extra', 1);
$ordering_default_method = $params->get('ordering_default_method', 'asc');

// Search button params
$showtitles = $params->get('showtitles', 1);					// Show extrafields titles
$button = $params->get('button', 1);							// Show submit button
$button_text = $params->get('button_text', JText::_('MOD_K2_FILTER_BUTTON_SEARCH'));	// Submit button text
$onchange = $params->get('onchange', 0);

$clear_btn = $params->get('clear_btn', 0);	

//Itemid
$itemidv = $params->get('itemidv', 0);
if($itemidv == 0) {
	$itemid = JRequest::getInt("Itemid");
}
else {
	$itemid = $params->get('itemid', '');
}

$document = &JFactory::getDocument();
$document->addStylesheet(JURI::base() . 'modules/mod_k2_filter/assets/table.css');	

if(!JPluginHelper::isEnabled('system', 'k2filter')) {
	if(JRequest::getVar("option") == "com_k2" && JRequest::getVar("view") == "itemlist") {
		echo "K2 Filter plugin is not published.<br />";
	}
}

if(!is_array($field_id) && $field_id == "") {
	echo "Select extra fields in module options! <br />";
}

if(is_array($field_id)) {
	for($i=0; $i<sizeof($field_id); $i++) {
		$j = $field_id[$i];
		$field_type[$i] = $params->get('field_type'.($i+1), '');
		$order[$i] = $params->get('order'.($i+1), ''); 
		$count = $i+1;
	}
}
else { 
	$j = $field_id;
	$field_type[0] = $params->get('field_type1', '');
	$order[0] = $params->get('order1', ''); 
	$count = 1;
}

if(is_array($field_id)) {
	$i=0;
	while($i<sizeof($field_id)) {
	$extra_fields_content[$i] = (modK2FilterHelper::extractExtraFields(modK2FilterHelper::pull($field_id[$i],'value')));
	$i++;		
	}
}
else {
	$extra_fields_content[0] = (modK2FilterHelper::extractExtraFields(modK2FilterHelper::pull($field_id,'value')));
}

if(is_array($field_id)) {
	$i=0;
	while($i<sizeof($field_id)) {
	$extra_fields_name[$i] = (modK2FilterHelper::pull($field_id[$i],'name'));
	$i++;
	}
}
else {
	$extra_fields_name[0] = (modK2FilterHelper::pull($field_id,'name'));
}

require(dirname(__FILE__).DS.'template.php');

?>