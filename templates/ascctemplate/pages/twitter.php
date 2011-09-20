<h4>RECENTLY ON TWITTER &nbsp;//&nbsp;

    <?php

    function twitterify($ret) {
        $ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
        $ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
        $ret = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $ret);
        $ret = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $ret);
        return $ret;
    }

    $url = "https://api.twitter.com/1/statuses/user_timeline.xml?include_entities=false&include_rts=true&exclude_replies=true&screen_name=theaustinstone&count=5";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    //print $response;
    //echo "Twitter is down, sorry for the confusion!";

    $xml_obj = new SimpleXMLElement($response);

    foreach ($xml_obj->status as $status) {
        echo twitterify($status->text);
        return; //only print first one
    }
    ?>

</h4>