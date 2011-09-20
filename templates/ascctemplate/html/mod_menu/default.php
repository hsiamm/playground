<?php
/**
 * @version		$Id: default.php 20983 2011-03-17 16:19:45Z chdemko $
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access.
defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
?>

<ul id="menunav" class="dropdown dropdown-horizontal">
    <?php
    foreach ($list as $i => &$item) :
        $id = '';
        $class = '';
        if ($item->id == $active_id) {
            $class .= 'current ';
        }

        if ($item->parent && ($item->type == 'alias' &&
                in_array($item->params->get('aliasoptions'), $path)
                || in_array($item->id, $path))) {
            $class .= 'active ';
        }

        // adds the id menunav if the top menuitem
        if ($item->parent) {
            $id .= 'menunav';
        }

        if (!empty($class)) {
            $class = ' class="' . trim($class) . '"';
        }

        if (!empty($id)) {
            $id = ' id="' . trim($id) . '"';
        }

        // adds an id or class if necessary
        echo '<li ' . $id . '' . $class . '>';

        // Render the menu item.
        switch ($item->type) :
            case 'separator':
            case 'url':
            case 'component':
                require JModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
                break;

            default:
                require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
                break;
        endswitch;

        // The next item is deeper.
        if ($item->deeper) {
            echo '<ul id="drop">';
            echo '<div class="rule_menu"></div>';
        }
        // The next item is shallower.
        else if ($item->shallower) {
            echo '</li>';
            echo str_repeat('</ul></li>', $item->level_diff);
        }
        // The next item is on the same level.
        else {
            echo '</li>';
        }
    endforeach;
    ?></ul>
