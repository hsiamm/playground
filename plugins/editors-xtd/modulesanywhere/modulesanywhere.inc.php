<?php
/**
 * Popup page
 * Displays a list with modules
 *
 * @package     Modules Anywhere
 * @version     1.11.8
 *
 * @author      Peter van Westen <peter@nonumber.nl>
 * @link        http://www.nonumber.nl
 * @copyright   Copyright © 2011 NoNumber! All Rights Reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die();

$user =& JFactory::getUser();
if ( $user->get( 'guest' ) ) {
	JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
}

require_once JPATH_PLUGINS.DS.'system'.DS.'nonumberelements'.DS.'helpers'.DS.'parameters.php';
$parameters =& NNParameters::getParameters();
$params = $parameters->getPluginParamValues( 'modulesanywhere', 'editors-xtd' );

$mainframe =& JFactory::getApplication();
if ( $mainframe->isSite() ) {
	if ( !$params->enable_frontend ) {
		JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
	}
}

$class = new plgButtonModulesAnywherePopup();
$class->render( $params );

class plgButtonModulesAnywherePopup
{
	function render( &$params )
	{
		$mainframe =& JFactory::getApplication();

		// load the admin language file
		$lang =& JFactory::getLanguage();
		if ( $lang->getTag() != 'en-GB' ) {
			// Loads English language file as fallback (for undefined stuff in other language file)
			$lang->load( 'plg_editors-xtd_modulesanywhere', JPATH_ADMINISTRATOR, 'en-GB' );
		}
		$lang->load( 'plg_editors-xtd_modulesanywhere', JPATH_ADMINISTRATOR, null, 1 );
		// load the content language file
		$lang->load( 'com_modules', JPATH_ADMINISTRATOR);

		// Initialize some variables
		$db =& JFactory::getDBO();
		$client =& JApplicationHelper::getClientInfo( JRequest::getVar( 'client', '0', '', 'int' ) );
		$option = 'modulesanywhere';

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'filter_order',		'filter_order',		'm.position',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$filter_state		= $mainframe->getUserStateFromRequest( $option.'filter_state',		'filter_state',		'',				'word' );
		$filter_position	= $mainframe->getUserStateFromRequest( $option.'filter_position',	'filter_position',	'',				'cmd' );
		$filter_type		= $mainframe->getUserStateFromRequest( $option.'filter_type',		'filter_type',		'',				'cmd' );
		$search				= $mainframe->getUserStateFromRequest( $option.'search',			'search',			'',				'string' );
		$search				= JString::strtolower( $search );

		$limit				= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg( 'list_limit' ), 'int' );
		$limitstart			= $mainframe->getUserStateFromRequest( 'modulesanywhere_limitstart', 'limitstart', 0, 'int' );

		$where[] = 'm.client_id = '.( int ) $client->id;

		$joins[] = 'LEFT JOIN #__users AS u ON u.id = m.checked_out';
		$joins[] = 'LEFT JOIN #__viewlevels AS g ON g.id = m.access';
		$joins[] = 'LEFT JOIN #__modules_menu AS mm ON mm.moduleid = m.id';

		// used by filter
		if ( $filter_position ) {
			$where[] = 'm.position = '.$db->quote( $filter_position );
		}
		if ( $filter_type ) {
			$where[] = 'm.module = '.$db->quote( $filter_type );
		}
		if ( $search ) {
			$where[] = 'LOWER( m.title ) LIKE '.$db->quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}
		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where[] = 'm.published = 1';
			} else if ( $filter_state == 'U' ) {
				$where[] = 'm.published = 0';
			}
		}

		$where		= ' WHERE ' . implode( ' AND ', $where );
		$join		= ' ' . implode( ' ', $joins );
		if ( $filter_order == 'm.ordering' ) {
			$orderby = ' ORDER BY m.position, m.ordering '. $filter_order_Dir;
		} else {
			$orderby = ' ORDER BY '. $filter_order .' '. $filter_order_Dir .', m.ordering ASC';
		}

		// get the total number of records
		$query = 'SELECT COUNT( DISTINCT m.id )'
		. ' FROM #__modules AS m'
		. $join
		. $where
		;
		$db->setQuery( $query );
		$total = $db->loadResult();

		jimport( 'joomla.html.pagination' );
		$pageNav = new JPagination( $total, $limitstart, $limit );

		$query = 'SELECT m.*, u.name AS editor, g.title AS groupname, MIN( mm.menuid ) AS pages'
		. ' FROM #__modules AS m'
		. $join
		. $where
		. ' GROUP BY m.id'
		. $orderby
		;
		$db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		$rows = $db->loadObjectList();
		if ( $db->getErrorNum() ) {
			echo $db->stderr();
			return false;
		}

		// get list of Positions for dropdown filter
		$query = 'SELECT m.position AS value, m.position AS text'
		. ' FROM #__modules as m'
		. ' WHERE m.client_id = '.( int ) $client->id
		. ' GROUP BY m.position'
		. ' ORDER BY m.position'
		;
		$positions[] = JHTML::_( 'select.option',  '0', '- '. JText::_( 'Select Position' ) .' -' );
		$db->setQuery( $query );
		$positions = array_merge( $positions, $db->loadObjectList() );
		$lists['position']	= JHTML::_( 'select.genericlist',   $positions, 'filter_position', 'class="inputbox" size="1" onchange="this.form.submit()"', 'value', 'text', "$filter_position" );

		// get list of Positions for dropdown filter
		$query = 'SELECT module AS value, module AS text'
		. ' FROM #__modules'
		. ' WHERE client_id = '.( int ) $client->id
		. ' GROUP BY module'
		. ' ORDER BY module'
		;
		$db->setQuery( $query );
		$types[]		= JHTML::_( 'select.option',  '0', '- '. JText::_( 'Select Type' ) .' -' );
		$types			= array_merge( $types, $db->loadObjectList() );
		$lists['type']	= JHTML::_( 'select.genericlist',   $types, 'filter_type', 'class="inputbox" size="1" onchange="this.form.submit()"', 'value', 'text', "$filter_type" );

		// state filter
		$lists['state']	= JHTML::_( 'grid.state',  $filter_state );

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		// search filter
		$lists['search']= $search;

		$this->outputHTML( $params, $rows, $client, $pageNav, $lists );
	}

	function outputHTML( &$params, &$rows, &$client, &$page, &$lists )
	{
		$parameters =& NNParameters::getParameters();
		$system_params = $parameters->getPluginParamValues( 'modulesanywhere' );

		$tag = explode( ',', $system_params->module_tag );
		$tag = trim( $tag['0'] );
		$postag = explode( ',', $system_params->modulepos_tag );
		$postag = trim( $postag['0'] );

		JHTML::_( 'behavior.tooltip' );

		// Add scripts and styles
		$document =& JFactory::getDocument();
		$script = "
			function modulesanywhere_jInsertEditorText( id, modulepos ) {
				f = document.getElementById( 'adminForm' );
				var style = f.style.options[f.style.selectedIndex].value.trim();
				if ( modulepos ) {
					str = '{".$postag." '+id+'}';
				} else {
					str = '{".$tag." '+id;
					if ( style && style != '".$system_params->style."' ) {
						str += '|'+style;
					}
					str += '}';
				}

				if ( f.div_enable.checked ) {
					var float = f.div_float.options[f.div_float.selectedIndex].value.trim();
					var params = new Array();
					if( f.div_width.value.trim() ) { params[params.length] = 'width:'+f.div_width.value.trim(); }
					if( f.div_height.value.trim() ) { params[params.length] = 'height:'+f.div_height.value.trim(); }
					if( float ) { params[params.length] = 'float:'+float; }
					if( f.div_class.value.trim() ) { params[params.length] = 'class:'+f.div_class.value.trim(); }
					str = ( '{div '+params.join('|') ).trim()+'}'+str.trim()+'{/div}';
				}

				window.parent.jInsertEditorText( str, '".JRequest::getVar( 'name' )."' );
				window.parent.SqueezeBox.close();
			}

			function toggleByCheckbox( id ) {
				el = document.getElementById( id );
				div = document.getElementById( id+'_div' );
				if ( el.checked ) {
					div.style.display = 'block';
				} else {
					div.style.display = 'none';
				}
			}
			window.addEvent('domready', function(){ toggleByCheckbox('div_enable'); });
		";
		$document->addScriptDeclaration( $script );
		$document->addStyleSheet( JURI::root( true ).'/plugins/system/nonumberelements/css/popup.css' );
	?>
	<div style="margin: 0;">
		<form action="" method="post" name="adminForm" id="adminForm">
			<fieldset>
				<div style="float: left">
					<h1><?php echo JText::_( 'MODULES_ANYWHERE' ); ?></h1>
				</div>
				<div style="float: right">
					<div class="button2-left"><div class="blank hasicon cancel">
						<a rel="" onclick="window.parent.SqueezeBox.close();" href="javascript://" title="<?php echo JText::_('Cancel') ?>"><?php echo JText::_('Cancel') ?></a>
					</div></div>
				</div>
			</fieldset>

			<p><?php echo html_entity_decode( JText::_( 'MA_CLICK_ON_ONE_OF_THE_MODULES_LINKS' ), ENT_COMPAT, 'UTF-8' ); ?></p>

			<table class="adminform" cellspacing="2" style="width:auto;float:left;margin-right:10px;">
				<tr>
					<th>
						<?php echo JText::_( 'MA_MODULE_STYLE' ); ?>:<br />
						<select name="style" class="inputbox">
						<?php
							$style = JRequest::getCmd( 'style' );
							if ( !$style ) {
								$style = $system_params->style;
							}

							echo '
								<option '.( ( $style == 'none' ) ? 'selected="selected" value=""' : 'value="none"' ).'>'.
									JText::_( 'MA_NO_WRAPPING' ).'</option>
								<option '.( ( $style == 'table' ) ? 'selected="selected" value=""' : 'value="table"' ).'>'.
									JText::_( 'MA_TABLE' ).'</option>
								<option '.( ( $style == 'horz' ) ? 'selected="selected" value=""' : 'value="horz"' ).'>'.
									JText::_( 'MA_HORZ' ).'</option>
								<option '.( ( $style == 'xhtml' ) ? 'selected="selected" value=""' : 'value="xhtml"' ).'>'.
									JText::_( 'MA_XHTML' ).'</option>
								<option '.( ( $style == 'rounded' ) ? 'selected="selected" value=""' : 'value="rounded"' ).'>'.
									JText::_( 'MA_ROUNDED' ).'</option>
							';
						?>
						</select>
					</th>
				</tr>
			</table>

			<table class="adminform" cellspacing="2" style="width:auto;float:left;">
				<tr style="height:30px;">
					<th>
						<label class="hasTip" title="<?php echo JText::_( 'MA_EMBED_IN_A_DIV' ).'::'.JText::_( 'MA_EMBED_IN_A_DIV_DESC' ); ?>">
							<input type="checkbox" onclick="toggleByCheckbox('div_enable');" onchange="toggleByCheckbox('div_enable');" name="div_enable" id="div_enable" <?php if ( $params->div_enable ) { echo 'checked="checked"'; } ?> />
							<?php echo JText::_( 'MA_EMBED_IN_A_DIV' ); ?>&nbsp;
						</label>
						<div id="div_enable_div" style="display:block;">
							<table>
								<tr>
									<td>
										<label class="hasTip" title="<?php echo JText::_( 'MA_WIDTH' ).'::'.JText::_( 'MA_WIDTH_DESC' ); ?>">
											<?php echo JText::_( 'MA_WIDTH' ); ?>:
											<input type="text" class="text_area" name="div_width" id="div_width" value="<?php echo $params->div_width; ?>" size="4" style="text-align: right;" />
										</label>
									</td>
									<td>
										<label class="hasTip" title="<?php echo JText::_( 'MA_HEIGHT' ).'::'.JText::_( 'MA_HEIGHT_DESC' ); ?>">
											<?php echo JText::_( 'MA_HEIGHT' ); ?>:
											<input type="text" class="text_area" name="div_height" id="div_height" value="<?php echo $params->div_height; ?>" size="4" style="text-align: right;" />
										</label>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<label class="hasTip" title="<?php echo JText::_( 'MA_ALIGNMENT' ).'::'.JText::_( 'MA_ALIGNMENT_DESC' ); ?>">
											<?php echo JText::_( 'MA_ALIGNMENT' ); ?>:
											<select name="div_float" id="div_float" class="inputbox">
												<option value=""<?php if ( !$params->div_float ) { echo 'selected="selected"'; } ?>>
													<?php echo JText::_( 'None' ); ?></option>
												<option value="left"<?php if ( $params->div_float == 'left' ) { echo 'selected="selected"'; } ?>>
													<?php echo JText::_( 'Left' ); ?></option>
												<option value="right"<?php if ( $params->div_float == 'right' ) { echo 'selected="selected"'; } ?>>
													<?php echo JText::_( 'Right' ); ?></option>
											</select>
										</label>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<label class="hasTip" title="<?php echo JText::_( 'MA_DIV_CLASSNAME' ).'::'.JText::_( 'MA_DIV_CLASSNAME_DESC' ); ?>">
											<?php echo JText::_( 'MA_DIV_CLASSNAME' ); ?>:
											<input type="text" class="text_area" name="div_class" id="div_class" value="<?php echo $params->div_class; ?>" />
										</label>
									</td>
								</tr>
							</table>
						</div>
					</th>
				</tr>
			</table>

			<div style="clear:both;"></div>

			<table class="adminform" cellspacing="1">
				<tbody>
					<tr>
						<td>
							<?php echo JText::_( 'Filter' ); ?>:
							<input type="text" name="search" id="search" value="<?php echo $lists['search'];?>" class="text_area" onchange="this.form.submit();" />
							<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
							<button onclick="
								document.getElementById( 'search' ).value='';
								document.getElementById( 'filter_position' ).value='0';
								document.getElementById( 'filter_type' ).value='0';
								document.getElementById( 'filter_state' ).value='';
								this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
						</td>
						<td style="text-align:right;">
							<?php
								echo $lists['position'];
								echo $lists['type'];
								echo $lists['state'];
							?>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="adminlist" cellspacing="1">
				<thead>
					<tr>
						<th nowrap="nowrap" width="1%">
							<?php echo JHTML::_( 'grid.sort',   'ID', 'm.id', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
						<th class="title">
							<?php echo JHTML::_( 'grid.sort', 'Module Name', 'm.title', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
						<th nowrap="nowrap" width="7%">
							<?php echo JHTML::_( 'grid.sort',   'Position', 'm.position', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
						<th nowrap="nowrap" width="7%">
							<?php echo JHTML::_( 'grid.sort', 'Published', 'm.published', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
						<th nowrap="nowrap" width="1%">
							<?php echo JHTML::_( 'grid.sort', 'Order', 'm.ordering', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
						<?php
						if ( $client->id == 0 ) {
							?>
							<th nowrap="nowrap" width="7%">
								<?php echo JHTML::_( 'grid.sort', 'Access', 'groupname', @$lists['order_Dir'], @$lists['order'] ); ?>
							</th>
							<?php
						}
						?>
						<th nowrap="nowrap" width="5%">
							<?php echo JHTML::_( 'grid.sort',   'Pages', 'pages', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
						<th nowrap="nowrap" width="10%"  class="title">
							<?php echo JHTML::_( 'grid.sort',   'Type', 'm.module', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="<?php echo ( $client->id == 0 ) ? '8' : '7'; ?>">
							<?php
								$pagination = str_replace( 'index.php?', 'plugins/editors-xtd/modulesanywhere/elements/modulesanywhere.page.php?name='.JRequest::getCmd( 'name', 'text' ).'&', $page->getListFooter() );
								$pagination = str_replace( 'index.php', 'plugins/editors-xtd/modulesanywhere/elements/modulesanywhere.page.php?name='.JRequest::getCmd( 'name', 'text' ), $pagination );
								echo $pagination;
							?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php
				$k = 0;
				for ( $i=0, $n=count( $rows ); $i < $n; $i++ ) {
					$row =& $rows[$i];

					if ( $row->published ) {
						$img = 'tick_l.png';
						$alt = JText::_( 'Published' );
					} else {
						$img = 'publish_x_l.png';
						$alt = JText::_( 'Unpublished' );
					}
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="right">
							<?php echo '<label class="hasTip" title="'.JText::_( 'MA_USE_ID_IN_TAG' ).'::{module '.$row->id.'}"><a href="javascript://" onclick="modulesanywhere_jInsertEditorText( \''.$row->id.'\' )">'.$row->id.'</a></label>';?>
						</td>
						<td>
							<?php echo '<label class="hasTip" title="'.JText::_( 'MA_USE_TITLE_IN_TAG' ).'::{module '.htmlspecialchars($row->title).'}"><a href="javascript://" onclick="modulesanywhere_jInsertEditorText( \''.addslashes(htmlspecialchars($row->title)).'\' )">'.htmlspecialchars($row->title).'</a></label>'; ?>
						</td>
						<td align="center">
							<?php echo '<label class="hasTip" title="'.JText::_( 'MA_USE_MODULE_POSITION_TAG' ).'::{modulepos '.$row->position.'}"><a href="javascript://" onclick="modulesanywhere_jInsertEditorText( \''.$row->position.'\', 1 )">'.$row->position.'</a></label>'; ?>
						</td>
						<td style="text-align:center;">
							<img src="<?php echo JURI::root( true ).'/plugins/system/nonumberelements/images/'.$img; ?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>'" />
						</td>
						<td align="center">
							<?php echo $row->ordering; ?>
						</td>
						<?php
						if ( $client->id == 0 ) {
							?>
							<td align="center"><?php echo JText::_( $row->groupname ); ?></td>
							<?php
						}
						?>
						<td align="center">
							<?php
							if ( is_null( $row->pages ) ) {
								echo JText::_( 'None' );
							} else if ( $row->pages > 0 ) {
								echo JText::_( 'Varies' );
							} else {
								echo JText::_( 'All' );
							}
							?>
						</td>
						<td>
							<?php echo $row->module ? $row->module : JText::_( 'User' ); ?>
						</td>
					</tr>
					<?php
					$k = 1 - $k;
				}
				?>
				</tbody>
			</table>
			<input type="hidden" name="name" value="<?php echo JRequest::getCmd( 'name', 'text' ); ?>" />
			<input type="hidden" name="client" value="<?php echo $client->id;?>" />
			<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
		</form>
	</div>
	<?php
	}
}