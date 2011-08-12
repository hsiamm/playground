<?php
/*
* File: tmpl/default.php
* File Description: This file handles the view of the tweets, and displays them.
*/

defined('_JEXEC') or die ('Direct Access is forbidden, shame on you');

function timeDifference($timestamp, $params)
{
	$a = $params->get('agoorder','end');
	$d = time() - $timestamp;
	if ($d < 60)
		return (($a=='first')?JText::_('ago'):$d)." ". (($d==1)? JText::_('second') : JText::_('seconds'))." " . (($a=='end')?JText::_('ago'):$d);
	else
	{
		$d = floor($d / 60);
		if($d < 60)
			return (($a=='first')?JText::_('ago'):$d)." ".(($d==1)? JText::_('minute') : JText::_('minutes') )." " . (($a=='end')?JText::_('ago'):$d);
		else
		{
			$d = floor($d / 60);
			if($d < 24)
				return (($a=='first')?JText::_('ago'):$d)." ".(($d==1)? JText::_('hour') : JText::_('hours'))." " . (($a=='end')?JText::_('ago'):$d);
			else
			{
				$d = floor($d / 24);
				if($d < 7)
					return (($a=='first')?JText::_('ago'):$d)." ".(($d==1)? JText::_('day') : JText::_('days'))." " . (($a=='end')?JText::_('ago'):$d);
				else
				{
					$d = floor($d / 7);
					if($d < 4)
						return (($a=='first')?JText::_('ago'):$d)." ".(($d==1)? JText::_('week'): JText::_('weeks'))." " . (($a=='end')?JText::_('ago'):$d);
				}//Week
			}//Day
		}//Hour
	}//Minute
}

if ($tweets['error'])
{
	echo "<div class=\"message\">".$tweets."</div>";
}
else
{
$date = $params->get('date','ago');
$format = $params->get('format','d.m.y H\:m');

?>
<?php if ($params->get('beforetext','') !== '') : ?>
<div class="beforeTweets">
<?php print $params->get('beforetext',''); ?>
</div>
<?php endif; ?>
<ul class="tweets">

<?php
$i = 0;
foreach ($tweets as $tweetData)
{
	if ($i != 0) {
		$tweet = $tweetData['tweet'];
		$pubDate = strtotime($tweetData['pubDate']);
		$link = $tweetData['link'];

		$exp = "/@(.+?)\b/";
		$tweet = preg_replace($exp, "<a href=\"http://twitter.com/$1\">@$1</a>", $tweet);  
		
		if ($date == "time") 
		{ 
			$pub = date($format, $pubDate); 
		} 
		else 
		{ 
			$pub = timeDifference($pubDate,$params); 
		}
?>

<li><?php echo $tweet; ?><br /><span class="tweet_time"><?php echo $pub; ?> - <a href="<?php echo $link; ?>"<?php echo ($params->get('linktype') == 'blank') ? ' target="_blank"' : ''; ?>><?php echo JText::_('view'); ?> &raquo;</a></li>

<?php
	}
	else { $i++; }
}
?>

</ul>
<?php if ($params->get('aftertext','') !== '') : ?>
<div class="afterTweets">
<?php print $params->get('aftertext',''); ?>
</div>
<?php endif; ?>
<?php
}
?>