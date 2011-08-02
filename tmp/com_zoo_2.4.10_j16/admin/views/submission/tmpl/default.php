<?php 
/**
* @package   com_zoo Component
* @file      default.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<form id="submissions-default" action="index.php" method="post" name="adminForm" accept-charset="utf-8">

	<?php echo $this->partial('menu'); ?>

	<div class="box-bottom">

		<?php if(count($this->submissions) > 0) : ?>

		<table class="list stripe">
			<thead>
				<tr>
					<th class="checkbox">
						<input type="checkbox" class="check-all" />
					</th>
					<th class="name" colspan="2">
						<?php echo $this->app->html->_('grid.sort', 'Name', 'name', @$this->lists['order_Dir'], @$this->lists['order']); ?>
					</th>
					<th class="types">
						<?php echo JText::_('Submittable Types'); ?>
					</th>
					<th class="trusted">
						<?php echo JText::_('Trusted Mode'); ?>
					</th>
					<th class="published">
						<?php echo $this->app->html->_('grid.sort', 'Published', 'state', @$this->lists['order_Dir'], @$this->lists['order']); ?>
					</th>
					<th class="access">
						<?php echo $this->app->html->_('grid.sort', 'Access', 'access', @$this->lists['order_Dir'], @$this->lists['order']); ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<?php
				for ($i=0, $n=count($this->submissions); $i < $n; $i++) :

					$row = $this->submissions[$i];

					$img 	= $row->state ? 'tick.png' : 'publish_x.png';
					$task 	= $row->state ? 'unpublish' : 'publish';
					$alt 	= $row->state ? JText::_('Published') : JText::_('Unpublished');
					$action = $row->state ? JText::_('Unpublish submission') : JText::_('Publish submission');

					$types = array_map(create_function('$type', 'return $type->name;'), $row->getSubmittableTypes());

					// access
					$group_access = isset($this->groups[$row->access]) ? JText::_($this->groups[$row->access]->name) : '';

					// trusted mode
					$trusted_mode     = (int) $row->isInTrustedMode();
					$trusted_mode_img = $trusted_mode ? 'tick.png' : 'publish_x.png';
					$trusted_mode_alt = $trusted_mode ? JText::_('Trusted Mode enabled') : JText::_('Trusted Mode disabled');

					?>
					<tr>
						<td class="checkbox">
							<input type="checkbox" name="cid[]" value="<?php echo $row->id; ?>" />
						</td>
						<td class="icon"></td>
						<td class="name">
							<span class="editlinktip hasTip" title="<?php echo JText::_('Edit Submission');?>::<?php echo $row->name; ?>">
								<a href="<?php echo $this->app->link(array('controller' => $this->controller, 'task' => 'edit', 'cid[]' => $row->id));  ?>"><?php echo $row->name; ?></a>
							</span>
						</td>
						<td class="types">
							<?php if (count($types)) : ?>
							<?php echo implode(', ', $types); ?>
							<?php else: ?>
							<span><?php echo JText::_('YOU WILL NEED AT LEAST ONE SUBMITTABLE TYPE FOR THIS SUBMISSION TO WORK'); ?></span>
							<?php endif; ?>
						</td>
						<td class="trusted">
							<a href="#" rel="task-<?php echo $trusted_mode ? 'disabletrustedmode' : 'enabletrustedmode' ?>" title="<?php echo JText::_('Enable/Disable Trusted Mode');?>" <?php echo $row->access == 0 ? 'disabled="disabled"' : ''; ?>>
								<img src="<?php echo $this->app->path->url('assets:images/'.$trusted_mode_img); ?>" border="0" alt="<?php echo $trusted_mode_alt; ?>" />
							</a>
						</td>
						<td class="published">
							<a href="#" rel="task-<?php echo $task; ?>" title="<?php echo $action; ?>">
								<img src="<?php echo $this->app->path->url('assets:images/'.$img); ?>" border="0" alt="<?php echo $alt; ?>" />
							</a>
						</td>
						<td class="access">
							<span><?php echo $group_access; ?></span>
						</td>
					</tr>
				<?php endfor; ?>
			</tbody>
		</table>

		<?php else :

				$title   = JText::_('NO_SUBMISSIONS_YET').'!';
				$message = JText::_('SUBMISSION_MANAGER_DESCRIPTION');
				echo $this->partial('message', compact('title', 'message'));

			endif;
		?>

	</div>

	<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
	<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo $this->app->html->_('form.token'); ?>

</form>

<?php echo ZOO_COPYRIGHT; ?>