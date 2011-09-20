<?php
/**
 * Language Include File (English)
 * Can overrule set variables used in different elements
 *
 * @package     Modules Anywhere
 * @version     1.11.8
 *
 * @author      Peter van Westen <peter@nonumber.nl>
 * @link        http://www.nonumber.nl
 * @copyright   Copyright © 2011 NoNumber! All Rights Reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @translation Peter van Westen <peter@nonumber.nl> NoNumber!
 */

// No direct access
defined( '_JEXEC' ) or die();

/**
 * Variables that can be overruled:
 * $image
 * $title
 * $description
 * $help
 */

$description = '
	<p>Easily place modules anywhere in your site.</p>
	<p>You can place modules using the syntax:<br />
	Using the name of the module: <span class="nn_code">{module Main Menu}</span><br />
	Using the id of the module: <span class="nn_code">{module 3}</span></p>
	<p>You can also place complete module positions using the syntax:<br />
	<span class="nn_code">{modulepos mainmenu}</span></p>
	<p>To use another style than the default, you can do this:<br />
	<span class="nn_code">{module Main Menu|horz}</span><br />
	You can choose from: table, horz, xhtml, rounded, none (and any extra style your template supports).</p>
';