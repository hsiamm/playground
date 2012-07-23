<?php
/**
 * @version		$Id: view.html.php 1492 2012-02-22 17:40:09Z joomlaworks@gmail.com $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class K2ViewCategories extends JView
{

	function display($tpl = null)
	{

		$mainframe = &JFactory::getApplication();
		$user = & JFactory::getUser();
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		$filter_order = $mainframe->getUserStateFromRequest($option.$view.'filter_order', 'filter_order', 'c.ordering', 'cmd');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($option.$view.'filter_order_Dir', 'filter_order_Dir', '', 'word');
		$filter_trash = $mainframe->getUserStateFromRequest($option.$view.'filter_trash', 'filter_trash', 0, 'int');
		$filter_category = $mainframe->getUserStateFromRequest($option.$view.'filter_category', 'filter_category', 0, 'int');
		$filter_state = $mainframe->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int');
		$language = $mainframe->getUserStateFromRequest($option.$view.'language', 'language', '', 'string');
		$search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		$search = JString::strtolower($search);
		$model = & $this->getModel();
		$total = $model->getTotal();
		if ($limitstart > $total - $limit){
			$limitstart = max(0, (int) (ceil($total / $limit) - 1) * $limit);
			JRequest::setVar('limitstart', $limitstart);
		}

		$categories = $model->getData();
		require_once(JPATH_COMPONENT.DS.'models'.DS.'category.php');
		$categoryModel= new K2ModelCategory;

		$params = & JComponentHelper::getParams('com_k2');
		$this->assignRef('params', $params);
		if ($params->get('showItemsCounterAdmin')){
			for ($i=0; $i<sizeof($categories); $i++){
				$categories[$i]->numOfItems=$categoryModel->countCategoryItems($categories[$i]->id);
				$categories[$i]->numOfTrashedItems=$categoryModel->countCategoryItems($categories[$i]->id, 1);
			}
		}

		$this->assignRef('rows', $categories);
		
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $limitstart, $limit);
		$this->assignRef('page', $pageNav);

		$lists = array ();
		$lists['search'] = $search;
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		$filter_trash_options[] = JHTML::_('select.option', 0, JText::_('K2_CURRENT'));
		$filter_trash_options[] = JHTML::_('select.option', 1, JText::_('K2_TRASHED'));
		$lists['trash'] = JHTML::_('select.genericlist', $filter_trash_options, 'filter_trash', '', 'value', 'text', $filter_trash);

		$filter_state_options[] = JHTML::_('select.option', -1, JText::_('K2_SELECT_STATE'));
		$filter_state_options[] = JHTML::_('select.option', 1, JText::_('K2_PUBLISHED'));
		$filter_state_options[] = JHTML::_('select.option', 0, JText::_('K2_UNPUBLISHED'));
		$lists['state'] = JHTML::_('select.genericlist', $filter_state_options, 'filter_state', '', 'value', 'text', $filter_state);

		$categoriesModel= JModel::getInstance('Categories', 'K2Model');
		$categories_option[]=JHTML::_('select.option', 0, JText::_('K2_SELECT_CATEGORY'));
		$categoriesFilter = $categoriesModel->categoriesTree(NULL, true, false);
		$categories_options=@array_merge($categories_option, $categoriesFilter);
		$lists['categories'] = JHTML::_('select.genericlist', $categories_options, 'filter_category', '', 'value', 'text', $filter_category);

		if(version_compare( JVERSION, '1.6.0', 'ge' )) {
			$languages = JHTML::_('contentlanguage.existing', true, true);
			array_unshift($languages, JHTML::_('select.option', '', JText::_('K2_SELECT_LANGUAGE')));
			$lists['language'] = JHTML::_('select.genericlist', $languages, 'language', '', 'value', 'text', $language);
		}
		$this->assignRef('lists', $lists);

		JToolBarHelper::title(JText::_('K2_CATEGORIES'), 'k2.png');

		if ($filter_trash == 1) {
			JToolBarHelper::custom('restore','restore.png','restore_f2.png','K2_RESTORE', true);
			JToolBarHelper::deleteList('K2_ARE_YOU_SURE_YOU_WANT_TO_DELETE_SELECTED_CATEGORIES', 'remove', 'K2_DELETE');
		}
		else {
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
			JToolBarHelper::customX( 'move', 'move.png', 'move_f2.png', 'K2_MOVE' );
			JToolBarHelper::customX( 'copy', 'copy.png', 'copy_f2.png', 'K2_COPY' );
			JToolBarHelper::editList();
			JToolBarHelper::addNew();
			JToolBarHelper::trash('trash');
		}

		if(K2_JVERSION == '16'){
			JToolBarHelper::preferences('com_k2', 550, 875, 'K2_PARAMETERS');
		}
		else {
			$toolbar=& JToolBar::getInstance('toolbar');
        	$toolbar->appendButton('Popup', 'config', 'Parameters', 'index.php?option=com_k2&view=settings');			
		}

		$this->loadHelper('html');
		K2HelperHTML::subMenu();
		
		$this->assignRef('filter_trash', $filter_trash);
		$template = $mainframe->getTemplate();
		$this->assignRef('template', $template);
		$ordering = ( ($this->lists['order'] == 'c.ordering' || $this->lists['order'] == 'c.parent, c.ordering') && (!$this->filter_trash) );
		$this->assignRef('ordering', $ordering);
		parent::display($tpl);

	}

	function move(){

		$mainframe = &JFactory::getApplication();
		JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
		$cid = JRequest::getVar('cid');

		foreach ($cid as $id) {
			$row = & JTable::getInstance('K2Category', 'Table');
			$row->load($id);
			$rows[]=$row;
		}

		require_once(JPATH_COMPONENT.DS.'models'.DS.'categories.php');
		$categoriesModel= new K2ModelCategories;
		$categories_option[]=JHTML::_('select.option', 0, JText::_('K2_NONE_ONSELECTLISTS'));
		$categories = $categoriesModel->categoriesTree(NULL, true, false);
		$categories_options=@array_merge($categories_option, $categories);
		foreach($categories_options as $option){
			if(in_array($option->value, $cid))
				$option->disable = true;
		}
		$lists['categories'] = JHTML::_('select.genericlist', $categories_options, 'category', 'class="inputbox" size="8"', 'value', 'text');

		$this->assignRef('rows',$rows);
		$this->assignRef('lists',$lists);

		JToolBarHelper::title( JText::_('K2_MOVE_CATEGORIES'), 'k2.png' );

		JToolBarHelper::custom('saveMove','save.png','save_f2.png','K2_SAVE', false);
		JToolBarHelper::cancel();

		parent::display();
	}

}
