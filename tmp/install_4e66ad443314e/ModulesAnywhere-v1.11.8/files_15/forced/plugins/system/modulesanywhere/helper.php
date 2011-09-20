<?php
/**
 * Plugin Helper File
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

// Import library dependencies
jimport( 'joomla.plugin.plugin' );

// Load common functions
require_once JPATH_PLUGINS.DS.'system'.DS.'nonumberelements'.DS.'helpers'.DS.'functions.php';

/**
* Plugin that places modules
*/
class plgSystemModulesAnywhereHelper
{
	function __construct( &$params )
	{
		$this->params = $params;
		$this->params->comment_start = '<!-- START: Modules Anywhere -->';
		$this->params->comment_end = '<!-- END: Modules Anywhere -->';
		$this->params->message_start = '<!--  Modules Anywhere Message: ';
		$this->params->message_end = ' -->';

		$tags = array();
		$tags[] = preg_quote( $this->params->module_tag, '#' );
		$tags[] = preg_quote( $this->params->modulepos_tag, '#' );
		if ( $this->params->handle_loadposition ) { $tags[] = 'loadposition'; }
		$tags = '('.implode( '|', $tags ).')';
		$this->params->tags = $tags;

		$bts =	'((?:<p(?: [^>]*)?>)?)((?:\s*<br ?/?>)?\s*)';
		$bte =	'(\s*(?:<br ?/?>\s*)?)((?:</p>)?)';
		$this->params->regex = '#'
			.$bts.'((?:\{div(?: [^\}]*)\})?)(\s*)'
			.'\{'.$tags.'(?: ([^\}\|]*))?((?:\|.+?)?)(?<!\\\\)\}'
			.'(\s*)((?:\{/div\})?)'.$bte
			.'#s';

		$acl =& JFactory::getACL();
		$this->params->acl = $acl->get_group_data( $this->params->articles_security_level );
		$this->params->acl = $this->params->acl['4'];
		$this->params->acls = array();

		$user =& JFactory::getUser();
		$this->params->aid = $user->get( 'aid', 0 );
		$this->params->aid_jaclplus = $user->get( 'jaclplus', 0 );
	}

////////////////////////////////////////////////////////////////////
// onPrepareContent
////////////////////////////////////////////////////////////////////

	function onPrepareContent ( &$article )
	{
		$message = '';

		if ( isset( $article->created_by ) ) {
			// Lookup group level of creator
			if ( !isset( $this->params->acls[$article->created_by] ) ) {
				$acl =& JFactory::getACL();
				$this->params->acls[$article->created_by] = $acl->getAroGroup( $article->created_by );
			}
			$article_group = $this->params->acls[$article->created_by];

			if ( !isset( $article_group->lft ) ) {
				$article_group->lft = 0;
			}

			// Set if security is passed
			// passed = creator is equal or higher than security group level
			if ( $this->params->acl > $article_group->lft ) {
				$message = JText::_( 'MA_OUTPUT_REMOVED_SECURITY' );
			}
		}

		if ( isset( $article->text ) ) {
			$this->processModules( $article->text, 'articles', $message );
		}
		if ( isset( $article->description ) ) {
			$this->processModules( $article->description, 'articles', $message );
		}
		if ( isset( $article->title ) ) {
			$this->processModules( $article->title, 'articles', $message );
		}
		if ( isset( $article->author ) ) {
			if ( isset( $article->author->name ) ) {
				$this->processModules( $article->author->name, 'articles', $message );
			} else if ( is_string( $article->author ) ) {
				$this->processModules( $article->author, 'articles', $message );
			}
		}
	}

////////////////////////////////////////////////////////////////////
// onAfterDispatch
////////////////////////////////////////////////////////////////////

	function onAfterDispatch()
	{
		$document =& JFactory::getDocument();
		$docType = $document->getType();

		if ( ( $docType == 'feed' || JRequest::getCmd( 'option' ) == 'com_acymailing' ) && isset( $document->items ) ) {
			$itemids = array_keys( $document->items );
			foreach ( $itemids as $i ) {
				$this->onPrepareContent( $document->items[$i] );
			}
		}

		if ( isset( $document->_buffer ) ) {
			$this->tagArea( $document->_buffer, 'MODA', 'component' );
		}

		// PDF
		if ( $docType == 'pdf' ) {
			if ( isset( $document->_header ) ) {
				$this->replaceTags( $document->_header );
				$this->cleanLeftoverJunk( $document->_header );
			}
			if ( isset( $document->title ) ) {
				$this->replaceTags( $document->title );
				$this->cleanLeftoverJunk( $document->title );
			}
			if ( isset( $document->_buffer ) ) {
				$this->replaceTags( $document->_buffer );
				$this->cleanLeftoverJunk( $document->_buffer );
			}
		}
	}

////////////////////////////////////////////////////////////////////
// onAfterRender
////////////////////////////////////////////////////////////////////
	function onAfterRender()
	{
		$document =& JFactory::getDocument();
		$docType = $document->getType();

		// not in pdf's
		if ( $docType == 'pdf' ) { return; }

		$html = JResponse::getBody();
		if ( $html == '' ) { return; }

		if ( $docType != 'html' ) {
			$this->replaceTags( $html );
		} else {
			if ( !( strpos( $html, '<body' ) === false ) && !( strpos( $html, '</body>' ) === false ) ) {
				$html_split = explode( '<body', $html, 2 );
				$body_split = explode( '</body>', $html_split['1'], 2 );

				// only do stuff in body
				$this->protect( $body_split['0'] );
				$this->replaceTags( $body_split['0'] );

				$html_split['1'] = implode( '</body>', $body_split );
				$html = implode( '<body', $html_split );
			} else {
				$this->protect( $html );
				$this->replaceTags( $html );
			}
		}

		$this->cleanLeftoverJunk( $html );
		$this->unprotect( $html );

		JResponse::setBody( $html );
	}

	function replaceTags( &$str )
	{
		if ( $str == '' ) { return; }

		$document =& JFactory::getDocument();
		$docType = $document->getType();

		// COMPONENT
		if ( $docType == 'feed' || JRequest::getCmd( 'option' ) == 'com_acymailing' ) {
			$s = '#(<item[^>]*>)#s';
			$str = preg_replace( $s, '\1<!-- START: MODA_COMPONENT -->', $str );
			$str = str_replace( '</item>', '<!-- END: MODA_COMPONENT --></item>', $str );
		}
		if ( strpos( $str, '<!-- START: MODA_COMPONENT -->' ) === false ) {
			$this->tagArea( $str, 'MODA', 'component' );
		}

		$components = $this->params->components;
		if ( !is_array( $components ) ) {
			$components = explode( '|', $components );
		}

		$message = '';
		if ( in_array( JRequest::getCmd( 'option' ), $components ) ) {
			// For all components that are selected, set the message
			$message = JText::_( 'MA_OUTPUT_REMOVED_NOT_ENABLED' );
		}

		$components = $this->getTagArea( $str, 'MODA', 'component' );

		foreach ( $components as $component ) {
			$this->processModules( $component['1'], 'components', $message );
			$str = str_replace( $component['0'], $component['1'], $str );
		}

		// EVERYWHERE
		$this->processModules( $str, 'other' );
	}

	function tagArea( &$str, $ext = 'EXT', $area = '' )
	{
		if ( $area ) {
			if ( is_array( $str ) ) {
				foreach ( $str as $key => $val ) {
					$this->tagArea( $val, $ext, $area );
					$str[ $key ] = $val;
				}
			} else if ( $str ) {
				$str = '<!-- START: '.strtoupper( $ext ).'_'.strtoupper( $area ).' -->'.$str.'<!-- END: '.strtoupper( $ext ).'_'.strtoupper( $area ).' -->';
				if ( $area == 'article_text' ) {
					$str = preg_replace( '#(<hr class="system-pagebreak".*?/>)#si', '<!-- END: '.strtoupper( $ext ).'_'.strtoupper( $area ).' -->\1<!-- START: '.strtoupper( $ext ).'_'.strtoupper( $area ).' -->', $str );
				}
			}
		}
	}

	function getTagArea( &$str, $ext = 'EXT', $area = '' )
	{
		$matches = array();
		if ( $str && $area ) {
			$start = '<!-- START: '.strtoupper( $ext ).'_'.strtoupper( $area ).' -->';
			$end = '<!-- END: '.strtoupper( $ext ).'_'.strtoupper( $area ).' -->';
			$matches = explode( $start, $str );
			array_shift( $matches );
			foreach ( $matches as $i => $match ) {
				list( $text ) = explode( $end, $match, 2 );
				$matches[$i] = array(
					$start.$text.$end,
					$text
				);
			}
		}
		return $matches;
	}

	function processModules( &$string, $area = 'articles', $message = '' )
	{
		if (
			$area == 'articles' && !$this->params->articles_enable ||
			$area == 'components' && !$this->params->components_enable ||
			$area == 'other' && !$this->params->other_enable
		) {
			$message = JText::_( 'MA_OUTPUT_REMOVED_NOT_ENABLED' );
		}

		if ( preg_match( '#\{'.$this->params->tags.'#', $string ) ) {
			jimport('joomla.application.module.helper');
			JPluginHelper::importPlugin( 'content' );

			$regex = $this->params->regex;
			if ( @preg_match( $regex.'u', $string ) ) {
				$regex .= 'u';
			}

			$matches = array();
			$count = 0;
			while ( $count++ < 10 && preg_match( '#\{'.$this->params->tags.'#', $string ) && preg_match_all( $regex, $string, $matches, PREG_SET_ORDER ) > 0 ) {
					foreach ( $matches as $match ) {
					$this->processMatch( $string, $match, $message );
				}
				$matches = array();
			}
		}
	}

	function processMatch( &$string, &$match, &$message )
	{
		$html = '';
		if ( $message != '' ) {
			if ( $this->params->place_comments ) {
				$html = $this->params->message_start.$message.$this->params->message_end;
			}
		} else {
			/*
			p_start		= $match['1'];
			br1a		= $match['2'];
			div_start	= $match['3'];
			br2a		= $match['4'];
			type		= $match['5'];
			id			= $match['6'];
			vars		= $match['7'];
			br2a		= $match['8'];
			div_end		= $match['9'];
			br2b		= $match['10'];
			p_end		= $match['11'];
			*/

			$type = trim( $match['5'] );
			$id = trim( $match['6'] );

			$style = $this->params->style;
			$overrides = array();

			if ( $this->params->override_style || $this->params->override_settings ) {
				$vars = str_replace( '\|', '[:MA_BAR:]', trim( $match['7'] ) );
				$vars = explode( '|', $vars );
				foreach ( $vars as $var ) {
					$var = trim( str_replace( '[:MA_BAR:]', '|', $var ) );
					if ( !$var ) {
						continue;
					}
					if ( strpos( $var, '=' ) === false ) {
						if ( $this->params->override_style ) {
							$style = $var;
						}
					} else {
						if ( $this->params->override_settings && $type == $this->params->module_tag ) {
							list( $key, $val ) = explode( '=', $var, 2 );
							$val = str_replace( array( '\{', '\}' ), array( '{', '}' ), $val );
							$overrides[$key] = $val;
						}
					}
				}
			}

			if ( $type == $this->params->module_tag ) {
				// module
				$html = $this->processModule( $id, $style, $overrides );
			} else {
				// module position
				$html = $this->processPosition( $id, $style );
			}

			if ( $match['1'] && $match['11'] ) {
				$match['1'] = '';
				$match['11'] = '';
			}

			$html = $match['2'].$match['4'].$html.$match['8'].$match['10'];

			if ( $match['3'] ) {
				$extra = trim( preg_replace( '#\{div(.*)\}#si', '\1', $match['3'] ) );
				$div = '';
				if ( $extra ) {
					$extra = explode( '|', $extra );
					$extras = new stdClass();
					foreach ( $extra as $e ) {
						if ( !( strpos( $e, ':' ) === false ) ) {
							list( $key, $val ) = explode( ':', $e, 2 );
							$extras->$key = $val;
						}
					}
					if ( isset( $extras->class ) ) {
						$div .= 'class="'.$extras->class.'"';
					}

					$style = array();
					if ( isset( $extras->width ) ) {
						if ( is_numeric( $extras->width ) ) {
							$extras->width .= 'px';
						}
						$style[] = 'width:'.$extras->width;
					}
					if ( isset( $extras->height ) ) {
						if ( is_numeric( $extras->height ) ) {
							$extras->height .= 'px';
						}
						$style[] = 'height:'.$extras->height;
					}
					if ( isset( $extras->align ) ) {
						$style[] = 'float:'.$extras->align;
					} else if ( isset( $extras->float ) ) {
						$style[] = 'float:'.$extras->float;
					}

					if ( !empty( $style ) ) {
						$div .= ' style="'.implode( ';', $style ).';"';
					}
				}
				$html = trim( '<div '.trim( $div ) ).'>'.$html.'</div>';

				$html = $match['11'].$html.$match['1'];
			} else {
				$html = $match['1'].$html.$match['11'];
			}

			$html = preg_replace( '#((?:<p(?: [^>]*)?>\s*)?)((?:<br ?/?>)?\s*<div(?: [^>]*)?>.*?</div>\s*(?:<br ?/?>)?)((?:\s*</p>)?)#', '\3\2\1', $html );
			$html = preg_replace( '#(<p(?: [^>]*)?>\s*)<p(?: [^>]*)?>#', '\1', $html );
			$html = preg_replace( '#(</p>\s*)</p>#', '\1', $html );
		}

		if ( $this->params->place_comments ) {
			$html = $this->params->comment_start.$html.$this->params->comment_end;
		}

		$string = str_replace( $match['0'], $html, $string );
		unset( $match );
	}

	function processPosition( $position, $style = 'none' )
	{
		$document	=& JFactory::getDocument();
		$renderer	= $document->loadRenderer( 'module' );

		$html = '';
		foreach ( JModuleHelper::getModules( $position ) as $mod ) {
			$html .= $renderer->render( $mod, array( 'style'=>$style ) );
		}
		return $html;
	}

	function processModule( $module, $style = 'none', $overrides = array() )
	{
		$db =& JFactory::getDBO();

		$where = ' AND ( title='.$db->quote(  NoNumberElementsFunctions::html_entity_decoder( $module ) ).'';
		if ( is_numeric( $module ) ) {
			$where .= ' OR id='.$module;
		}
		$where .=  ' ) ';
		if ( !$this->params->ignore_state ) {
			$where .= ' AND published = 1';
		}

		$query =
			'SELECT *'
			.' FROM #__modules'
			.' WHERE client_id = 0'
			.' AND access '.( defined( '_JACL' ) ? 'IN ('.$this->params->aid_jaclplus.')' : '<= '. (int) $this->params->aid )
			.$where
			.' ORDER BY ordering'
			.' LIMIT 1';

		$db->setQuery( $query );
		$module = $db->loadObject();

		$html = '';
		if ( $module ) {
			//determine if this is a custom module
			$module->user = ( substr( $module->module, 0, 4 ) == 'mod_' ) ? 0 : 1;

			// set style
			$module->style = $style;

			// override module settings
			$params = '';
			foreach ( $overrides as $key => $val ) {
				$params .= "\n".$key.'='.$val;
			}
			if ( $params != '' ) {
				$module->params = trim( $module->params ).$params."\n\n";
			}

			$document = clone( JFactory::getDocument() );
			$document->_type = 'html';
			$renderer = $document->loadRenderer( 'module' );
			$html = $renderer->render( $module, array( 'style'=>$style ) );
		}
		return $html;
	}

		/*
	 * Protect input and text area's
	 */
	function protect( &$string )
	{
		if (	in_array( JRequest::getCmd( 'task' ), array( 'edit' ) )
			||	in_array( JRequest::getCmd( 'view' ), array( 'edit', 'form' ) )
			||	in_array( JRequest::getCmd( 'layout' ), array( 'edit', 'form', 'write' ) )
			||	in_array( JRequest::getCmd( 'option' ), array( 'com_contentsubmit', 'com_cckjseblod' ) )
		) {
			// Protect complete adminForm (to prevent articles from being created when editing articles and such)
			$unprotected = '{'.$this->params->module_tag;
			$protected = $this->protectStr( $unprotected );
			$string = preg_replace( '#(<'.'form [^>]*(id|name)="adminForm")#si', '<!-- TMP_START_EDITOR -->\1', $string );
			$string = explode( '<!-- TMP_START_EDITOR -->', $string );
			foreach ( $string as $i => $str ) {
				if ( !empty( $str ) != '' && fmod( $i, 2 ) ) {
					if ( !( strpos( $str, $unprotected ) === false ) ) {
						$str = explode( '</form>', $str, 2 );
						$str['0'] = str_replace( $unprotected, $protected, $str['0'] );
						$string[$i] = implode( '</form>', $str );
					}
				}
			}
			$string = implode( '', $string );
		}
	}

	function unprotect( &$string )
	{
		$string = str_replace( $this->protectStr( '{'.$this->params->module_tag ), '{'.$this->params->module_tag, $string );
	}

	function protectStr( $string )
	{
		$string = base64_encode( $string );
		return $string;
	}

	function cleanLeftoverJunk( &$str )
	{
		if ( !(strpos( $str, '{/'.$this->params->module_tag.'}' ) === false ) ) {
			$regex = $this->params->regex;
			if ( @preg_match( $regex.'u', $str ) ) {
				$regex .= 'u';
			}
			if( preg_match( $regex, $str ) ) {
				$str = preg_replace( $regex, '', $str );
			}
		}
		$str = preg_replace( '#<\!-- (START|END): MODA_[^>]* -->#', '', $str );
		if ( !$this->params->place_comments ) {
			$str = str_replace( array(
					$this->params->comment_start, $this->params->comment_end,
					htmlentities( $this->params->comment_start ), htmlentities( $this->params->comment_end ),
					urlencode( $this->params->comment_start ), urlencode( $this->params->comment_end )
				), '', $str );
			$str = preg_replace( '#'.preg_quote( $this->params->message_start, '#' ).'.*?'.preg_quote( $this->params->message_end, '#' ).'#', '', $str );
		}
	}
}