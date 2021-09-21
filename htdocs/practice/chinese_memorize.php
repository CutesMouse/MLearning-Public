<?php
$difficulties = isset($_GET["dif"]) ? $_GET["dif"] : "3";
$GLOBALS["index"] = 0;
$quotes = isset($_GET["quote"]) ? $_GET["quote"] : "true";
$source = $info["content_array"];
$source = preg_split("[/]", $source);
define("QUOTELIST", array("，", "。","、", "；", "：", "「", "」", "『", "』", "【", "】",
    "《", "》", "！", "？", "?", "!", ":", ";", "\"", "＂", "."));
if (isset($_GET["answer"])) {
    require_once "chinese_memorize_answer.php";
    return;
}
?>
<div class="content">
    <div class="settings-border">
        <div class="settings">
            <input autocomplete="off" type="checkbox" id="quotes"><label for="quotes">忽略標點符號</label>
            <br><label>難度: </label><label id="star1" class="star" onmouseover="starMo(this)">★</label><label id="star2"
                                                                                                             class="star"
                                                                                                             onmouseover="starMo(this)">★</label><label
                    class="star" id="star3" onmouseover="starMo(this)">★</label><label class="star" id="star4"
                                                                                       onmouseover="starMo(this)">★</label><label
                    class="star" id="star5" onmouseover="starMo(this)">★</label>
            <br>
            <button class="refresh" onclick="submit()">重新整理</button>
        </div>
    </div>
</div>
<script>
    var difficulties = "<?php echo $difficulties?>";
    var quotes = "<?php echo $quotes?>";
    if (quotes === "true") document.getElementById("quotes").checked = true;
    setStar(document.getElementById("star" + difficulties));

    function starMo(ele) {
        ele.classList.add("star-hover");
        ele.setAttribute("onmouseleave", "starLe(this)");
        ele.setAttribute("onclick", "setStar(this)");
        checkSt(ele);
    }

    function setStar(ele) {
        for (let i = 5; i > 0; i--) document.getElementById("star" + i).classList.remove("star-selected");
        starLe(ele);
        let id = ele.id.substr(-1, 1);
        difficulties = id;
        for (let i = id; i > 0; i--) document.getElementById("star" + i).classList.add("star-selected");
    }

    function checkSt(hover) {
        let id = hover.id.substr(-1, 1);
        for (let i = parseInt(id) - 1; i > 0; i--) {
            document.getElementById("star" + i).classList.add("star-hover");
        }
        for (let i = parseInt(id) + 1; i <= 5; i++) {
            document.getElementById("star" + i).classList.remove("star-hover");
        }
    }

    function starLe(ele) {
        for (let i = 1; i <= 5; i++) document.getElementById("star" + i).classList.remove("star-hover");
    }

    function submit() {
        window.open("<?php echo $php_self . "?type=practice&id=$id"?>" + "&dif=" + difficulties + "&quote=" + (document.getElementById("quotes").checked), "_self");
    }
</script>
<?php
function getRandomArray($dif, $source)
{
    $total = sizeof($source);
    $quest = intval(0.2 * $dif * $total);
    $result = array();
    for ($i = 0; $i < $total; $i++) {
        if ($quest != 0) {
            array_push($result, true);
            $quest--;
            continue;
        }
        array_push($result, false);
    }
    shuffle($result);
    return $result;
}

$GLOBALS["script"] = "";
function genQuestion($content, $quote, $id)
{
    $GLOBALS["last"] = $GLOBALS["index"];
    if (!$quote) {
        $GLOBALS["index"]++;
        echo "<input id='{$GLOBALS["index"]}' autocomplete=\"off\" class='question_input' name='$id' style='width:" . (mb_strlen($content, "utf-8") * 35) . "'>";
        return;
    }
    $queue = "";
    $part = 0;
    for ($i = 0; $i < mb_strlen($content, "utf-8"); $i++) {
        $current = mb_substr($content, $i, 1, "utf-8");
        if (in_array($current, QUOTELIST)) {
            if (!$queue) {
                echo "<label class='plain'>$current</label>";
                continue;
            }
            $GLOBALS["index"]++;
            echo "<input id='{$GLOBALS["index"]}' autocomplete=\"off\" class='question_input' name='$id-$part' type='text' style='width:" . (mb_strlen($queue, "utf-8") *35) . "'>" . "<label class='plain'>$current</label>";
            $part++;
            $queue = "";
            continue;
        }
        $queue .= $current;
    }
    if ($queue) {
        $GLOBALS["index"]++;
        echo "<input id='{$GLOBALS["index"]}' autocomplete=\"off\" class='question_input' name='$id-$part' type='text' style='width:" . (mb_strlen($queue, "utf-8") * 35) . "'>";
    }
}

function genPlain($content)
{
    echo "<label class='plain'>$content</label>";
}

$pool = getRandomArray($difficulties, $source);
?>
<div class="main_questions">
    <form onkeypress="ignore(event)" method="post"
          action="index.php?type=practice&id=<?php echo $id . "&dif=$difficulties&quote=$quotes" ?>&answer=true">
        <?php
        for ($i = 0; $i < sizeof($source); $i++) {
            if ($pool[$i]) genQuestion($source[$i], $quotes == "true", $i);
            else genPlain($source[$i]);
            echo "<br>";
        }
        ?>
        <input autocomplete="off" type="submit" class="submit" value="提交">
    </form>
</div>
<script>
    <?php
        if ($GLOBALS["index"]) {
            for ($i = 1; $i < $GLOBALS["index"];$i++) {
                $next = $i+1;
                echo "document.getElementById('{$i}').setAttribute(\"onkeyup\",\"changeTo({$next},event)\");";
            }
        }
    echo $GLOBALS["script"]
    ?>
    function ignore(event) {
        if (event.which === 13) {
            event.preventDefault();
            return false;
        }
    }

    function changeTo(id, event) {
        if (event.which != 13) return;
        document.getElementById(id).focus();
    }
</script>
