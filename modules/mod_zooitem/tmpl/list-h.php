<?php
/**
* @package   ZOO Item
* @file      list-h.php
* @version   2.4.2
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// include css
$zoo->document->addStylesheet('modules:mod_zooitem/tmpl/list-h/style.css');

// include js
$zoo->document->addScript('modules:mod_zooitem/mod_zooitem.js');

$count = count($items);

?>

<div class="zoo-item list-h">

	<?php if ($count) : ?>

		<ul>
			<?php $i = 0; foreach ($items as $item) : ?>
			<li class="width<?php echo intval(100 / $count);?> <?php if ($i % 2 == 0) { echo 'odd'; } else { echo 'even'; } ?>">
				<div class="match-height"><?php echo $renderer->render('item.'.$layout, compact('item', 'params')); ?></div>
			</li>
			<?php $i++; endforeach; ?>
		</ul>
		
	<?php else : ?>
		<?php echo JText::_('COM_ZOO_NO_ITEMS_FOUND'); ?>
	<?php endif; ?>
		
</div>

<script type="text/javascript">
	jQuery(function($) {
		$('div.zoo-item.list-h').each(function() { $(this).find('.match-height').matchHeight(); });
	});
</script>