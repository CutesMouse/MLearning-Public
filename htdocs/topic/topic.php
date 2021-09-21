<link rel="stylesheet" href="editor/css/editormd.preview.css" type="text/css" />
<link href="topic/topic.css" type="text/css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="editor/editormd.js"></script>
<script src="editor/lib/marked.min.js"></script>
<script src="editor/lib/prettify.min.js"></script>
<!--<script>hljs.initHighlightingOnLoad();</script>
<script src="script/highLight.js"></script>
<link href="topic/content.css" type="text/css" rel="stylesheet">-->

<script>
    function check(url) {
        if (confirm("你確定要刪除嗎? 此舉動無法挽回喔")) {
            window.open(url,"_self");
        }
    }
</script>
<div class="block">
<?php
require_once __DIR__."/../mysql/db.php";
$cmd = $mysql->query("select * from topic order by time");
function canModify($data, $mysql) {
    $acc = getCurrentAcc($mysql);
    if (!$acc) return false;
    if ($acc["perm"] === "admin") return true;
    if ($data["author"] === $acc["username"]) return true;
    return false;
}
function genDiv($data, $mysql) {
    $modify = "";
    if (canModify($data,$mysql)) {
        $modify = ". / <a href='index.php?type=modify&id=".$data["id"]."'>編輯</a> / <a href='#' onclick='check(\"index.php?type=delete&id=".$data["id"]."\")'>刪除</a>";
    }
    echo "<div class='topic'>
        <div class='title'>".$data["title"]."</div><br>
        <hr class='sep'>
        <div class='text_box' id='{$data["id"]}'>
        <textarea style='display: none'>{$data["content"]}</textarea>
        </div>
        <div class='info'>發布者: ".$data["author"]." / 時間: ".$data["time"]." / ID: ".$data["id"]."$modify</div>
    </div>";
}
$items = array();
while ($t = $cmd->fetch_assoc()) {
    array_push($items,$t);
}
$length = sizeof($items);
for ($i = $length-1; $i >= 0 && $length - $i <= 100; $i--) {
    //$items[$i]["content"] = $items[$i]["content"];
    genDiv($items[$i],$mysql);
}
?></div>

<script type="text/javascript">
    var array = document.getElementsByClassName("text_box");
    for (let i = 0; i < array.length; i++) {
        let o = array[i];
        $(function() {
            editormd.markdownToHTML(o.id,{
                emoji : true,
            });
        });
    }
</script>