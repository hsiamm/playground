<h4>RECENTLY ON TWITTER &nbsp;//&nbsp;

    <?php

    function twitterify($ret) {
        $ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
        $ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
        $ret = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $ret);
        $ret = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $ret);
        return $ret;
    }

    $cache = JPATH_BASE . DS . 'cache';
    if (!is_writable($cache))
        echo 'Cache folder is unwriteable. Solution: chmod 755 ' . $cache;

    $user = 'theaustinstone';
    $quantity = '5';
    //$cachetime = '1';
    $cachetime = '30' * 60;
    $tweetURL = "https://api.twitter.com/1/statuses/user_timeline.xml?include_entities=false&include_rts=true&exclude_replies=true&screen_name=" . $user . "&count=" . $quantity;
    $cachefile = $user . ".xml";
    $cachepathfile = $cache . DS . $cachefile;

    if (!file_exists($cachepathfile) || (time() - $cachetime) > filemtime($cachepathfile)) {
        $ch = curl_init($tweetURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        echo "*";

        $file = $response;
        if ($file)
            file_put_contents($cachepathfile, $file);
        //else
            //echo "Unable to get latest tweets at this time. ";
    }

    $twitter = simplexml_load_file($cachepathfile);
    print $twitter;

    if (!$twitter)
        echo "Check back another time, we're down!";

    //$xml_obj = new SimpleXMLElement($twitter);

    foreach ($twitter->status as $status) {
        echo twitterify($status->text);
        return; //only print first one
    }
    ?>

</h4>