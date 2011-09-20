<?php

//**************************************************************
// Fly06 Modules Search Plugin
// Copyright (C) 2010-2011 by Frédéric Leroy (aka Fly06)   
// --------------- All Rights Reserved ----------------      
// Homepage   : http://www.fly06.fr/        
// Version    : 2.0 beta    
// Date       : 13/03/11
// License    : GNU/GPL            
//**************************************************************

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$mainframe =& JFactory::getApplication();

if(version_compare(JVERSION,'1.6.0','<')){
	$mainframe->registerEvent( 'onSearch', 'plgSearchModules' );
	$mainframe->registerEvent( 'onSearchAreas', 'plgSearchModuleAreas' );
}else{
	$mainframe->registerEvent( 'onContentSearch', 'plgSearchModules' );
	$mainframe->registerEvent( 'onContentSearchAreas', 'plgSearchModuleAreas' );
}

$lang =& JFactory::getLanguage();
$lang->load('plg_search_modules', JPATH_ADMINISTRATOR); 

/**
 * @return array An array of search areas
 */
function &plgSearchModuleAreas() {

	static $areas = array();
	
	$areas['modules'] = JText::_( 'FLY06MSP_MODULES' );
	
	return $areas;
}

/**
* Modules Search method
*
* The sql must return the following fields that are used in a common display
* routine: href, title, section, created, text, browsernav
* @param string Target search string
* @param string mathcing option, exact|any|all
* @param string ordering option, newest|oldest|popular|alpha|category
*/
function plgSearchModules( $text, $phrase='', $ordering='', $areas=null ) {
	
	// Si ce plugin n'est pas dans les areas, on sort
	if (is_array( $areas )) {
		if (!array_intersect( $areas, array_keys( plgSearchModuleAreas() ) )) {
			return array();
		}
	}
	
	//-------------------------------------------------------
	// Pre-processing
	//-------------------------------------------------------

	$mainframe =& JFactory::getApplication();
	$db		=& JFactory::getDBO();
	$user	=& JFactory::getUser();
	$conf =& JFactory::getConfig();

	$searchText = $text;

	// J1.6 - L'objet params est automatiquement disponible 
	// load plugin params info
	$plugin =& JPluginHelper::getPlugin('search', 'modules');
	if (version_compare(JVERSION,'1.6.0','<')) {
	 	$plg_params = new JParameter( $plugin->params );
	} else {
		$plg_params = new JRegistry;
		$plg_params->loadJSON($plugin->params);
	}

	// Récup paramètres

	// Les modules sont partionnées en trois groupes
	// 1 - HTML Modules (mod_custom) 
	// ==> On cherche dans le champs content de la table #__modules
	$search_modules_type_1 = $plg_params->def( 'search_modules_type_1', 1);
	// 2 - Non HTML Module + cache=1
	// ==> On cherche dans le cache du module
	$search_modules_type_2 = $plg_params->def( 'search_modules_type_2', 0);
	// 3 - Non HTML Module + Non cache=1
	// ==> On exécute le module hors contexte pour récupérer son contenu
	// Les modules qui s'excutent en fonction du contexte doivent être sorties du périmètre de recherche
	$search_modules_type_3 = $plg_params->def( 'search_modules_type_3', 0);

	// Restrictions, champs id, module et position
	$search_modules_positions = trim($plg_params->def( 'search_modules_positions', '' ));
	$no_search_modules_ids = trim($plg_params->def( 'no_search_modules_ids', '' ));
	$no_search_modules = trim($plg_params->def( 'no_search_modules', '' ));

	// Autres paramètres
	$process_cached_modules = $plg_params->def( 'process_cached_modules', 0 );
	$limit = $plg_params->def( 'search_limit', 50 );
	$groupby_moduleid = $plg_params->def( 'groupby_moduleid', 0 );
	$show_module_frequency = $plg_params->def( 'show_module_frequency', 0 );
	$include_assigned_all_modules = $plg_params->def( 'include_assigned_all_modules', 0 );

	// Nettoyage et formatage du paramètre 'search_modules_positions'
	if ($search_modules_positions) {
		$search_modules_positions = explode(',', $search_modules_positions);
		$search_modules_positions = array_map("trim", $search_modules_positions);
		$search_modules_positions = array_filter($search_modules_positions);
		$search_modules_positions = array_unique($search_modules_positions);
		$search_modules_positions = array_map(array(&$db, 'Quote'), $search_modules_positions);
	}

	// Nettoyage et formatage du paramètre 'search_modules_ids'
	if ($no_search_modules_ids) {
		$no_search_modules_ids = explode(',', $no_search_modules_ids);
		$no_search_modules_ids = array_map("trim", $no_search_modules_ids);
		$no_search_modules_ids = array_filter($no_search_modules_ids);
		$no_search_modules_ids = array_unique($no_search_modules_ids);
		$no_search_modules_ids = array_map(array(&$db, 'Quote'), $no_search_modules_ids);
	}

	// Nettoyage et formatage du paramètre 'search_modules'
	if ($no_search_modules) {	
		$no_search_modules = explode(',', $no_search_modules);
		$no_search_modules = array_map("trim", $no_search_modules);
		$no_search_modules = array_filter($no_search_modules);
	
		// Ajout des modules contextuels en dur
		$no_search_modules = array_merge($no_search_modules, array('mod_related_items'));
	
		$no_search_modules = array_unique($no_search_modules);
		$no_search_modules = array_map(array(&$db, 'Quote'), $no_search_modules);
	} else {
		$no_search_modules = array( 'mod_related_items' );
		$no_search_modules = array_map(array(&$db, 'Quote'), $no_search_modules);
	}

	// Keywords
	$text = trim( $text );
	if ($text == '') {
		return array();
	}

	// Section
	$section = JText::_( 'FLY06MSP_MODULES' );

	// Title (J1.6) vs name (J1.5) field (jos_menu)
	$field = (version_compare(JVERSION,'1.6.0','<') ? 'name' : 'title');

	// Ordering
	// J1.6 - le champs 'name' de la table jos_menu a été rebatisé 'title'
	switch ( $ordering ) {
		case 'alpha':
			//$order = 'a.name ASC';
			$order = "a.".$field." ASC";
			break;
		case 'category':
			//$order = 'c.title ASC, a.name ASC';
			$order = "c.title ASC, a.".$field." ASC";
			break;
		case 'popular':
		case 'newest':
		case 'oldest':
		default:
			//$order = 'a.name DESC';
			$order = "a.".$field." DESC";
	}

	// Grouping
	switch ($groupby_moduleid) {
		case 1:
			$menuid = ' MIN(a.id) as id,';
			$concat = ($show_module_frequency
				?
				' CONCAT_WS( " / ", '.$db->Quote($section).', c.title, CONCAT_WS( " ", '.$db->Quote(JText::_( 'FLY06MSP_NUMBER_OF_PAGES_START' )).', COUNT(a.id), ' .$db->Quote(JText::_( 'FLY06MSP_NUMBER_OF_PAGES_END' )).')) AS section,'
				:
				' CONCAT_WS( " / ", '.$db->Quote($section).', c.title ) AS section,'
				);
			$groupby = 'c.id';
			break;
		default:
		case 0:
			$menuid = ' a.id as id,';
			$concat = ' CONCAT_WS( " / ", '.$db->Quote($section).', c.title ) AS section,';	
			$groupby = '';	
			break;
	}

	// Condition de jointure
	switch ($include_assigned_all_modules) {
		case 1:
			$condition = '(b.menuid = 0 OR b.menuid = a.id)';
			break;
		default:
		case 0:
			$condition = 'b.menuid = a.id';
			break;
	}

	// Access Condition
	if (version_compare(JVERSION,'1.6.0','<')) {
		$sql_access = ' AND access <= '.(int) $user->get( 'aid' );
		$sql_a_access = ' AND a.access <= '.(int) $user->get( 'aid' );
	} else {
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		$sql_access = ' AND access IN ('. $groups. ')';
		$sql_a_access = ' AND a.access IN ('. $groups. ')';
	}

	// Cache Parameter
	// J1.6 : Les paramètres sont stockés au format JSON
	if (version_compare(JVERSION,'1.6.0','<')) {
		$regex_cache = '\'"cache":"1"\'';
	} else {
		$regex_cache = '\'cache=1\'';
	}

	// Created			
	if (version_compare(JVERSION,'1.6.0','<')) {
		$created = ' "" as created,';
	} else {
		$created = ' CASE c.publish_down WHEN "0000-00-00 00:00:00" THEN "" ELSE c.publish_down END as created,';
	}

	// Initialisations
	$ids = array();
	$modules = array();

	//-------------------------------------------------------
	// 1 - HTML Modules
	//-------------------------------------------------------

	$rows_1 = array();

	if ($search_modules_type_1) {

		$modules_1 = array();
	
		// On charge les modules répondants aux critères suivants :
		// - publiés
		// - = mod_custom
		$query	= 'SELECT * FROM #__modules'
				. ' WHERE published = 1'
				. $sql_access
				. ' AND client_id = '. (int) $mainframe->getClientId()
				. ' AND module = ' . $db->Quote('mod_custom')
				. ($search_modules_positions ? ' AND position IN (' . implode(',', $search_modules_positions ) . ')' : '')
				. ($no_search_modules_ids ? ' AND id NOT IN (' . implode(',', $no_search_modules_ids ) . ')' : '')
				;

		$db->setQuery( $query );
		$modules_1 = $db->loadObjectList();	
	
		// On renseigne le champs content
		if (count($modules_1)) {

			foreach ($modules_1 as $module) {

				// Pas de contenu, on passe au suivant
				if (empty($module->content)) continue; 
			
				// Si $searchText non trouvé, on passe au suivant
				//if (!searchHelper::checkNoHTML($module, $searchText, array('content'))) continue;
				if (!_doSearch($module->content, $searchText, $phrase)) continue;
						
				$modules[$module->id] = $module;
				$ids[] = $module->id;
			}
			
		}		

	}

	//-------------------------------------------------------
	// 2 - Non HTML Module + cache=1
	//-------------------------------------------------------	

	$rows_2 = array();

	if ($search_modules_type_2 && ($conf->getValue( 'config.caching' ) || $process_cached_modules)) {

		$modules_2 = array();
	
		// On charge les modules répondants aux critères suivants :
		// - publiés
		// - <> mod_custom
		// - cache=1
		$query	= 'SELECT * FROM #__modules'
				. ' WHERE published = 1'
				. $sql_access
				. ' AND client_id = '. (int) $mainframe->getClientId()
				. ' AND module <> ' . $db->Quote('mod_custom')
				. ' AND params REGEXP ' . $regex_cache
				. ($search_modules_positions ? ' AND position IN (' . implode(',', $search_modules_positions ) . ')' : '')
				. ($no_search_modules_ids ? ' AND id NOT IN (' . implode(',', $no_search_modules_ids ) . ')' : '')
				. ($no_search_modules ? ' AND module NOT IN (' . implode(',', $no_search_modules ) . ')' : '')
				;

		$db->setQuery( $query );
		$modules_2 = $db->loadObjectList();	
	
		// On renseigne le champs content
		if (count($modules_2)) {
		
			if ($conf->getValue('config.caching')) {
		
				// On créé un objet $cache
				jimport('joomla.cache.cache');
			
				$options = array(
					'cachebase' 	=> $conf->getValue('config.cache_path'),
					'lifetime' 		=> $conf->getValue('config.cachetime') * 60,	// minutes to seconds
					'language' 		=> $conf->getValue('config.language'),
					'storage'		=> $conf->getValue('config.cache_handler', 'file'),
					'caching'		=> true
				);
		
				$cache = new JCache($options);
			
			}

			foreach ($modules_2 as $module) {

				$data = false;
		
				// On récupère le cache du module si il existe
				if ($conf->getValue('config.caching')) {
					$data = $cache->get($module->id.$user->get('aid', 0), $module->module);
				}

				// cache pas trouvé ou cache non activé
				// On passe au suivant si on ne souhaite pas récupérer le html en exécutant le .php du module
				if (($data == false) && !$process_cached_modules) continue; 

				if ($data != false) {
					$cached = unserialize( $data );
					$module->content = $cached['result'];		
				} else {
					// Get module parameters
					if (version_compare(JVERSION,'1.6.0','<')) {
						$params = new JParameter( $module->params );
					} else {
						$params = new JRegistry;
						$params->loadJSON($module->params);
					}

					// Get module path
					$module->module = preg_replace('/[^A-Z0-9_\.-]/i', '', $module->module);
					$path = JPATH_BASE.DS.'modules'.DS.$module->module.DS.$module->module.'.php';

					// Load the module
					if (file_exists( $path ) && empty($module->content)) {
					
						$lang =& JFactory::getLanguage();
						$lang->load($module->module);

						ob_start();
						require $path;
						$module->content = ob_get_contents();
						ob_end_clean();

					}			
				}
					
				// Pas de contenu, on passe au suivant
				if (empty($module->content)) continue; 
								
				// Si $searchText non trouvé, on passe au suivant
				//if (!searchHelper::checkNoHTML($module, $searchText, array('content'))) continue;
				if (!_doSearch($module->content, $searchText, $phrase)) continue;

				$modules[$module->id] = $module;
				$ids[] = $module->id;
			}
			
		}

	}
	
	//-------------------------------------------------------
	// 3 - Non HTML Module + Non cache=1
	//-------------------------------------------------------	

	$rows_3 = array();	

	if ($search_modules_type_3) {

		$modules_3 = array();
	
		// On charge les modules répondants aux critères suivants :
		// - publiés
		// - <> mod_custom
		// - cache=1
		$query	= 'SELECT * FROM #__modules'
				. ' WHERE published = 1'
				. $sql_access
				. ' AND client_id = '. (int) $mainframe->getClientId()
				. ' AND module <> ' . $db->Quote('mod_custom')
				. ' AND params NOT REGEXP ' . $regex_cache
				. ($search_modules_positions ? ' AND position IN (' . implode(',', $search_modules_positions ) . ')' : '')
				. ($no_search_modules_ids ? ' AND id NOT IN (' . implode(',', $no_search_modules_ids ) . ')' : '')
				. ($no_search_modules ? ' AND module NOT IN (' . implode(',', $no_search_modules ) . ')' : '')
				;

		$db->setQuery( $query );
		$modules_3 = $db->loadObjectList();	
	
		// On renseigne le champs content
		if (count($modules_3)) {
		
			foreach ($modules_3 as $module) {

				// Get module parameters
				if (version_compare(JVERSION,'1.6.0','<')) {
					$params = new JParameter( $module->params );
				} else {
					$params = new JRegistry;
					$params->loadJSON($module->params);
				}

				// Get module path
				$module->module = preg_replace('/[^A-Z0-9_\.-]/i', '', $module->module);
				$path = JPATH_BASE.DS.'modules'.DS.$module->module.DS.$module->module.'.php';

				// Load the module
				if (file_exists( $path ) && empty($module->content)) {
					$lang =& JFactory::getLanguage();
					$lang->load($module->module);

					ob_start();
					require $path;
					$module->content = ob_get_contents();
					ob_end_clean();
				}			
	
				// Pas de contenu, on passe au suivant
				if (empty($module->content)) continue; 
			
				// Si $searchText non trouvé, on passe au suivant
				if (!_doSearch($module->content, $searchText, $phrase)) continue;
			
				$modules[$module->id] = $module;
				$ids[] = $module->id;
			}
			
		}

	}	

	//-------------------------------------------------------
	// Post-processing
	//-------------------------------------------------------

	// Initialisation tableau des résultats
	$results = array();

	// Récupération des Items de menu dans lesquels les modules sont affichés
	if (count($modules)) {
		// On construit le tableau $rows
		$query	= 'SELECT a.'.$field.' as title, c.content as text, "2" as browsernav,'
				. $created
				. $menuid
				. $concat
				. ' CONCAT_WS( "=", "index.php?Itemid", a.id ) AS href,'
				. ' c.id as mod_id'
				. ' FROM #__menu as a'
				. ' INNER JOIN #__modules_menu AS b ON ' . $condition
				. ' INNER JOIN #__modules AS c ON c.id = b.moduleid'
				. ' WHERE c.id IN (' . implode(',', $ids) . ')'
				. ' AND a.published = 1'
				. $sql_a_access
				. ($groupby ? ' GROUP BY ' . $groupby : '')
				. ' ORDER BY '. $order
				;
		$db->setQuery( $query, 0, $limit );
		$rows = $db->loadObjectList();		

		// On renseigne le champs content des objets du tableau $rows
		foreach ($rows as $row) {
			$row->text = $modules[$row->mod_id]->content;
			$results[] = $row;
		}

	}

	// Retour
	return $results;
}


///////////////////////////////////////////////////////
// Helper Functions
///////////////////////////////////////////////////////

function _doSearch($content, $searchTerm, $searchPhrase) {

	$searchRegex = array(
			'#<script[^>]*>.*?</script>#si',
			'#<style[^>]*>.*?</style>#si',
			'#<!.*?(--|]])>#si',
			'#<[^>]*>#i'
			);

	// Mot(s) à rechercher
	switch ($searchPhrase) {
		case 'exact':
			$terms = $searchTerm;
			break;
		case 'all':
		case 'any':
		default:
			$terms = explode(' ', $searchTerm);
			break;
	}

	// On supprime le html du contenu
	foreach($searchRegex as $regex) {
		$text = preg_replace($regex, '', $content);
	}
	
	// Recherche
	switch ($searchPhrase) {
		case 'exact':
			$return = _boolStristr($text, $terms);
			break;
		case 'any':
			$return = false;
			foreach($terms as $term) {
				if(_boolStristr($text, $term)) {
					$return = true;
					break;
				}
			}
			break;
		case 'all':
		default:
			$return = true;
			foreach($terms as $term) {
				if(!_boolStristr($text, $term)) {
					$return = false;
					break;
				}
			}
			break;
	}
	
	return $return;
}

function _boolStristr($text, $term) {

	if(JString::stristr($text, $term) == false) {
		return false;
	} else {
		return true;
	}

}
