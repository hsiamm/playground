/**
 * Main JavaScript file (MooTools 1.2 compatible)
 *
 * @package     NoNumber! Elements
 * @version     2.8.4
 *
 * @author      Peter van Westen <peter@nonumber.nl>
 * @link        http://www.nonumber.nl
 * @copyright   Copyright © 2011 NoNumber! All Rights Reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

/* File moved... this is for backward compatibility */
var all_scripts = document.getElementsByTagName("script");
if ( all_scripts.length ) {
	nn_script_root = all_scripts[all_scripts.length-1].src.replace( /[^\/]*\.js$/, '' );
	document.write('<script src="'+nn_script_root+'script.js" type="text/JavaScript"><\/script>');
}