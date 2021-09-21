<?php
$sc = $info["content_array"];
$source = preg_split("[/]", $sc);
if (isset($_GET["answer"])) {
    require_once "chinese_explain_answer.php";
    return;
}
?>
<script>
    function submit() {
        window.open("<?php echo $php_self . "?type=practice&id=$id"?>" + "&dif=" + difficulties + "&quote=" + (document.getElementById("quotes").checked), "_self");
    }
</script>
<?php
$GLOBALS["script"] = "";
function genQuestion($content, $id)
{
    if (isset($GLOBALS["last"])) {
        $GLOBALS["script"] .= "document.getElementById(\"" . $GLOBALS["last"] . "\").setAttribute(\"onkeyup\",\"changeTo($id,event)\");";
    }
    $GLOBALS["last"] = $id;
    $quest = preg_split("[:]",$content)[0];
    $ans = preg_split("[:]",$content)[1];
    genPlain($quest.": ");
    echo "<input id='$id' autocomplete=\"off\" class='question_input' name='$id' 
    style='width:" . (mb_strlen($ans, "utf-8") * 50) . "'>";
    genPlain("。");
}

function genPlain($content)
{
    $temp = "";
    $length = mb_strlen($content,"utf8");
    $isPar = false;
    $par = "";
    $par_content = "";
    for ($i = 0; $i < $length; $i++) {
        $cur = mb_substr($content,$i,1,"utf8");
        if ($isPar) {
            if ($cur == "）") {
                $isPar = false;
                genToolTipPlain($par,$par_content," alphabet");
                $par = "";
                $par_content = "";
                continue;
            }
            if ($cur == "（") continue;
            $par_content .= $cur;
            continue;
        }
        $curNxt = ($i === $length-1 ? "" : mb_substr($content,$i+1,1,"utf8"));
        if ($curNxt == '（') {
            if ($temp) {
                echo "<label class='plain'>$temp</label>";
                $temp = "";
            }
            $par = $cur;
            $isPar = true;
            continue;
        }
        $temp .= $cur;
    }
    if ($temp) echo "<label class='plain'>$temp</label>";
    //echo "<label class='plain'>$content</label>";
}
function genToolTipPlain($word, $tooltip, $extraClass) {
    echo "<span class='plain{$extraClass}'>$word<span class='plain-tooltip'>$tooltip</span></span>";
}
?>
<div class="main_questions">
    <form onkeypress="ignore(event)" method="post" action="index.php?type=practice&id=<?php echo $id?>&answer=true">
        <?php
        for ($i = 0; $i < sizeof($source); $i++) {
            genQuestion($source[$i], $i);
            echo "<br>";
        }
        ?>
        <input autocomplete="off" type="submit" class="submit" value="提交">
    </form>
</div>
<script>
    <?php echo $GLOBALS["script"] ?>
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
