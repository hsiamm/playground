<?php

function getResponseFromURL($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    //print $response;
    return $response;
}

date_default_timezone_set('America/Chicago');
$today = date('Y-m-d');
$yesterday = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")));
$today_long = date('M j, Y');
$yesterday_long = date('M j, Y', mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")));
$tomorrow_long = "Tomorrow";

$date = $today;
$date_long = $today_long;

$key = "IP";
$plan = "one-year-tract";
$format = "crossway-xml-1.0";
$type = array(1 => "Psalms & Wisdom", 2 => "History of Israel", 3 => "Chronicles & Prophets", 4 => "Gospels & Epistles");
?>

<script type="text/javascript">
    $(document).ready(function () {
        //initial shows
        $("#reap_today").show();
        $("#passage_today_1").show();
        $("#today").addClass("grey");
        $("#today_1").addClass("reap_current");
        
        // choose which scripture
        $('.reap').click(function() {
            var id = $(this).attr('id');
            $(".reap").removeClass("reap_current");
            $(".reap_scripture").hide();
  
            $("#passage_" + id).fadeIn("slow");
            $("#" + id).addClass("reap_current");
            return false;
        });
        
        
        //change day to grey
        $(".caption span").click(function(){
            $(".reap").removeClass("reap_current");
            $(".reap_days").hide();
            $(".reap_scripture").hide();
            $(".caption span").removeClass("grey");
            
            var id = $(this).attr('id');
            var label = document.getElementById("date_label");
            if (id == "yesterday")
                $(label).text("Verses for " + "<?php echo $yesterday_long; ?>");
            else if (id == "today")
                $(label).text("Verses for " + "<?php echo $today_long; ?>");
            else
                $(label).text("Verses for " + "<?php echo $tomorrow_long; ?>");
            
            $("#" + id).addClass("grey");
            $("#reap_" + id).fadeIn("slow");
            $("#passage_" + id + "_1").fadeIn("slow");
            $("#" + id + "_1").addClass("reap_current"); 
            return false;
        });
       
    });
</script>

<div class="onecol">
    <h1>Bible Reading Plan</h1>
</div>

<div class="twocol nomar">
    <p>Read through the Bible with us over the course of the year.</p>
</div>	

<div style="clear:both"></div>
<div class="bump">&nbsp;</div>

<div class="threecol_2">
    <div class="reap_top">
        <h2 id="date_label" class="nomar">Verses for <?php echo $date_long; ?></h2>
        <p class="caption nomar">
            <span id="yesterday">&larr; Yesterday</span> | 
            <span id="today">Today</span> | 
            <span id="tomorrow">Tomorrow &rarr;</span>
        </p>
    </div><!--/reap_top -->
</div>

<div style="clear:both;"></div>

<div class="reap_days hidden" id="reap_yesterday">
    <div class="sixcol nomar">
        <?php
        $count = 0;
        $url = "http://www.esvapi.org/v2/rest/readingPlanQuery?key=$key&reading-plan=$plan&date=$yesterday&output-format=$format";
        $xml_obj = new SimpleXMLElement(getResponseFromURL($url));

        foreach ($xml_obj->passage as $passage) {
            $count++;
            ?>
            <div class="reap" id="<?php echo "yesterday_" . $count; ?>"><a href="#">
                    <div class="reap_verse">
                        <h2 class="reap_book nomar"><?php echo str_replace('-', '&ndash;', $passage->reference); ?></h2>
                       <!-- <h3 class="reap_sub nomar"><?php echo $type[$count]; ?></h3>-->
                        <div class="reap_rule">&nbsp;</div>
                    </div></a>
            </div><!-- /reap -->
        <?php } ?>
    </div>

    <?php
    $count = 0;
    foreach ($xml_obj->passage as $passage) {
        $count++;
        ?>
        <div class="reap_scripture hidden" id="passage_<?php echo "yesterday_" . $count; ?>">
            <?php
            $passageRef = urlencode($passage->reference);
            $options = "include-passage-references=false&include-footnotes=false";
            $url = "http://www.esvapi.org/v2/rest/passageQuery?key=$key&passage=$passageRef&$options";
            print getResponseFromURL($url);
            ?>
        </div>

    <?php } ?>
</div>

<div class="reap_days hidden" id="reap_today">
    <div class="sixcol nomar">
        <?php
        $count = 0;
        $url = "http://www.esvapi.org/v2/rest/readingPlanQuery?key=$key&reading-plan=$plan&date=$today&output-format=$format";
        $xml_obj = new SimpleXMLElement(getResponseFromURL($url));

        foreach ($xml_obj->passage as $passage) {
            $count++;
            ?>
            <div class="reap" id="<?php echo "today_" . $count; ?>"><a href="#">
                    <div class="reap_verse">
                        <h2 class="reap_book nomar"><?php echo str_replace('-', '&ndash;', $passage->reference); ?></h2>
                       <!-- <h3 class="reap_sub nomar"><?php echo $type[$count]; ?></h3>-->
                        <div class="reap_rule">&nbsp;</div>
                    </div></a>
            </div><!-- /reap -->
        <?php } ?>
    </div>

    <?php
    $count = 0;
    foreach ($xml_obj->passage as $passage) {
        $count++;
        ?>
        <div class="reap_scripture hidden" id="passage_<?php echo "today_" . $count; ?>">
            <?php
            $passageRef = urlencode($passage->reference);
            $options = "include-passage-references=false&include-footnotes=false";
            $url = "http://www.esvapi.org/v2/rest/passageQuery?key=$key&passage=$passageRef&$options";
            print getResponseFromURL($url);
            ?>
        </div>

    <?php } ?>
</div>

<div class="reap_days hidden" id="reap_tomorrow">
    <div class="sixcol nomar">
        <?php
        $count = 0;
        $passage = "Matthew+6:34";
        $url = "http://www.esvapi.org/v2/rest/verse?key=$key&passage=$passage&output-format=$format";
        $xml_obj = new SimpleXMLElement(getResponseFromURL($url));

        foreach ($xml_obj->passage as $passage) {
            $count++;
            ?>
            <div class="reap" id="<?php echo "tomorrow_" . $count; ?>"><a href="#">
                    <div class="reap_verse">
                        <h2 class="reap_book nomar"><?php echo str_replace('-', '&ndash;', $passage->reference); ?></h2>
                       <!-- <h3 class="reap_sub nomar"><?php echo $type[$count]; ?></h3>-->
                        <div class="reap_rule">&nbsp;</div>
                    </div></a>
            </div><!-- /reap -->
        <?php } ?>
    </div>

    <?php
    $count = 0;
    foreach ($xml_obj->passage as $passage) {
        $count++;
        ?>
        <div class="reap_scripture hidden" id="passage_<?php echo "tomorrow_" . $count; ?>">
            <h2>Patience is a virtue</h2>
            <?php
            $passageRef = urlencode($passage->reference);
            $options = "include-passage-references=false&include-footnotes=false";
            $url = "http://www.esvapi.org/v2/rest/passageQuery?key=$key&passage=$passageRef&$options";
            print getResponseFromURL($url);
            ?>
        </div>

    <?php } ?>
</div>

<div class="threecol">
    <div style="margin-top:7px;">&nbsp;</div>
    <p class="sans">Need some study hints?</p>
    <p class="sans">REAP (Read, Examine, Apply, Pray) is a straight forward way to process what you're reading, either on your own or with a small group of people.</p>

    <p class="sans"><a href="/files/reap.pdf">Here are some instructions and a quick example</a>.</p>
    <p class="sans"><strong>TIP:</strong> To read the entire Bible in one year, read from all four columns each day. To read through the Bible in two or four years, select two or one column this year and the remaining columns in subsequent years.</p>
    <div class="rule_wh_short">&nbsp;</div>
    <p class="sans"><a href="/reap-esv"><strong>Go to the ESV Study Bible Plan</strong></a>.</p>
</div>

<?php

function getReadingForDayOfWeek($dayOfWeek) {
    $week = date('W');
    $year = date('Y');
    // if Sunday, go back a week and get date
    if ($dayOfWeek == '7')
        $week = $week - 1;
    $day_date = date("Y-m-d", strtotime($year . "-W" . $week . "-" . $dayOfWeek));

    // requests reading plan info, strips out each verse info
    $url = "http://www.esvapi.org/v2/rest/readingPlanInfo?key=IP&reading-plan=one-year-tract&date=$day_date&output-format=crossway-xml-1.0";
    $xml_obj = new SimpleXMLElement(getResponseFromURL($url));
    print $xml_obj;
    foreach ($xml_obj->info as $verse_info) {
        $arr1 = explode(";", $verse_info->{'private'}, 2);
        $arr2 = explode(";", $verse_info->{'family'}, 2);
        echo '<td>' . str_replace('-', '&ndash;', $arr1[0]) . '</td>';
        echo '<td>' . str_replace('-', '&ndash;', $arr1[1]) . '</td>';
        echo '<td>' . str_replace('-', '&ndash;', $arr2[0]) . '</td>';
        echo '<td>' . str_replace('-', '&ndash;', $arr2[1]) . '</td>';
    }
}
?>

<div class="threecol_2">

    <div class="rule_wh"></div><!--/\/\/\rule/\/\/\-->
    <div class="container">
        <h2>This Week's Reading Plan</h2>
    </div>
    <table>
        <tr>
            <td></td>
            <td><h3 class="nomar">Old<br>Testament</h3></td>
            <td><h3 class="nomar">New<br>Testament</h3></td>
            <td><h3 class="nomar">Old<br>Testament</h3></td>
            <td><h3 class="nomar">New<br>Testament</h3></td>
        </tr>
        <tr class="alt_day">
            <td class="day grey">Sunday:</td>
            <?php getReadingForDayOfWeek("7"); ?>					
        </tr>

        <tr>
            <td class="day grey">Monday:</td>
            <?php getReadingForDayOfWeek("1"); ?>					
        </tr>

        <tr class="alt_day">
            <td class="day grey">Tuesday:</td>
            <?php getReadingForDayOfWeek("2"); ?>	
        </tr>

        <tr>
            <td class="day grey">Wednesday:</td>
            <?php getReadingForDayOfWeek("3"); ?>					
        </tr>

        <tr class="alt_day">
            <td class="day grey">Thursday:</td>
            <?php getReadingForDayOfWeek("4"); ?>				
        </tr>

        <tr>
            <td class="day grey">Friday</td>
            <?php getReadingForDayOfWeek("5"); ?>					
        </tr>

        <tr class="alt_day">
            <td class="day grey">Saturday</td>
            <?php getReadingForDayOfWeek("6"); ?>					
        </tr>					
    </table>

</div><!--/threecol_2-->