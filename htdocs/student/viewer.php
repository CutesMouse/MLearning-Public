<?php
if (isset($_GET["viewid"])) $viewid = $_GET["viewid"];
if (!isset($viewid)) return;
if (isset($_POST["comment"])) {
    $type = $_POST["type"];
    $comment = $_POST["comment"];
    require_once __DIR__ . "/../mysql/db.php";
    require_once __DIR__ . "/../mysql/commentSystem.php";
    if ($type === "create") submitComment($viewid, $comment, $mysql);
    else modifyComment($type,$viewid, $comment, $mysql);
    $_POST = array();
    header("refresh:0");
}
if (isset($_GET["delete_comment"])) {
    require_once __DIR__ . "/../mysql/db.php";
    require_once __DIR__ . "/../mysql/commentSystem.php";
    deleteComment($_GET["delete_comment"],$viewid,$mysql);
    echo "已刪除!";
    return;
}
?>
<link rel="stylesheet" href="editor/css/editormd.preview.css" type="text/css"/>
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

<script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css"/>

<link href="topic/topic.css" type="text/css" rel="stylesheet">
<style>
    .submit, .attachButton {
        margin-top: 10px;
        background: rgb(255, 198, 150);
        color: red;
        font-size: 20px;
        padding: 5px 20px;
        border: 2px solid #cd4a4a;
    }

    .submit:hover, .attachButton:hover {
        background: rgb(240, 140, 50);
    }
</style>
<script src="editor/editormd.js"></script>
<script src="editor/lib/marked.min.js"></script>
<script src="editor/lib/prettify.min.js"></script>
<script>
    function check(url) {
        if (confirm("你確定要刪除嗎? 此舉動無法挽回喔")) {
            window.open(url + "&student=true", "_self");
        }
    }
</script>
<div class="block">
    <?php
    require_once __DIR__ . "/../mysql/db.php";
    require_once __DIR__ . "/../mysql/acmanager.php";
    $data = $mysql->query("select * from edutopic where id='{$viewid}'")->fetch_assoc();
    function genDiv($data, $mysql)
    {
        $modify = "";
        if (canModify($data, $mysql)) {
            $modify = ". / <a href='index.php?type=modify&student=true&id=" . $data["id"] . "'>編輯</a> / <a href='#' onclick='check(\"index.php?type=delete&id=" . $data["id"] . "\")'>刪除</a>";
        }
        echo "<div class='topic'>
        <div class='title'>" . $data["title"] . "</div><br>
        <hr class='sep'>
        <div class='text_box' id='{$data["id"]}'>
        <textarea style='display: none'>{$data["content"]}</textarea></div>";

        if ($data["attach"]) {
            require_once "attachLoader.php";
            $attachs = json_decode($data["attach"]);
            if (sizeof((array)$attachs) != 0) {
                echo "<hr class='sep'>";
                echo "附件:";
                foreach ($attachs as $id => $attach) {
                    echo " <a identify='{$id}' id='$id' href='javascript:void(0)' onclick='openWindow(this)'>{$attach->name}</a>";
                    load($id, $attach);
                }
                $GLOBALS["ops"] = true;
            }
        }

        echo "
        <div class='info'>發布者: " . $data["author"] . " / 時間: " . $data["time"] . " / ID: " . $data["id"] . "$modify</div>
        <button onclick='history.back()' class='submit'>回上頁</button>";
    }

    function canModify($data, $mysql)
    {
        $acc = getCurrentAcc($mysql);
        if (!$acc) return false;
        if ($acc["perm"] === "admin") return true;
        if ($data["author"] === $acc["username"]) return true;
        return false;
    }

    genDiv($data, $mysql);
    ?>
</div>
<script type="text/javascript">
    var array = document.getElementsByClassName("text_box");
    for (let i = 0; i < array.length; i++) {
        let o = array[i];
        $(function () {
            editormd.markdownToHTML(o.id, {
                emoji: true,
            });
        });
    }
    function modify(id) {
        $.ajax("post/comment_grab.php?post=" + "<?php echo $viewid?>&comment_id="+id,{
            dataType: "json",
            success: function(arg) {
                $("#comment-textarea").val(arg.content.content);
                $("#comment-type").val(id.toString());
            }
        })
    }
    function cmd(id) {
        if (!confirm("確定要刪除這則留言?")) return;
        $.ajax("student/viewer.php?delete_comment="+id+"&viewid=<?php echo $viewid?>",{
            data: null,
            success: function(arg) {
                history.go(0);
            }
        });
    }
</script>
<?php
if (isset($GLOBALS["ops"])) {
    ?>
    <style>
        .window .score {
            display: block;
            font-size: 25px;
            text-align: center;
            margin: 30px 0;
        }
        .window .back {
            display: block;
            text-align: center;
            margin: 30px 0;
        }
        .leave {
            float: right;
            border: none;
            background: red;
            color: pink;
        }
        .leave:hover {
            background: pink;
            color: red;
            transition: all 0.2s;
        }
        .attachButton {
            margin: 10px 0;
        }
        .black {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(0,0,0,0.7);
            transition: all 0.5s;
        }
        .head {
            font-family: Comic Sans MS,微軟正黑體;
            font-size: 30px;
            margin: 10px 10px;
        }
        .title-sep {
            margin: 20px 20px;
        }
        .content {
            margin: 0px 10px;
            font-family: Comic Sans MS,微軟正黑體;
        }
        .window {
            background: white;
            display: block;
            position: fixed;
            top: 60%;
            left: 50%;
            min-width: 400px;
            min-height: 400px;
            transform: translateX(-50%) translateY(-50%);
            opacity: 0;
            transition: all 0.5s;
            padding: 30px;
        }
        .window input {
            margin: 10px 0;
            font-size: 20px;
        }
        .window .question {
            font-size: 25px;
        }
        .open {
            opacity: 1;
            border-color: black;
            border-width: 2px;
            border-style: solid;
            border-radius: 5px;
            top: 50%;
            left: 50%;
            transform: translateX(-50%) translateY(-50%);
            transition: all 0.5s;
        }
    </style>
    <div id="black"></div>
    <script>
        function openWindow(ele) {
            let id = ele.getAttribute("identify");
            let black = document.getElementById("black").classList;
            let cl = document.getElementById(id+"_outer_dialog").classList;
            if (cl.contains("open")) {
                cl.remove("open");
                black.remove("black")
            }
            else {
                black.add("black");
                cl.add("open");
            }
        }

        $(function () {<?php
        printOut();
        echo "});"; ?></script><?php } ?>
<link rel="stylesheet" type="text/css" href="student/comment.css">
<hr class="sep">
<div id="comment">
    <?php
    if ($data["comments"]) {
        $cmt = json_decode($data["comments"]);
        require_once __DIR__."/../mysql/commentSystem.php";
        require_once __DIR__."/../mysql/db.php";
        if (sizeof((array) $cmt)) {
            for ($i = 0; $i < sizeof((array) $cmt); $i++) {
                $obj = ((array) ($cmt))[$i];
                echo "<div class='comment-view'>";
                echo preg_replace("[&spl;]","<br>",preg_replace("[<]","&lt;",$obj->content));
                echo "<div class='comment-author'>作者: {$obj->author} / 發布時間: {$obj->time}";
                if (canModifyComment($obj,$mysql)) {
                    echo " / <a href='javascript:void(0)' onclick='modify(\"{$obj->id}\")'>編輯</a>";
                }
                if (canDeleteComment($obj,$data,$mysql)) {
                    echo " / <a href='javascript:void(0)' onclick='cmd(\"{$obj->id}\")'>刪除</a>";
                }
                echo "</div>";
                echo "</div>";
            }
        }
    }
    ?>
    <div id="comment-post">
        <?php
        if ($acc) {
            ?>
            <form method="post">
                <label id="comment-textarea-label" for="comment-textarea">留言<br></label>
                <textarea id='comment-textarea' name="comment"></textarea>
                <input id="comment-type" style="display: none" name="type" value="create">
                <input id="comment-submit" type="submit" value="送出">
            </form>
        <?php
        } else {
            echo "請先登入才能進行留言!";
        }
        ?>
    </div>
</div>

