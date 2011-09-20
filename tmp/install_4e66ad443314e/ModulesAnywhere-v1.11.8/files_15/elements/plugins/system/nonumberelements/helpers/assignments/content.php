<?php
/**
 * NoNumber! Elements Helper File: Assignments: Content
 *
 * @package     NoNumber! Elements
 * @version     2.9.1
 *
 * @author      Peter van Westen <peter@nonumber.nl>
 * @link        http://www.nonumber.nl
 * @copyright   Copyright © 2011 NoNumber! All Rights Reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die();

/**
* Assignments: Content
*/
class NoNumberElementsAssignmentsContent
{
	var $_version = '2.9.1';

	/**
	 * passSecsCats
	 * @param <object> $params
	 * inc_children
	 * inc_sections
	 * inc_categories
	 * inc_articles
	 * inc_others
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passSecsCats( &$main, &$params, $selection = array(), $assignment = 'all', $article = 0 )
	{
		// components that use the com_content secs/cats
		$components = array( 'com_content', 'com_contentsubmit' );
		if ( !in_array( $main->_params->option, $components ) ) {
			return ( $assignment == 'exclude' );
		}

		$selection = $main->makeArray( $selection );

		if ( empty( $selection ) ) {
			return ( $assignment == 'exclude' );
		}

		$pass = 0;

		$inc = (
				$main->_params->option == 'com_contentsubmit'
			||	( $params->inc_sections && $main->_params->option == 'com_content' && $main->_params->view == 'section' )
			||	( $params->inc_categories && $main->_params->option == 'com_content' && $main->_params->view == 'category' )
			||	( $params->inc_articles && $main->_params->option == 'com_content' && ( $main->_params->view == '' || $main->_params->view == 'article' ) )
			||	( $params->inc_others && !( $main->_params->option == 'com_content' && ( $main->_params->view == 'section' || $main->_params->view == 'category' || $main->_params->view == '' || $main->_params->view == 'article' ) ) )
		);

		if ( $inc ) {
			$secs = array();
			$cats = array();
			foreach ( $selection as $seccat ) {
				$seccat = explode( ':', str_replace( '.', ':', $seccat ) );
				if ( count( $seccat ) > 1 ) {
					// category
					$cats[] = $seccat['1'];
				} else {
					// section
					$secs[] = $seccat['0'];
					if ( $params->inc_children ) {
						$query = 'SELECT id'
							.' FROM #__categories'
							.' WHERE section = '.(int) $seccat['0'];
						$main->_db->setQuery( $query );
						$categories = $main->_db->loadResultArray();
						if ( !is_array( $categories ) ) {
							$categories = array();
						}
						$cats = array_merge( $cats, $categories );
					}
				}
			}

			if( $main->_params->option == 'com_contentsubmit' ) {
				// Content Submit
				$contentsubmit_params = new ContentsubmitModelArticle();
				if ( in_array( $contentsubmit_params->_id, $cats ) ) {
					$pass = 1;
				}
			} else {
				if ( $params->inc_others && !( $main->_params->option == 'com_content' && ( $main->_params->view == 'section' || $main->_params->view == 'category' || $main->_params->view == 'article' ) ) ) {
					if ( $article ) {
						if ( !isset( $article->id ) ) {
							if ( isset( $article->slug ) ) {
								$article->id = (int) $article->slug;
							}
						}
						if ( !isset( $article->catid ) ) {
							if ( isset( $article->catslug ) ) {
								$article->catid = (int) $article->catslug;
							}
						}
						$main->_params->id = $article->id;
						$main->_params->view = 'article';
					}
				}

				switch( $main->_params->view ) {
					case 'section':
						$pass = in_array( $main->_params->id, $secs );
						break;
					case 'category':
						$pass = in_array( $main->_params->id, $cats );
						break;
					default:
						if ( !$article ) {
							$article =& JTable::getInstance( 'content' );
							$article->load( $main->_params->id );
						}
						if ( $article->catid ) {
							$pass = in_array( $article->catid, $cats );
						} else {
							$catid = JRequest::getInt( 'catid' );
							$filter_sectionid = JRequest::getInt( 'filter_sectionid' );
							if ( $catid && $catid !== -1 ) {
								$pass = in_array( $catid, $cats );
							} else if ( $filter_sectionid !== '' &&  $filter_sectionid !== -1 ) {
								$pass = in_array( $filter_sectionid, $secs );
							}
						}
						break;
				}
			}
		}

		if ( $pass ) {
			return ( $assignment == 'include' );
		} else {
			return ( $assignment == 'exclude' );
		}
	}

	/**
	 * passArticles
	 * @param <object> $params
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passArticles( $main, &$params, $selection = array(), $assignment = 'all', $article = 0 )
	{
		if (	!$main->_params->id
			||	!(		( $main->_params->option == 'com_content' && $main->_params->view == 'article' )
					||	( $main->_params->option == 'com_flexicontent' && $main->_params->view == 'items' )
				)
		) {
			return ( $assignment == 'exclude' );
		}

		$pass = 0;

		if ( $selection && !is_array( $selection ) ) {
			if ( !( strpos( $selection, '|' ) === false ) ) {
				$selection = explode( '|', $selection );
			} else {
				$selection = explode( ',', $selection );
			}
		}
		if ( !empty( $selection ) ) {
			$pass = in_array( $main->_params->id, $selection );
		}

		if ( $params->keywords && !is_array( $params->keywords ) ) {
			$params->keywords = explode( ',', $params->keywords );
		}
		if ( !empty( $params->keywords ) ) {
			$pass = 0;
			if ( !$article ) {
				require_once JPATH_SITE.DS.'components'.DS.'com_content'.DS.'models'.DS.'article.php';
				$model = JModel::getInstance( 'article', 'contentModel' );
				$model->setId( $main->_params->id );
				$article = $model->getArticle();
			}
			if ( isset( $article->metakey ) && $article->metakey ) {
				$keywords = explode( ',', $article->metakey );
				foreach( $keywords as $keyword ) {
					if ( $keyword && in_array( trim( $keyword ), $params->keywords ) ) {
						$pass = 1;
						break;
					}
				}
				if ( !$pass ) {
					$keywords = explode( ',', str_replace( ' ', ',', $article->metakey ) );
					foreach( $keywords as $keyword ) {
						if ( $keyword && in_array( trim( $keyword ), $params->keywords ) ) {
							$pass = 1;
							break;
						}
					}
				}
			}
		}

		if ( $pass ) {
			return ( $assignment == 'include' );
		} else {
			return ( $assignment == 'exclude' );
		}
	}
}