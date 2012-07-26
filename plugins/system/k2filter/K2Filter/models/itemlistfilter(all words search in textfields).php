<?php
/**
 * @version		$Id: itemlist.php 1379 2011-12-02 16:17:56Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2011 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

///change all Itemlist to Itemlistfilter
class K2ModelItemlistfilter extends JModel {

		function getData($ordering = NULL) {

				$user = &JFactory::getUser();
				$aid = $user->get('aid');
				$db = &JFactory::getDBO();
				$params = &K2HelperUtilities::getParams('com_k2');
				$limitstart = JRequest::getInt('limitstart');
				$limit = JRequest::getInt('limit');
				$task = JRequest::getCmd('task');
				if($task=='search' && $params->get('googleSearch'))
					return array();

				$jnow = &JFactory::getDate();
				$now = $jnow->toMySQL();
				$nullDate = $db->getNullDate();

				if (JRequest::getWord('format') == 'feed')
						$limit = $params->get('feedLimit');

				$query = "SELECT i.*, c.name as categoryname,c.id as categoryid, c.alias as categoryalias, c.params as categoryparams";

				$query .= ", (r.rating_sum/r.rating_count) AS rating";

				$query.=" FROM #__k2_items as i LEFT JOIN #__k2_categories AS c ON c.id = i.catid";

				$query .= " LEFT JOIN #__k2_rating r ON r.itemID = i.id";

				if($task=='user' && !$user->guest && $user->id==JRequest::getInt('id')){
					$query .= " WHERE ";
				}
				else {
					 $query .= " WHERE i.published = 1 AND ";
				}

				if(K2_JVERSION=='16'){

					$query .= "i.access IN(".implode(',', $user->authorisedLevels()).")"
					." AND i.trash = 0"
					." AND c.published = 1"
					." AND c.access IN(".implode(',', $user->authorisedLevels()).")"
					." AND c.trash = 0";
										
					$mainframe = &JFactory::getApplication();
					$languageFilter = $mainframe->getLanguageFilter();
					if($languageFilter) {
						$languageTag = JFactory::getLanguage()->getTag();
						$query .= " AND c.language IN (".$db->quote($languageTag).",".$db->quote('*').") 
						AND i.language IN (".$db->quote($languageTag).",".$db->quote('*').")" ;
					}
				}
				else {
	 				$query .= "i.access <= {$aid}"
					." AND i.trash = 0"
					." AND c.published = 1"
					." AND c.access <= {$aid}"
					." AND c.trash = 0"; 				
				}

				// ADDED K2FSM from here ->>
				if (JRequest::getVar('restrict') == 1) {
					if (JRequest::getVar('restmode') == 0 && JRequest::getVar('restcat') != '') {
						$restcat = JRequest::getVar('restcat');
						$restcat = str_replace(" ", "", $restcat);
						$restcat = explode(",", $restcat);
						
						$restsub = JRequest::getVar('restsub', 1);
						
						if($restsub == 1) {
							$query .= " AND ( ";
							foreach($restcat as $kr => $restcatid) {
								$restsubs = K2ModelItemListFilter::getCategoryTree($restcatid);
								foreach($restsubs as $k => $rests) {
									$query .= "i.catid = " . $rests;
									if($k+1 < sizeof($restsubs))
										$query .= " OR ";
								}
								if($kr+1 < sizeof($restcat))
									$query .= " OR ";			
							}
							$query .= " )";
						}
						
						else {
							$query .= " AND ( ";
							foreach($restcat as $kr => $restcatid) {
								$query .= "i.catid = " . $restcatid;
								if($kr+1 < sizeof($restcat))
									$query .= " OR ";			
							}
							$query .= " )";
						}
					}	
					
					else if (JRequest::getVar('restmode') == 1 && JRequest::getVar("restcata") != "") {
						$restcata = JRequest::getVar('restcata');
						$restsub = JRequest::getVar('restsub', 1);
						
						if($restsub == 1) {
							$query .= " AND ( ";
							$restsubs = K2ModelItemListFilter::getCategoryTree($restcata);
							foreach($restsubs as $k => $rests) {
								$query .= "i.catid = " . $rests;
								if($k+1 < sizeof($restsubs))
									$query .= " OR ";
							}
							$query .= " )";
						}
						
						else 
							$query .= " AND i.catid = " . $restcata;
					}
				}
				
				if (JRequest::getVar('category')) {
					$catid = JRequest::getVar('category');
					if(!is_array($catid)) {
						$query .= " AND ( ";
						$restsubs = K2ModelItemListFilter::getCategoryTree($catid);
						foreach($restsubs as $k => $rests) {
							$query .= "i.catid = " . $rests;
							if($k+1 < sizeof($restsubs))
								$query .= " OR ";
						}
						$query .= " )";
					}
					else {
						$catid = implode(",", $catid);
						$query .= " AND i.catid IN (" . $catid . ")";
					}
				}
				// <<- ADDED K2FSM till here
				
				if( !($task=='user' && !$user->guest && $user->id==JRequest::getInt('id') )) {
					$query .= " AND ( i.publish_up = ".$db->Quote($nullDate)." OR i.publish_up <= ".$db->Quote($now)." )";
					$query .= " AND ( i.publish_down = ".$db->Quote($nullDate)." OR i.publish_down >= ".$db->Quote($now)." )";
				}

				//Build query depending on task
				switch ($task) {
								
						// ADDED k2FSM from here ->>
						case 'filter':
                        
                        $slider_count = 0;
                        
						for($i=1; $i<255; $i++){
						
								$badchars = array('#', '>', '<', '\\');
										
										
								$search = JRequest::getVar('searchword'.$i, null);
								if (! empty($search)) {
										
												$mydb = &JFactory::getDBO();
												$myquery = "SELECT * FROM #__k2_extra_fields WHERE id = $i";
												$mydb->setQuery($myquery);
												$myresults = $mydb->LoadObjectList();
												foreach($myresults as $myresult) {
														 require_once (JPATH_SITE.DS.'modules'.DS.'mod_k2_filter'.DS.'helper.php');
														 $myfields = (modK2FilterHelper::extractExtraFields(modK2FilterHelper::pull($i,'')));
												}
										
												$sql = K2ModelItemListFilter::prepareFilterArray($search, $badchars, 0, 0, 0, $myfields, $i, 0);

											if (! empty($sql)) {
												$query .= $sql;
											} else {
												$rows = array();
												return $rows;
											}
								}
								
								$search_from = JRequest::getVar('searchword'.$i.'-from', null);
								$search_to = JRequest::getVar('searchword'.$i.'-to', null);
								
								if (!empty($search_to)) {
							
									$mydb = &JFactory::getDBO();
									$myquery = "SELECT * FROM #__k2_items";
									$mydb->setQuery($myquery);
									$items = $mydb->LoadObjectList();
									
									foreach($items as $item) {
										$extra = $item->extra_fields;
										
										//preg_match("/\"{$i}\",\"value\":\"(\d+|\d.+)\"/", $extra, $match);
										//$value = (float)$match[1];
										require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'lib'.DS.'JSON.php');
										$json = new Services_JSON;
										$extrafields = $json->decode($extra);
										
										if(!empty($extrafields)) {
											foreach($extrafields as $extrafield) {
												if($extrafield->id == $i) {
													$value = $extrafield->value;
													break;
												}
												else {
													$value = '';
												}
											}
										}
										else continue;
										
										if(!empty($search_from)) {
											if($value >= $search_from && $value <= $search_to) {
												$myids[] = $item->id;
											}
										}
										else {
											if($value <= $search_to && $value != '') {
												$myids[] = $item->id;
											}
										}
									}
									if($myids) {
										$query .= " AND i.id IN(".implode(',', $myids).")";	
									}
									else {
										$query .= " AND i.id = 0";
									}
									
									unset($myids);
								}
                                
                               $search = JRequest::getVar('array'.$i, null);
                               if (! empty($search)) {
                                        $count = sizeof($search);
                                        $mydb = &JFactory::getDBO();
                                        $myquery = "SELECT * FROM #__k2_extra_fields WHERE id = $i";
                                        $mydb->setQuery($myquery);
                                        $myresults = $mydb->LoadObjectList();
                                        foreach($myresults as $myresult) {
                                                 require_once (JPATH_SITE.DS.'modules'.DS.'mod_k2_filter'.DS.'helper.php');
                                                 $myfields = (modK2FilterHelper::extractExtraFields(modK2FilterHelper::pull($i,'')));
                                                 }
                                        
                                        $sql = K2ModelItemListFilter::prepareFilterArray($search, $badchars, 0, 1, $count, $myfields, $i, 0);
                                        
                                        if (! empty($sql)) {
                                                $query .= $sql;
                                        } else {
                                                $rows = array();
                                                return $rows;
                                        }
                                }
                                
                                $slider_search = JRequest::getVar('slider'.$i, null);
                                if (!empty($slider_search)) {
                                        
                                        $mydb = &JFactory::getDBO();
                                        $myquery = "SELECT * FROM #__k2_extra_fields WHERE id = $i";
                                        $mydb->setQuery($myquery);
                                        $myresults = $mydb->LoadObjectList();
                                        
										foreach($myresults as $myresult) {
                                            require_once (JPATH_SITE.DS.'modules'.DS.'mod_k2_filter'.DS.'helper.php');
                                            $myfields = (modK2FilterHelper::extractExtraFields(modK2FilterHelper::pull($i,'')));
                                        }
										
										$range = 0;                                            
                                        $sql = K2ModelItemListFilter::prepareFilterArray($slider_search, $badchars, 1, 0, 0, $myfields, $i, $range);
                                        if (! empty($sql)) {
                                                $query .= $sql;
                                        } else {
                                                $rows = array();
                                                return $rows;
                                        }
                                }
								
								$slider_range = JRequest::getVar('slider_range'.$i, null);
                                if (!empty($slider_range)) {
                                        
                                        $mydb = &JFactory::getDBO();
                                        $myquery = "SELECT * FROM #__k2_extra_fields WHERE id = $i";
                                        $mydb->setQuery($myquery);
                                        $myresults = $mydb->LoadObjectList();
                                        
										foreach($myresults as $myresult) {
                                            require_once (JPATH_SITE.DS.'modules'.DS.'mod_k2_filter'.DS.'helper.php');
                                            $myfields = (modK2FilterHelper::extractExtraFields(modK2FilterHelper::pull($i,'')));
                                        }
                                        
										$range = 1;
                                        $sql = K2ModelItemListFilter::prepareFilterArray($slider_range, $badchars, 1, 0, 0, $myfields, $i, $range);
                                        if (! empty($sql)) {
                                                $query .= $sql;
                                        } else {
                                                $rows = array();
                                                return $rows;
                                        }
                                }
                                
                          }
						  
								///searchable labels
								$flabel = JRequest::getString('flabel');
								if(!empty($flabel)) {
									$query .= K2ModelItemListFilter::prepareSearch($flabel);
								}
						  
								///tag filter
								$tag = JRequest::getString('ftag');
								
								if(!empty($tag)) {
								
									jimport('joomla.filesystem.file');
									if (JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'joomfish.php')) {

											$registry = &JFactory::getConfig();
											$lang = $registry->getValue("config.jflang");

											$sql = " SELECT reference_id FROM #__jf_content as jfc LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.id";
											$sql .= " WHERE jfc.value = ".$db->Quote($tag);
											$sql .= " AND jfc.reference_table = 'k2_tags'";
											$sql .= " AND jfc.reference_field = 'name' AND jfc.published=1";

											$db->setQuery($sql, 0, 1);
											$result = $db->loadResult();

									}
									
									if (!isset($result) || $result < 1) {
											$sql = "SELECT id FROM #__k2_tags WHERE name=".$db->Quote($tag);
											$db->setQuery($sql, 0, 1);
											$result = $db->loadResult();
									}
									
									$query.=" AND i.id IN (SELECT itemID FROM #__k2_tags_xref WHERE tagID=".(int)$result.")";
								
								}
								
								///multi tag filter
								$taga = JRequest::getVar('taga');

								if(!empty($taga)) {
								
									jimport('joomla.filesystem.file');
									if (JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'joomfish.php')) {

											$registry = &JFactory::getConfig();
											$lang = $registry->getValue("config.jflang");

											$sql = " SELECT reference_id FROM #__jf_content as jfc LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.id";
											
											$sql .= " WHERE (";
												foreach($taga as $k=>$tag) {
													$sql .= "jfc.value = ".$db->Quote($tag);
													if(($k+1) != count($taga))
														$sql .= " OR ";
												}
											$sql .= ")";
											
											$sql .= " AND jfc.reference_table = 'k2_tags'";
											$sql .= " AND jfc.reference_field = 'name' AND jfc.published=1";

											$db->setQuery($sql);
											$results = $db->loadResultArray();
									}
									
									if (!isset($results) || $results < 1 || count($results) < 1) {
											$sql = "SELECT id FROM #__k2_tags WHERE (";
												foreach($taga as $k=>$tag) {
													$sql .= "name=".$db->Quote($tag);
													if(($k+1) != count($taga))
														$sql .= " OR ";
												}
											$sql .= ")";
											$db->setQuery($sql);
											$results = $db->loadResultArray();
									}
									
									$query .= " AND i.id IN (SELECT itemID FROM #__k2_tags_xref WHERE (";
									foreach($results as $k=>$result) {
										$query .= "tagID = ".(int)$result;
										if(($k+1) != count($results))
											$query .= " OR ";
									}
									$query .= "))";
								
								}
								
								///title filter
								$title = JRequest::getString('ftitle');
								
								if(!empty($title)) {
								
									$titles = explode(' ', JString::strtolower($title));
														
									$query .= " AND (";
									foreach ($titles as $k=>$title) {
										if (JString::strlen($title) > 3) {
											if($k == 0) {
												$query .= "(i.title REGEXP '^.*{$title}.*$')";
											}
											else {
												$query .= " OR (i.title REGEXP '^.*{$title}.*$')";
											}
										}
									}
									$query .= ")";
									
								}
								
								///item text
								$ftext = JRequest::getString('ftext');
								
								if(!empty($ftext)) {
								
									$ftexts = explode(' ', JString::strtolower($ftext));
														
									$query .= " AND (";
									foreach ($ftexts as $k=>$ftext) {
										if (JString::strlen($ftext) > 3) {
											if($k == 0) {
												$query .= "(i.introtext REGEXP '^.*{$ftext}.*$' OR i.fulltext REGEXP '^.*{$ftext}.*$')";
											}
											else {
												$query .= " OR (i.introtext REGEXP '^.*{$ftext}.*$' OR i.fulltext REGEXP '^.*{$ftext}.*$')";
											}
										}
									}
									$query .= ")";
								}								
								
								///title A-Z filter
								$title_az = JRequest::getString('ftitle_az');
								
								if(!empty($title_az)) {
									$query .= " AND i.title REGEXP '^{$title_az}.*$'";
								}
								
								/// creation date
								$created = JRequest::getString('created');
								
								if(!empty($created)) {
									$query .= " AND i.created REGEXP '^{$created}.*$'";
								}	

								$thour = date("H:i:s");
								
								/// creation date range
								$created_from = JRequest::getString('created-from');
								$created_to = JRequest::getString('created-to');
								
								if(!empty($created_from) && !empty($created_to)) {
									$query .= " AND i.created >= '{$created_from}' AND i.created <= '{$created_to} {$thour}'";
								}								
								
								/// publish up date
								$publish_up = JRequest::getString('publish_up');
								
								if(!empty($publish_up)) {
									$query .= " AND i.publish_up REGEXP '^{$publish_up}.*$'";
								}
								
								/// publish up date range
								$publish_up_from = JRequest::getString('publish-up-from');
								$publish_up_to = JRequest::getString('publish-up-to');
								
								if(!empty($publish_up_from) && !empty($publish_up_to)) {
									$query .= " AND i.publish_up >= '{$publish_up_from}' AND i.publish_up <= '{$publish_up_to} {$thour}'";
								}
								
								/// publish down date
								$publish_down = JRequest::getString('publish_down');
								
								if(!empty($publish_down)) {
									$query .= " AND i.publish_down REGEXP '^{$publish_down}.*$'";
								}
								
								/// publish down date range
								$publish_down_from = JRequest::getString('publish-down-from');
								$publish_down_to = JRequest::getString('publish-down-to');
								
								if(!empty($publish_down_from) && !empty($publish_down_to)) {
									$query .= " AND i.publish_down >= '{$publish_down_from}' AND i.publish_down <= '{$publish_down_to} {$thour}'";
								}
										
							break;
						// <<- ADDED K2FSM till here

						default:
								$searchIDs = $params->get('categories');

								if (is_array($searchIDs) && count($searchIDs)) {

										if ($params->get('catCatalogMode')) {
												$sql = @implode(',', $searchIDs);
												$query .= " AND i.catid IN ({$sql})";
										} else {
												
												$result = K2ModelItemListFilter::getCategoryTree($searchIDs);
												if (count($result)) {
														$sql = @implode(',', $result);
														$query .= " AND i.catid IN ({$sql})";
												}
										}
								}

								break;
				}

				//Set featured flag
				if ($task == 'category' || empty($task)) {
						if (JRequest::getInt('featured') == '0') {
								$query .= " AND i.featured != 1";
						} else if (JRequest::getInt('featured') == '2') {
								$query .= " AND i.featured = 1";
						}
				}

				
			// ADDED k2FSM from here ->>

				//Set ordering
				$order = JRequest::getVar("orderby", '');
				if($order == '') {
					$order = JRequest::getVar("ordering_default", '');
				}
				$order_method = JRequest::getVar("orderto", '');
				
				switch ($order) {

						case 'date':
								$orderby = 'i.created';
								$orderby .= ' '.$order_method;
								break;

						case 'alpha':
								$orderby = 'i.title';
								$orderby .= ' '.$order_method;
								break;

						case 'order':
								if (JRequest::getInt('featured') == '2') {
									$orderby = 'i.featured_ordering';
									$orderby .= ' '.$order_method;
								}
								else {
									$orderby = 'c.ordering, i.ordering';
									$orderby .= ' '.$order_method;
								}
								break;
								
						case 'featured':
								$orderby = 'i.featured '.$order_method.', i.created '.$order_method;
								break;

						case 'hits':
								$orderby = 'i.hits';
								$orderby .= ' '.$order_method;
								break;

						case 'rand':
								$orderby = 'RAND()';
								break;

						case 'best':
								$orderby = 'rating';
								$orderby .= ' '.$order_method;
								break;
								
						case 'publishUp':
								$orderby = 'i.publish_up';
								$orderby .= ' '.$order_method;
								break;

						case 'id':		
						default:
								$orderby = 'i.id';
								$orderby .= ' '.$order_method;
								break;
				}	

			$query .= " ORDER BY ".$orderby;
			
			$order = (int)$order;
			if($order == 0) {
				$db->setQuery($query, $limitstart, $limit);
			}
			else {
				$db->setQuery($query);
			}
			$rows = $db->loadObjectList();
				
			if(!empty($rows)) {
				
				//Order by extrafield
				$order = JRequest::getVar("orderby", '');
				if($order == '') {
					$order = JRequest::getVar("ordering_default", '');
				}
				$order_method = JRequest::getVar("orderto", '');	

				$order = (int)$order;
				
				if($order != 0) {
					foreach($rows as $key=>$item) {
							
						$extras = $item->extra_fields;
						$extras = json_decode($extras);

						foreach($extras as $extra) {
							if($extra->id == $order) {
								$extraval = $extra->value;
							}
						}
									
						$extrasort[$key] = Array();
						$extrasort[$key][0] = $item;
						$extrasort[$key][1] = $extraval;
					}
					
					if($order_method == "asc") {
						usort($extrasort, array('K2ModelItemlistfilter','compareasc'));
					}
					else {
						usort($extrasort, array('K2ModelItemlistfilter','comparedesc'));
					}
																
					$rows = Array();
					$total = $limit + $limitstart;

					for($i=$limitstart; $i<$total; $i++) {
						if(!empty($extrasort[$i][0])) {
							$rows[] = $extrasort[$i][0];
						}
					}
				}

			}
			
			return $rows;
				
			// <<- ADDED k2FSM till here
		}

		function getTotal() {

				$user = &JFactory::getUser();
				$aid = $user->get('aid');
				$db = &JFactory::getDBO();
				$params = &K2HelperUtilities::getParams('com_k2');
				$task = JRequest::getCmd('task');

				if($task=='search' && $params->get('googleSearch'))
					return 0;

				$jnow = &JFactory::getDate();
				$now = $jnow->toMySQL();
				$nullDate = $db->getNullDate();

				$query = "SELECT COUNT(*) FROM #__k2_items as i LEFT JOIN #__k2_categories c ON c.id = i.catid";

				if ($task == 'tag')
						$query .= " LEFT JOIN #__k2_tags_xref tags_xref ON tags_xref.itemID = i.id LEFT JOIN #__k2_tags tags ON tags.id = tags_xref.tagID";

				if($task=='user' && !$user->guest && $user->id==JRequest::getInt('id')){
					$query .= " WHERE ";
				}
				else {
					 $query .= " WHERE i.published = 1 AND ";
				}
				
				if(K2_JVERSION=='16'){
					$query .= "i.access IN(".implode(',', $user->authorisedLevels()).")"
					." AND i.trash = 0"
					." AND c.published = 1"
					." AND c.access IN(".implode(',', $user->authorisedLevels()).")"
					." AND c.trash = 0";
					
					$mainframe = &JFactory::getApplication();
					$languageFilter = $mainframe->getLanguageFilter();
					if($languageFilter) {
						$languageTag = JFactory::getLanguage()->getTag();
						$query .= " AND c.language IN (".$db->quote($languageTag).",".$db->quote('*').") 
						AND i.language IN (".$db->quote($languageTag).",".$db->quote('*').")" ;
					}
				}
				else {
					$query .= "i.access <= {$aid}"
					." AND i.trash = 0"
					." AND c.published = 1"
					." AND c.access <= {$aid}"
					." AND c.trash = 0";
				}
				
				// ADDED K2FSM from here ->>
				if (JRequest::getVar('restrict') == 1) {
					if (JRequest::getVar('restmode') == 0 && JRequest::getVar('restcat') != '') {
						$restcat = JRequest::getVar('restcat');
						$restcat = str_replace(" ", "", $restcat);
						$restcat = explode(",", $restcat);
						
						$restsub = JRequest::getVar('restsub', 1);
						
						if($restsub == 1) {
							$query .= " AND ( ";
							foreach($restcat as $kr => $restcatid) {
								$restsubs = K2ModelItemListFilter::getCategoryTree($restcatid);
								foreach($restsubs as $k => $rests) {
									$query .= "i.catid = " . $rests;
									if($k+1 < sizeof($restsubs))
										$query .= " OR ";
								}
								if($kr+1 < sizeof($restcat))
									$query .= " OR ";			
							}
							$query .= " )";
						}
						
						else {
							$query .= " AND ( ";
							foreach($restcat as $kr => $restcatid) {
								$query .= "i.catid = " . $restcatid;
								if($kr+1 < sizeof($restcat))
									$query .= " OR ";			
							}
							$query .= " )";
						}
					}	
					
					else if (JRequest::getVar('restmode') == 1 && JRequest::getVar("restcata") != "") {
						$restcata = JRequest::getVar('restcata');
						$restsub = JRequest::getVar('restsub', 1);
						
						if($restsub == 1) {
							$query .= " AND ( ";
							$restsubs = K2ModelItemListFilter::getCategoryTree($restcata);
							foreach($restsubs as $k => $rests) {
								$query .= "i.catid = " . $rests;
								if($k+1 < sizeof($restsubs))
									$query .= " OR ";
							}
							$query .= " )";
						}
						
						else 
							$query .= " AND i.catid = " . $restcata;
					}
				}
				
				if (JRequest::getVar('category')) {
					$catid = JRequest::getVar('category');
					if(!is_array($catid)) {
						$query .= " AND ( ";
						$restsubs = K2ModelItemListFilter::getCategoryTree($catid);
						foreach($restsubs as $k => $rests) {
							$query .= "i.catid = " . $rests;
							if($k+1 < sizeof($restsubs))
								$query .= " OR ";
						}
						$query .= " )";
					}
					else {
						$catid = implode(",", $catid);
						$query .= " AND i.catid IN (" . $catid . ")";
					}
				}
				// <<- ADDED K2FSM till here
				
				$query .= " AND ( i.publish_up = ".$db->Quote($nullDate)." OR i.publish_up <= ".$db->Quote($now)." )";
				$query .= " AND ( i.publish_down = ".$db->Quote($nullDate)." OR i.publish_down >= ".$db->Quote($now)." )";

				//Build query depending on task
				switch ($task) {
								
						// ADDED k2FSM from here ->>
						case 'filter':
                        
                        $slider_count = 0;
                        for($i=1; $i<255; $i++){
						$badchars = array('#', '>', '<', '\\');
                                
                                
                        $search = JRequest::getVar('searchword'.$i, null);
						if (! empty($search)) {
                                
                                        $mydb = &JFactory::getDBO();
                                        $myquery = "SELECT * FROM #__k2_extra_fields WHERE id = $i";
                                        $mydb->setQuery($myquery);
                                        $myresults = $mydb->LoadObjectList();
                                        foreach($myresults as $myresult) {
                                                 require_once (JPATH_SITE.DS.'modules'.DS.'mod_k2_filter'.DS.'helper.php');
                                                 $myfields = (modK2FilterHelper::extractExtraFields(modK2FilterHelper::pull($i,'')));
                                        }
                                
										$sql = K2ModelItemListFilter::prepareFilterArray($search, $badchars, 0, 0, 0, $myfields, $i, 0);

										if (! empty($sql)) {
											$query .= $sql;
										} else {
											$rows = 0;
											return $rows;
										}
						}
						
						
								$search_from = JRequest::getVar('searchword'.$i.'-from', null);
								$search_to = JRequest::getVar('searchword'.$i.'-to', null);
								
								if (!empty($search_to)) {
							
									$mydb = &JFactory::getDBO();
									$myquery = "SELECT * FROM #__k2_items";
									$mydb->setQuery($myquery);
									$items = $mydb->LoadObjectList();
									
									foreach($items as $item) {
										$extra = $item->extra_fields;
										
										//preg_match("/\"{$i}\",\"value\":\"(\d+|\d.+)\"/", $extra, $match);
										//$value = (float)$match[1];
										require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'lib'.DS.'JSON.php');
										$json = new Services_JSON;
										$extrafields = $json->decode($extra);
										
										if(!empty($extrafields)) {
											foreach($extrafields as $extrafield) {
												if($extrafield->id == $i) {
													$value = $extrafield->value;
													break;
												}
												else {
													$value = '';
												}
											}
										}
										else continue;
										
										if(!empty($search_from)) {
											if($value >= $search_from && $value <= $search_to) {
												$myids[] = $item->id;
											}
										}
										else {
											if($value <= $search_to && $value != '') {
												$myids[] = $item->id;
											}
										}
									}
									if($myids) {
										$query .= " AND i.id IN(".implode(',', $myids).")";	
									}
									else {
										$query .= " AND i.id = 0";
									}
									
									unset($myids);
								}
                                
                               $search = JRequest::getVar('array'.$i, null);
                               if (! empty($search)) {
                                        $count = sizeof($search);
                                        $mydb = &JFactory::getDBO();
                                        $myquery = "SELECT * FROM #__k2_extra_fields WHERE id = $i";
                                        $mydb->setQuery($myquery);
                                        $myresults = $mydb->LoadObjectList();
                                        foreach($myresults as $myresult) {
                                                 require_once (JPATH_SITE.DS.'modules'.DS.'mod_k2_filter'.DS.'helper.php');
                                                 $myfields = (modK2FilterHelper::extractExtraFields(modK2FilterHelper::pull($i,'')));
                                                 }
                                        
                                        $sql = K2ModelItemListFilter::prepareFilterArray($search, $badchars, 0, 1, $count, $myfields, $i, 0);
                                        
                                        if (! empty($sql)) {
                                                $query .= $sql;
                                        } else {
                                                $rows = array();
                                                return $rows;
                                        }
                                }
                                
                                $slider_search = JRequest::getVar('slider'.$i, null);
                                if (! empty($slider_search)) {
                                        $sl_count = 0;
                                        for($n=0; $n<255; $n++) {
                                                $slider_search_s = JRequest::getVar('slider'.$n, null);
                                                if(! empty($slider_search_s) && $sl_count == 0) { 
                                                        $slsearch_string = "% ".$slider_search_s." ";
                                                        $sl_count = 1;
                                                }
                                                else if(! empty($slider_search_s)) $slsearch_string .= "% ".$slider_search_s." %";
                                        }
                                        
                                        $mydb = &JFactory::getDBO();
                                        $myquery = "SELECT * FROM #__k2_extra_fields WHERE id = $i";
                                        $mydb->setQuery($myquery);
                                        $myresults = $mydb->LoadObjectList();
                                        foreach($myresults as $myresult) {
                                                 require_once (JPATH_SITE.DS.'modules'.DS.'mod_k2_filter'.DS.'helper.php');
                                                 $myfields = (modK2FilterHelper::extractExtraFields(modK2FilterHelper::pull($i,'')));
                                                 }
                                                 
                                        $sql = K2ModelItemListFilter::prepareFilterArray($slider_search, $badchars, 1, 0, 0, $myfields, $i, 0);
                                        if (! empty($sql)) {
                                                $query .= $sql;
                                        } else {
                                                $rows = array();
                                                return $rows;
                                        }
                                }
								
								$slider_range = JRequest::getVar('slider_range'.$i, null);
                                if (! empty($slider_range)) {
                                        
                                        $mydb = &JFactory::getDBO();
                                        $myquery = "SELECT * FROM #__k2_extra_fields WHERE id = $i";
                                        $mydb->setQuery($myquery);
                                        $myresults = $mydb->LoadObjectList();
                                        
										foreach($myresults as $myresult) {
                                            require_once (JPATH_SITE.DS.'modules'.DS.'mod_k2_filter'.DS.'helper.php');
                                            $myfields = (modK2FilterHelper::extractExtraFields(modK2FilterHelper::pull($i,'')));
                                        }
                                        
										$range = 1;
                                        $sql = K2ModelItemListFilter::prepareFilterArray($slider_range, $badchars, 1, 0, 0, $myfields, $i, $range);
                                        if (! empty($sql)) {
                                                $query .= $sql;
                                        } else {
                                                $rows = array();
                                                return $rows;
                                        }
                                }
                                
                          }
						  
						  
								///searchable labels
								$flabel = JRequest::getString('flabel');
								if(!empty($flabel)) {
									$query .= K2ModelItemListFilter::prepareSearch($flabel);
								}
							
								///tag filter
								$tag = JRequest::getString('ftag');
								
								if(!empty($tag)) {
								
									jimport('joomla.filesystem.file');
									if (JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'joomfish.php')) {

											$registry = &JFactory::getConfig();
											$lang = $registry->getValue("config.jflang");

											$sql = " SELECT reference_id FROM #__jf_content as jfc LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.id";
											$sql .= " WHERE jfc.value = ".$db->Quote($tag);
											$sql .= " AND jfc.reference_table = 'k2_tags'";
											$sql .= " AND jfc.reference_field = 'name' AND jfc.published=1";

											$db->setQuery($sql, 0, 1);
											$result = $db->loadResult();

									}
									
									if (!isset($result) || $result < 1) {
											$sql = "SELECT id FROM #__k2_tags WHERE name=".$db->Quote($tag);
											$db->setQuery($sql, 0, 1);
											$result = $db->loadResult();
									}
									
									$query.=" AND i.id IN (SELECT itemID FROM #__k2_tags_xref WHERE tagID=".(int)$result.")";
								
								}
								
								///multi tag filter
								$taga = JRequest::getVar('taga');

								if(!empty($taga)) {
								
									jimport('joomla.filesystem.file');
									if (JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'joomfish.php')) {

											$registry = &JFactory::getConfig();
											$lang = $registry->getValue("config.jflang");

											$sql = " SELECT reference_id FROM #__jf_content as jfc LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.id";
											
											$sql .= " WHERE (";
												foreach($taga as $k=>$tag) {
													$sql .= "jfc.value = ".$db->Quote($tag);
													if(($k+1) != count($taga))
														$sql .= " OR ";
												}
											$sql .= ")";
											
											$sql .= " AND jfc.reference_table = 'k2_tags'";
											$sql .= " AND jfc.reference_field = 'name' AND jfc.published=1";

											$db->setQuery($sql);
											$results = $db->loadResultArray();
									}
									
									if (!isset($results) || $results < 1 || count($results) < 1) {
											$sql = "SELECT id FROM #__k2_tags WHERE (";
												foreach($taga as $k=>$tag) {
													$sql .= "name=".$db->Quote($tag);
													if(($k+1) != count($taga))
														$sql .= " OR ";
												}
											$sql .= ")";
											$db->setQuery($sql);
											$results = $db->loadResultArray();
									}
									
									$query .= " AND i.id IN (SELECT itemID FROM #__k2_tags_xref WHERE (";
									foreach($results as $k=>$result) {
										$query .= "tagID = ".(int)$result;
										if(($k+1) != count($results))
											$query .= " OR ";
									}
									$query .= "))";
								
								}
								
								///title filter
								$title = JRequest::getString('ftitle');
								
								if(!empty($title)) {
								
									$titles = explode(' ', JString::strtolower($title));
														
									$query .= " AND (";
									foreach ($titles as $k=>$title) {
										if (JString::strlen($title) > 3) {
											if($k == 0) {
												$query .= "(i.title REGEXP '^.*{$title}.*$')";
											}
											else {
												$query .= " OR (i.title REGEXP '^.*{$title}.*$')";
											}
										}
									}
									$query .= ")";
									
								}
								
								///item text
								$ftext = JRequest::getString('ftext');
								
								if(!empty($ftext)) {
								
									$ftexts = explode(' ', JString::strtolower($ftext));
														
									$query .= " AND (";
									foreach ($ftexts as $k=>$ftext) {
										if (JString::strlen($ftext) > 3) {
											if($k == 0) {
												$query .= "(i.introtext REGEXP '^.*{$ftext}.*$' OR i.fulltext REGEXP '^.*{$ftext}.*$')";
											}
											else {
												$query .= " OR (i.introtext REGEXP '^.*{$ftext}.*$' OR i.fulltext REGEXP '^.*{$ftext}.*$')";
											}
										}
									}
									$query .= ")";
								}	
								
								///title A-Z filter
								$title_az = JRequest::getString('ftitle_az');
								
								if(!empty($title_az)) {
									$query .= " AND i.title REGEXP '^{$title_az}.*$'";
								}
								
								/// creation date
								$created = JRequest::getString('created');
								
								if(!empty($created)) {
									$query .= " AND i.created REGEXP '^{$created}.*$'";
								}								

								$thour = date("H:i:s");
								
								/// creation date range
								$created_from = JRequest::getString('created-from');
								$created_to = JRequest::getString('created-to');
								
								if(!empty($created_from) && !empty($created_to)) {
									$query .= " AND i.created >= '{$created_from}' AND i.created <= '{$created_to} {$thour}'";
								}								
								
								/// publish up date
								$publish_up = JRequest::getString('publish_up');
								
								if(!empty($publish_up)) {
									$query .= " AND i.publish_up REGEXP '^{$publish_up}.*$'";
								}
								
								/// publish up date range
								$publish_up_from = JRequest::getString('publish-up-from');
								$publish_up_to = JRequest::getString('publish-up-to');
								
								if(!empty($publish_up_from) && !empty($publish_up_to)) {
									$query .= " AND i.publish_up >= '{$publish_up_from}' AND i.publish_up <= '{$publish_up_to} {$thour}'";
								}
								
								/// publish down date
								$publish_down = JRequest::getString('publish_down');
								
								if(!empty($publish_down)) {
									$query .= " AND i.publish_down REGEXP '^{$publish_down}.*$'";
								}
								
								/// publish down date range
								$publish_down_from = JRequest::getString('publish-down-from');
								$publish_down_to = JRequest::getString('publish-down-to');
								
								if(!empty($publish_down_from) && !empty($publish_down_to)) {
									$query .= " AND i.publish_down >= '{$publish_down_from}' AND i.publish_down <= '{$publish_down_to} {$thour}'";
								}

							break;
						// <<- ADDED K2FSM till here

						default:
								$searchIDs = $params->get('categories');

								if (is_array($searchIDs) && count($searchIDs)) {

										if ($params->get('catCatalogMode')) {
												$sql = @implode(',', $searchIDs);
												$query .= " AND i.catid IN ({$sql})";
										} else {
 											$result = K2ModelItemListFilter::getCategoryTree($searchIDs);
												if (count($result)) {
														$sql = @implode(',', $result);
														$query .= " AND i.catid IN ({$sql})";
												}
										}
								}

								break;
				}

				//Set featured flag
				if ($task == 'category' || empty($task)) {
						if (JRequest::getVar('featured') == '0') {
								$query .= " AND i.featured != 1";
						} else if (JRequest::getVar('featured') == '2') {
								$query .= " AND i.featured = 1";
						}
				}
				$db->setQuery($query);
				$result = $db->loadResult();
				return $result;
		}
		
	
		function prepareSearch($search) {

			jimport('joomla.filesystem.file');
			$db = &JFactory::getDBO();
			$language = &JFactory::getLanguage();
			$defaultLang = $language->getDefault();
			$currentLang = $language->getTag();
			$length = JString::strlen($search);
			$sql = '';
			
			if(JRequest::getVar('categories')){
				$categories = @explode(',', JRequest::getVar('categories'));
				JArrayHelper::toInteger($categories);
				$sql.= " AND i.catid IN (".@implode(',', $categories).") ";
			}
			
			if(empty($search)) {
				return $sql;
			}

			if (JString::substr($search, 0, 1) == '"' && JString::substr($search, $length - 1, 1) == '"') {
				$type = 'exact';
			}
			else {
				$type='any';
			}

			if (JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'joomfish.php') && $currentLang != $defaultLang) {

				$conditions = array();
				$search_ignore = array();
				

				$ignoreFile = $language->getLanguagePath().DS.$currentLang.DS.$currentLang.'.ignore.php';

				if (JFile::exists($ignoreFile)) {
					include $ignoreFile;
				}

				if ($type=='exact') {

					$word = JString::substr($search, 1, $length - 2);

					if (JString::strlen($word) > 3 && !in_array($word, $search_ignore)) {
						$word = $db->Quote('%'.$db->getEscaped($word, true).'%', false);



						$jfQuery = " SELECT reference_id FROM #__jf_content as jfc LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.".K2_JF_ID;
						$jfQuery .= " WHERE jfc.reference_table = 'k2_items'";
						$jfQuery .= " AND jfl.code=".$db->Quote($currentLang);
						$jfQuery .= " AND jfc.published=1";
						$jfQuery .= " AND jfc.value LIKE ".$word;
						$jfQuery .= " AND (jfc.reference_field = 'title'
								OR jfc.reference_field = 'introtext'
								OR jfc.reference_field = 'fulltext'
								OR jfc.reference_field = 'image_caption'
								OR jfc.reference_field = 'image_credits'
								OR jfc.reference_field = 'video_caption'
								OR jfc.reference_field = 'video_credits'
								OR jfc.reference_field = 'extra_fields_search'
								OR jfc.reference_field = 'metadesc'
								OR jfc.reference_field = 'metakey'
					)";
						$db->setQuery($jfQuery);
						$result = $db->loadResultArray();
						$result = @array_unique($result);
						JArrayHelper::toInteger($result);
						if (count($result)) {
							$conditions[] = "i.id IN(".implode(',', $result).")";
						}

					}

				} else {
					$search = explode(' ', JString::strtolower($search));
					foreach ($search as $searchword) {

						if (JString::strlen($searchword) > 3 && !in_array($searchword, $search_ignore)) {

							$word = $db->Quote('%'.$db->getEscaped($searchword, true).'%', false);

							$jfQuery = " SELECT reference_id FROM #__jf_content as jfc LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.".K2_JF_ID;
							$jfQuery .= " WHERE jfc.reference_table = 'k2_items'";
							$jfQuery .= " AND jfl.code=".$db->Quote($currentLang);
							$jfQuery .= " AND jfc.published=1";
							$jfQuery .= " AND jfc.value LIKE ".$word;
							$jfQuery .= " AND (jfc.reference_field = 'title'
									OR jfc.reference_field = 'introtext'
									OR jfc.reference_field = 'fulltext'
									OR jfc.reference_field = 'image_caption'
									OR jfc.reference_field = 'image_credits'
									OR jfc.reference_field = 'video_caption'
									OR jfc.reference_field = 'video_credits'
									OR jfc.reference_field = 'extra_fields_search'
									OR jfc.reference_field = 'metadesc'
									OR jfc.reference_field = 'metakey'
						)";
							$db->setQuery($jfQuery);
							$result = $db->loadResultArray();
							$result = @array_unique($result);
							foreach ($result as $id) {
								$allIDs[] = $id;
							}

							if (JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'joomfish.php') && $currentLang != $defaultLang) {

								if (isset($allIDs) && count($allIDs)) {
									JArrayHelper::toInteger($allIDs);
									$conditions[] = "i.id IN(".implode(',', $allIDs).")";
								}

							}


						}

					}


				}

				if (count($conditions)) {
					$sql .= " AND (".implode(" OR ", $conditions).")";
				}

			}
			else {

				$sql .= " AND MATCH(i.title, i.introtext, i.`fulltext`,i.image_caption,i.image_credits,i.video_caption,i.video_credits,i.extra_fields_search,i.metadesc,i.metakey) ";
				if ($type=='exact') {
					$text = JString::trim($search,'"');
					$text = $db->Quote('"'.$db->getEscaped($text, true).'"', false);
				}
				else {
					$search = JString::str_ireplace('*', '', $search);
					$words = explode(' ', $search);
					for($i=0; $i<count($words); $i++){
						$words[$i].= '*';
					}
					$search = implode(' ', $words);
					$text = $db->Quote($db->getEscaped($search, true), false);
				}
				$sql.= " AGAINST ({$text} IN BOOLEAN MODE)";

			}
			
			return $sql;
		}
		
		
	// ADDED K2FSM from here ->>
	// modified function prepareSearch($search) to match multiple queries
        
	function prepareFilterArray($searches2, $badchars, $slider, $array, $count, $slarray, $i_slider, $range) {
		jimport('joomla.filesystem.file');
		$db = &JFactory::getDBO();
		$language = &JFactory::getLanguage();
		
		//$defaultLang = $language->getDefault();
		$langParams = &JComponentHelper::getParams('com_languages');
		$defaultLang = $langParams->get('site', 'en-GB');
		
		$currentLang = $language->getTag();
		
		$search = $searches2 ;
		
		if(!is_array($search)) {
			$length = JString::strlen($search);

			if (JString::substr($search, 0, 1) == '"' && JString::substr($search, $length - 1, 1) == '"') {
				$type = 'exact';
			}
			else {
				$type = 'any';
			}
		}
		else $type = 'any';



                                if($slider == 0 and $array == 0) {
                                        $sql = " AND (i.extra_fields REGEXP ";
                                        if ($type == 'exact') {
                                                $text = JString::trim($search,'"');
                                                $text = $db->Quote('"'.$db->getEscaped($text, true).'"', false);
                                                $sql.= "AGAINST ({$text}  IN BOOLEAN MODE)";
                                        }
                                        else {
                                                $text = $search;
												$n = 0;
                                                for($j=0; $j<(sizeof($slarray)); $j++) {
                                                        $k = ($j + 1);
                                                        if ($slarray[$j] == $text) {
                                                               $sql .= " '^.*\"$i_slider\",\"value.[^}]*\"$k\".*$')";
                                                               $n = 1;
                                                        }
                                                }
                                                if ($n != 1) {
													$sql = '';
													
													//convert to ascii codes if not in english
													$search = json_encode($search);
													$search = str_replace("\"", "", $search);
													$search = str_replace("\\", "\\\\\\\\", $search);

													jimport('joomla.filesystem.file');
													if (JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'joomfish.php') && 	$currentLang != $defaultLang) {
													
														$search = explode(' ', JString::strtolower($search));
														
														foreach ($search as $searchword) {

															if (JString::strlen($searchword) > 3) {

																$word = $searchword;
																
																$jfQuery = " SELECT reference_id FROM #__jf_content as jfc LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.id";
																$jfQuery .= " WHERE jfc.reference_table = 'k2_items'";
																$jfQuery .= " AND jfl.code=".$db->Quote($currentLang);
																$jfQuery .= " AND jfc.published=1";
																$jfQuery .= " AND jfc.value REGEXP '^.*\"$i_slider\",\"value\":\"[^}]*$word.*\".*$'";
																$jfQuery .= " AND (jfc.reference_field = 'extra_fields')";
																
																$db->setQuery($jfQuery);
																$result = $db->loadResultArray();
																$result = @array_unique($result);
																foreach ($result as $id) {
																	$allIDs[] = $id;
																}
																
																if (isset($allIDs) && count($allIDs)) {
																	JArrayHelper::toInteger($allIDs);
																	$conditions[] = "i.id IN(".implode(',', $allIDs).")";
																}
															}
														}
														
														if (count($conditions)) {
															$sql .= " AND (".implode(" OR ", $conditions).")";
														}
													}
													else {
														$search = explode(' ', JString::strtolower($search));
														
														$sql = " AND (";
														foreach ($search as $k=>$searchword) {
															if (JString::strlen($searchword) > 3) {
																if($k == 0) {
																	$sql .= "(i.extra_fields REGEXP '^.*\"$i_slider\",\"value\":\"[^}]*$searchword.*\".*$')";
																}
																else {
																	$sql .= " OR (i.extra_fields REGEXP '^.*\"$i_slider\",\"value\":\"[^}]*$searchword.*\".*$')";
																}
															}
														}
														$sql .= ")";
													}
												}
                                        }
                                }
                                
                                else if ($slider == 0 and $array == 1) {
                                        if ($type == 'exact') {
                                                $text = JString::trim($search,'"');
                                                $text = $db->Quote('"'.$db->getEscaped($text, true).'"', false);  
                                        }
                                      else $text = $search;
                                      $sql = " AND ";
                                      $n = 0;
                                      for($j=0; $j<$count; $j++){
                                               for($k=1; $k<(sizeof($slarray)+1); $k++) {
                                                        if(($text[$j] == $slarray[$k-1]) && ($n == 0)) {
                                                                $sql .= " (i.extra_fields ";
																$sql .= "REGEXP '^.*\"$i_slider\",\"value(.[^}]*\"$k";
                                                                $n = 1;
                                                        }
                                                        else if($text[$j] == $slarray[$k-1]) $sql .= "|.[^}]*\"$k"; 
                                               }
                                      }
                                     $sql .= ")\".*$')";
                                }
                                
                                else if ($slider == 1 && $array == 0 && $range == 0) {
                                        if ($type == 'exact') {
                                                $text = JString::trim($search,'"');
                                                $text = $db->Quote('"'.$db->getEscaped($text, true).'"', false);  
                                        }
										
                                        else $text = trim($search);
                                        
										$array = array();
                                        for($j=1; $j<(sizeof($slarray)+1); $j++)
                                                $array[$j] = $slarray[$j-1];
                                        $array[0] = "0";
                                        $slarray = $array;
                                        for($j=0; $j<(sizeof($slarray)); $j++)
                                                if(trim($slarray[$j]) == $text) $place = ($j+1);
                                        $k =  0;
                                        for($j=1; $j<(sizeof($slarray)+1); $j++) {
                                                if(($j+1) <= $place) {
                                                        if($k == 0) { 
                                                                $sql = " AND ((i.extra_fields REGEXP '^.*\"$i_slider\",\"value.[^}]*\"$j\".*$')";
                                                                $k = 1;
                                                        }
                                                        else $sql .= " OR (i.extra_fields REGEXP '^.*\"$i_slider\",\"value.[^}]*\"$j\".*$')";
                                                }
                                                else $j = sizeof($slarray);
                                        }
                                        if($sql == "")
                                                $sql = " AND (i.extra_fields REGEXP '^.*\"$i_slider\",\"value.[^}]*\"$j\".*$'";
                                        else $sql .= " OR (i.extra_fields REGEXP '^.*\"$i_slider\",\"value.[^}]*\"0\".*$')";
                                    
                                        $sql .= ")";

                                }
								
                                else if ($slider == 1 && $array == 0 && $range == 1) {
										
                                        $texts = trim($search);
										$texts = str_replace(" ", "", $texts);
										$texts = explode("-", $texts);
                                        
										$stop = sizeof($slarray)-1;
                                        
										for($j=0; $j<=$stop; $j++)
                                                if(trim($slarray[$j]) == $texts[1]) $place = $j+1;
												
										for($j=0; $j<=$stop; $j++) {
                                            if(trim($slarray[$j]) == $texts[0]) {
												$place1 = $j+1;
											}

										}
										
										$k =  0;
										if(!isset($place1)) $place1 = 0;
										for($j=$place1; $j<$stop+2; $j++) {
                                                if(($j <= $place)) {
                                                        if($k == 0) { 
                                                                $sql = " AND ((i.extra_fields REGEXP '^.*\"$i_slider\",\"value.[^}]*\"$j\".*$')";
                                                                $k = 1;
                                                        }
                                                        else $sql .= " OR (i.extra_fields REGEXP '^.*\"$i_slider\",\"value.[^}]*\"$j\".*$')";
                                                }
                                                else break;
                                        }
                                    
                                        $sql .= ")";

                                }
                        

			return $sql;
		}
		
		function getCategoryTree($categories){
			$mainframe = &JFactory::getApplication();
			$db = &JFactory::getDBO();
			$user = &JFactory::getUser();
			$aid = (int) $user->get('aid');
			if(!is_array($categories)){
				$categories = (array)$categories;
			}
			JArrayHelper::toInteger($categories);
			$categories = array_unique($categories);
			sort($categories);
			$key = implode('|', $categories);
			$clientID = $mainframe->getClientId();
			static $K2CategoryTreeInstances = array();
			if(isset($K2CategoryTreeInstances[$clientID]) && array_key_exists($key, $K2CategoryTreeInstances[$clientID])){
				return $K2CategoryTreeInstances[$clientID][$key];
			}
			$array = $categories;
			while(count($array)){
				$query = "SELECT id
						FROM #__k2_categories 
						WHERE parent IN (".implode(',', $array).") 
						AND id NOT IN (".implode(',', $array).") ";
				if($mainframe->isSite()){
					$query.="
								AND published=1 
								AND trash=0";
					if(K2_JVERSION=='16'){
						$query.= " AND access IN(".implode(',', $user->authorisedLevels()).")";
						if($mainframe->getLanguageFilter()) {
							$query.= " AND language IN(".$db->Quote(JFactory::getLanguage()->getTag()).", ".$db->Quote('*').")";
						}
					}
					else{
						$query.=" AND access<={$aid}";
					}
				}
				$db->setQuery($query);
				$array = $db->loadResultArray();
				$categories = array_merge($categories, $array);
			}
			JArrayHelper::toInteger($categories);
			$categories = array_unique($categories);
			$K2CategoryTreeInstances[$clientID][$key] = $categories;
			return $categories;
		}		
		
		function utfCharToNumber($char) {
			$i = 0;
			$number = '';
			while (isset($char{$i})) {
				$number.= ord($char{$i});
				++$i;
				}
			return $number;
		}
		
		function getExtra($exclude) {
			
			$db = &JFactory::getDBO();
			
			if($exclude != "") {
				$query = "SELECT * FROM #__k2_extra_fields WHERE id NOT IN ({$exclude}) ORDER BY ordering";
			}
			else {
				$query = "SELECT * FROM #__k2_extra_fields ORDER BY ordering";
			}
			$db->setQuery($query);
			$extras = $db->loadObjectList();
			
			return $extras;
		}

		function compareasc($v1, $v2) {
		   if ($v1[1] == $v2[1]) return 0;
		   return ($v1[1] < $v2[1])?-1:1;
		}		
		
		function comparedesc($v1, $v2) {
		   if ($v1[1] == $v2[1]) return 0;
		   return ($v1[1] > $v2[1])?-1:1;
		}		
		
		// <<- ADDED K2FSM till here

}
