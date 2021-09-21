<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/eggplant/jquery-ui.css"/>
<link rel="stylesheet" href="editor/css/editormd.css" />
<?php
require_once __DIR__ . "/../mysql/acmanager.php";
$acc = getCurrentAcc($mysql);
$student = isset($_GET["student"]) ? $_GET["student"] ==="true" : false;
$from = $student ? "edutopic" : "topic";
$data = $mysql->query("select content,title,author from {$from} where id='$id'")->fetch_assoc();
if ($student) {
    $attach = $mysql->query("select attach from {$from} where id='{$id}'")->fetch_assoc()["attach"];
    echo "<script>var source = ";
    if ($attach) {
        echo "'".$attach."'";
    } else echo "null";
    echo "</script><script src=\"post/attachment/list-panel.js\"></script>";
}
function canModify($data, $mysql)
{
    $acc = getCurrentAcc($mysql);
    if (!$acc) return false;
    if ($acc["perm"] === "admin") return true;
    if ($data["author"] === $acc["username"]) return true;
    return false;
}

if (!$data) {
    echo "找不到的文章!";
    header("refresh:2;url=index.php?type=topic");
    return;
}
if ($acc == null || !canModify($data, $mysql)) {
    echo "您沒有編輯的權限!";
    header("refresh:2;url=index.php?type=topic");
    return;
}
?>
    <div class="block">
        <div class="sblock">
            <form id="main-form" method="post" action="post/submit.php?type=topic_modify<?php if ($student) echo "&student=true"?>&id=<?php echo $id ?>">
                <div class="title"><label for="title">文章標題</label> <input value="<?php echo $data["title"] ?>"
                                                                          id="title" name="title">
                    <?php if ($student) {
                        ?>
                        <button type="button" id="attButton" class="submit attachments-list">附件列表</button>
                        <br>
                        <?php
                    } ?><br></div>
                <div id="content"><textarea name="content"><?php echo $data["content"] ?></textarea></div>
                <input type="text" style="display: none" id="attach_input" name="attach">
                <div class="submit-block"><button id="main-form-submit" class="submit" type="button">送出</button></div>
            </form>
        </div>
    </div>
<?php if ($student) { ?><div id="add_attachment">
    <form id="add_attachment_form" method="post">
        <label for="add_attachment_type">附件類型 </label><select id="add_attachment_type" name="type"><option value="opt">單一選擇題</option></select>
        <br><label for="add_attachment_title">標題 </label><input id="add_attachment_title" type="text" name="title">
    </form>
</div><?php }?>
    <style>
        body {
            background: #ffd9b7;
            padding-bottom: 100px;
        }

        .attachments-list {
            margin-left: 10px;
        }

        .block {
            width: 80%;
            height: 80%;
            margin: 0 auto;
            text-align: center;
            display: block;
        }

        .submit {
            margin-top: 10px;
            background: rgb(255, 198, 150);
            color: red;
            font-size: 20px;
            padding: 5px 20px;
            border: 2px solid #cd4a4a;
        }

        .submit:hover {
            background: rgb(240, 140, 50);
        }

        .type-list, .attachments-list {
            position: relative;
            top: -7px;
        }

        .title, .title input {
            margin: 20px;
            font-size: 30px;
        }

        .title {
            display: flex;
            align-items: center;
        }

        .content textarea {
            margin: 0 20px 20px;
            font-size: 25px;
            width: 80%;
            height: 60%;
            resize: none;
        }
    </style>
    <script src="editor/editormd.min.js"></script>
    <script src="editor/languages/zh-tw.js"></script>
    <script type="text/javascript">
        editormd.emoji     = {
            path  : "https://www.webfx.com/tools/emoji-cheat-sheet/graphics/emojis/",
            ext   : ".png"
        };
        $(function() {
            var editor = editormd("content", {
                // width  : "100%",
                // height : "100%",
                // width  : "100%",
                // height : "100%",
                path   : "editor/lib/",
                toolbarIcons : function() {
                    return ["undo", "redo","|","bold","italic", "del","|", "hr","quote","html-entities","emoji","link","image","code","code-block","table","datetime","|","ucwords","uppercase","lowercase","|","h1","h2","h3","h4","h5","h6","|","list-ul","list-ol","|", "search", "clear", "watch"]
                },
                emoji: true,
                imageUpload : true,
                imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
                imageUploadURL : "attachments/upload.php",
            });
            $('#main-form-submit').click(function() {
                if (!$('#title').val()) {
                    alert("標題不得為空!");
                    return;
                }
                $('#main-form').submit();
            });
        });
    </script>
<?php
