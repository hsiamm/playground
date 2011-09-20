<?php
/**
 * Plugin Helper File
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
* ...
*/
class plgSystemNoNumberElementsHelper
{
	function __construct()
	{
		$mainframe =& JFactory::getApplication();

		$url = JRequest::getVar( 'url' );
		$options = JRequest::getVar( 'url_options', array(), 'post', 'array' );
		$func = new plgSystemNoNumberElementsHelperFunctions;

		if ( $url ) {
			echo $func->getByUrl( $url, $options );
			exit();
		}

		$file = JRequest::getVar( 'file' );

		// only allow files that have .inc.php in the file name
		if ( !$file || ( strpos( $file, '.inc.php' ) === false ) ) {
			echo JText::_( 'Access Denied' );
			exit();
		}

		$folder = JRequest::getVar( 'folder' );
		jimport( 'joomla.filesystem.file' );


		if ( $mainframe->isSite() && !JRequest::getCmd( 'usetemplate' ) ) {
			$mainframe->setTemplate( 'system' );
		}
		$_REQUEST['tmpl'] = 'component';
		JRequest::setVar( 'option', '1' );

		$mainframe->set( '_messageQueue', '' );

		$path = JPATH_SITE;
		if ( $folder ) {
			$path .= DS.implode( DS, explode( '.', $folder ) );
		}
		$file = $path.DS.$file;

		$html = '';
		if ( JFile::exists( $file ) ) {
			ob_start();
				include $file;
				$html = ob_get_contents();
			ob_end_clean();
		}

		$document =& JFactory::getDocument();
		$document->setBuffer( $html, 'component' );
		$document->addStyleSheet( JURI::root( true ).'/templates/system/css/system.css' );
		$document->addStyleSheet( JURI::root( true ).'/plugins/system/nonumberelements/css/default.css' );
		$document->addScript( JURI::root(true).'/includes/js/joomla.javascript.js' );

		$mainframe->render();

		echo JResponse::toString( $mainframe->getCfg( 'gzip' ) );

		exit();
	}
}
class plgSystemNoNumberElementsHelperFunctions
{
	function getByUrl( $url, $options = array() ) {
		if ( substr( $url, 0, 4 ) != 'http' ) {
			$url = 'http://'.$url;
		}

		$html = '';
		if ( function_exists( 'curl_init' ) ) {
			$html = $this->curl( $url, $options );
		} else {
			$file = @fopen( $url, 'r' );
			if ( $file ) {
				$html = array();
				while ( !feof( $file ) ) {
					$html[] = fgets( $file, 1024 );
				}
				$html = implode( '', $html );
			}
		}

		return $html;
	}

	function curl( $url, $options = array() )
	{
		$ch = curl_init( $url );
		$ch_options = array (
			CURLOPT_URL => $url,
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 3,
			CURLOPT_USERAGENT => 'some crazy browser'
		);

		if ( !empty( $options ) ) {
			$curl_opts = $this->getCurlOpts();
			foreach ( $options as $key => $option ) {
				if ( is_numeric( $key ) ) {
					$ch_options[$key] = $option;
				} else if ( isset( $curl_opts[$key] ) ) {
					$ch_options[$curl_opts[$key]] = $option;
				}
			}
		}

		curl_setopt_array( $ch, $ch_options );

		//follow on location problems
		if ( ini_get( 'open_basedir' ) == '' && ini_get( 'safe_mode' ) != 'On' ) {
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
			$html = curl_exec( $ch );
		}else{
			$html = $this->curl_redir_exec( $ch );
		}
		curl_close( $ch );
		return $html;
	}

	function curl_redir_exec( $ch )
	{
		static $curl_loops = 0;
		static $curl_max_loops = 20;

		if ( $curl_loops++ >= $curl_max_loops ) {
			$curl_loops = 0;
			return false;
		}

		curl_setopt( $ch, CURLOPT_HEADER, true );
		$data = curl_exec( $ch );

		list( $header, $data ) = explode( "\n\n", str_replace( "\r", '', $data ), 2 );
		$http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

		if ( $http_code == 301 || $http_code == 302 ) {
			$matches = array();
			preg_match( '/Location:(.*?)\n/', $header, $matches );
			$url = @parse_url( trim( array_pop( $matches ) ) );
			if (!$url) {
				//couldn't process the url to redirect to
				$curl_loops = 0;
				return $data;
			}
			$last_url = parse_url( curl_getinfo( $ch, CURLINFO_EFFECTIVE_URL ) );
			if ( !$url['scheme'] ) {
				$url['scheme'] = $last_url['scheme'];
			}
			if ( !$url['host'] ) {
				$url['host'] = $last_url['host'];
			}
			if ( !$url['path'] ) {
				$url['path'] = $last_url['path'];
			}
			$new_url = $url['scheme'].'://'.$url['host'].$url['path'].( $url['query'] ? '?'.$url['query'] : '' );
			curl_setopt( $ch, CURLOPT_URL, $new_url );
			return $this->curl_redir_exec( $ch );
		} else {
			$curl_loops = 0;
			return $data;
		}
	}

	function getCurlOpts() {
		return array(
			'CURLOPT_AUTOREFERER' => 58,
			'CURLOPT_BINARYTRANSFER' => 19914,
			'CURLOPT_BUFFERSIZE' => 98,
			'CURLOPT_CAINFO' => 10065,
			'CURLOPT_CAPATH' => 10097,
			'CURLOPT_CLOSEPOLICY' => 72,
			'CURLOPT_CONNECTTIMEOUT' => 78,
			'CURLOPT_CONNECTTIMEOUT_MS' => 156,
			'CURLOPT_COOKIE' => 10022,
			'CURLOPT_COOKIEFILE' => 10031,
			'CURLOPT_COOKIEJAR' => 10082,
			'CURLOPT_COOKIESESSION' => 96,
			'CURLOPT_CRLF' => 27,
			'CURLOPT_CUSTOMREQUEST' => 10036,
			'CURLOPT_DNS_CACHE_TIMEOUT' => 92,
			'CURLOPT_EGDSOCKET' => 10077,
			'CURLOPT_ENCODING' => 10102,
			'CURLOPT_FAILONERROR' => 45,
			'CURLOPT_FILE' => 10001,
			'CURLOPT_FILETIME' => 69,
			'CURLOPT_FOLLOWLOCATION' => 52,
			'CURLOPT_FORBID_REUSE' => 75,
			'CURLOPT_FRESH_CONNECT' => 74,
			'CURLOPT_FTPAPPEND' => 50,
			'CURLOPT_FTPLISTONLY' => 48,
			'CURLOPT_FTPPORT' => 10017,
			'CURLOPT_FTP_USE_EPRT' => 106,
			'CURLOPT_FTP_USE_EPSV' => 85,
			'CURLOPT_HEADER' => 42,
			'CURLOPT_HEADERFUNCTION' => 20079,
			'CURLOPT_HTTP200ALIASES' => 10104,
			'CURLOPT_HTTPGET' => 80,
			'CURLOPT_HTTPHEADER' => 10023,
			'CURLOPT_HTTPPROXYTUNNEL' => 61,
			'CURLOPT_HTTP_VERSION' => 84,
			'CURLOPT_INFILE' => 10009,
			'CURLOPT_INFILESIZE' => 14,
			'CURLOPT_INTERFACE' => 10062,
			'CURLOPT_KRB4LEVEL' => 10063,
			'CURLOPT_LOW_SPEED_LIMIT' => 19,
			'CURLOPT_LOW_SPEED_TIME' => 20,
			'CURLOPT_MAXCONNECTS' => 71,
			'CURLOPT_MAXREDIRS' => 68,
			'CURLOPT_NETRC' => 51,
			'CURLOPT_NOBODY' => 44,
			'CURLOPT_NOPROGRESS' => 43,
			'CURLOPT_NOSIGNAL' => 99,
			'CURLOPT_PORT' => 3,
			'CURLOPT_POST' => 47,
			'CURLOPT_POSTFIELDS' => 10015,
			'CURLOPT_POSTQUOTE' => 10039,
			'CURLOPT_PROXY' => 10004,
			'CURLOPT_PROXYPORT' => 59,
			'CURLOPT_PROXYTYPE' => 101,
			'CURLOPT_PROXYUSERPWD' => 10006,
			'CURLOPT_PUT' => 54,
			'CURLOPT_QUOTE' => 10028,
			'CURLOPT_RANDOM_FILE' => 10076,
			'CURLOPT_RANGE' => 10007,
			'CURLOPT_READDATA' => 10009,
			'CURLOPT_READFUNCTION' => 20012,
			'CURLOPT_REFERER' => 10016,
			'CURLOPT_RESUME_FROM' => 21,
			'CURLOPT_RETURNTRANSFER' => 19913,
			'CURLOPT_SSLCERT' => 10025,
			'CURLOPT_SSLCERTPASSWD' => 10026,
			'CURLOPT_SSLCERTTYPE' => 10086,
			'CURLOPT_SSLENGINE' => 10089,
			'CURLOPT_SSLENGINE_DEFAULT' => 90,
			'CURLOPT_SSLKEY' => 10087,
			'CURLOPT_SSLKEYPASSWD' => 10026,
			'CURLOPT_SSLKEYTYPE' => 10088,
			'CURLOPT_SSLVERSION' => 32,
			'CURLOPT_SSL_CIPHER_LIST' => 10083,
			'CURLOPT_SSL_VERIFYHOST' => 81,
			'CURLOPT_SSL_VERIFYPEER' => 64,
			'CURLOPT_STDERR' => 10037,
			'CURLOPT_TCP_NODELAY' => 121,
			'CURLOPT_TIMECONDITION' => 33,
			'CURLOPT_TIMEOUT' => 13,
			'CURLOPT_TIMEOUT_MS' => 155,
			'CURLOPT_TIMEVALUE' => 34,
			'CURLOPT_TRANSFERTEXT' => 53,
			'CURLOPT_UNRESTRICTED_AUTH' => 105,
			'CURLOPT_UPLOAD' => 46,
			'CURLOPT_URL' => 10002,
			'CURLOPT_USERAGENT' => 10018,
			'CURLOPT_USERPWD' => 10005,
			'CURLOPT_VERBOSE' => 41,
			'CURLOPT_WRITEFUNCTION' => 20011,
			'CURLOPT_WRITEHEADER' => 10029,
			'CURLOPT_DNS_USE_GLOBAL_CACHE' => 91,
		);
	}
}