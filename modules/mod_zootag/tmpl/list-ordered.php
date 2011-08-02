<?php
/**
* @package   ZOO Tag
* @file      list-ordered.php
* @version   2.4.1
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// include css
$zoo->document->addStylesheet('modules:mod_zootag/tmpl/list-ordered/style.css');

$count = count($tags);

?>

<div class="zoo-tag list-ordered">

	<?php if ($count) : ?>

		<ol>
			<?php $i = 0; foreach ($tags as $tag) : ?>
			<li class="weight<?php echo $tag->weight; ?> <?php if ($i % 2 == 0) { echo 'odd'; } else { echo 'even'; } ?>">
				<a href="<?php echo JRoute::_($tag->href); ?>"><?php echo $tag->name; ?></a>
			</li>
			<?php $i++; endforeach; ?>
		</ol>
	
	<?php else : ?>
		<?php echo JText::_('COM_ZOO_NO_TAGS_FOUND'); ?>
	<?php endif; ?>
	
</div>
