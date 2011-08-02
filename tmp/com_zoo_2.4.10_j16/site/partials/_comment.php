<?php
/**
* @package   com_zoo Component
* @file      _comment.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// set author name
$author->name = $author->name ? $author->name : JText::_('Anonymous');

?>
<li>
	<div id="comment-<?php echo $comment->id; ?>" class="comment <?php if ($author->isJoomlaAdmin()) echo 'comment-byadmin'; ?>">

		<div class="comment-head">

			<?php if ($params->get('avatar', 0)) : ?>
			<div class="avatar">
				<?php echo $author->getAvatar(50); ?>
			</div>
			<?php endif; ?>

			<?php if ($author->url) : ?>
				<div class="author">
					<a href="<?php echo JRoute::_($author->url); ?>" title="<?php echo $author->url; ?>" rel="nofollow"><?php echo $author->name; ?></a>
				</div>
			<?php else: ?>
				<div class="author"><?php echo $author->name; ?></div>
			<?php endif; ?>

			<div class="meta">
				<?php echo $this->app->html->_('date', $comment->created, $this->app->date->format(JText::_('DATE_FORMAT_COMMENTS')), $this->app->date->getOffset()); ?>
				| <a class="permalink" href="#comment-<?php echo $comment->id; ?>">#</a>
			</div>

		</div>

		<div class="comment-body">

			<p class="content">
				<?php echo $this->app->comment->filterContentOutput($comment->content); ?>
			</p>

			<?php if ($comment->getItem()->isCommentsEnabled()) : ?>
				<div class="reply"><a href="#" rel="nofollow"><?php echo JText::_('Reply'); ?></a></div>
			<?php endif; ?>

			<?php if ($comment->state != Comment::STATE_APPROVED) : ?>
				<div class="moderation"><?php echo JText::_('COMMENT_AWAITING_MODERATION'); ?></div>
			<?php endif; ?>

		</div>

	</div>

	<ul class="level<?php echo ++$level; ?>">
		<?php
		foreach ($comment->getChildren() as $comment) {
			echo $this->partial('comment', array('level' => $level, 'comment' => $comment, 'author' => $comment->getAuthor(), 'params' => $params));
		}
		?>
	</ul>

</li>