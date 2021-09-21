<head>
    <script>
        var win = null;

        function openList() {
            win = window.open("post/types.php", "", "height=300, width=250,toolbar=no");
            checkIfClose();
        }

        function checkIfClose() {
            if (win == null) return;
            if (win.closed) {
                let i = win.document.getElementById("result");
                if (i == null) return;
                adjustType(i.innerHTML);
                win = null;
                return;
            }
            setTimeout(checkIfClose, 500);
        }

        function adjustType(item) {
            document.getElementById("type").value = item;
        }

        var source = null;
    </script>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <link type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/eggplant/jquery-ui.css"/>

    <?php
    require_once __DIR__ . "/../mysql/acmanager.php";
    if (getCurrentAcc($mysql) == null) {
        echo "請先登入後才能進行投稿!";
        header("refresh:2;url=index.php?type=login");
        return;
    }
    $student = ($item === "student");
    if ($student) echo "<script src=\"post/attachment/list-panel.js\"></script>";
    ?>
</head>
<div class="block">
    <div class="sblock">
        <form id="main-form" method="post"
              action="post/submit.php?type=<?php if ($student) echo "student"; else echo "topic" ?>">
            <div class="title"><label for="title">標題</label> <input id="title" name="title">
                <?php if ($student) {
                    ?>
                    <label for="type">類別</label> <input id="type" name="type">
                    <button type="button" class="submit type-list" onclick="openList()">類別列表</button>
                    <button type="button" id="attButton" class="submit attachments-list">附件列表</button>
                    <br>
                    <?php
                } ?>
            </div>
            <div id="content"><textarea style="display: none" name="content"></textarea></div>
            <input type="text" style="display: none" id="attach_input" name="attach">
            <div class="submit-block">
                <button id="main-form-submit" class="submit" type="button">送出</button>
            </div>
        </form>
    </div>
</div>
<?php if ($student) { ?>
    <div id="add_attachment" class="window">
        <span class="title">新增附件</span>
        <hr class="title-sep">
        <div class="content">
            <form id="add_attachment_form" method="post" class="content">
                <label for="add_attachment_type">附件類型 </label><select id="add_attachment_type" name="type">
                    <option value="opt">單一選擇題</option>
                </select>
                <br><label for="add_attachment_title">標題 </label><input id="add_attachment_title" type="text" name="title">
            </form>
        </div>
    </div>
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
            background: rgba(0, 0, 0, 0.7);
            transition: all 0.5s;
        }

        .head {
            font-family: Comic Sans MS, 微軟正黑體;
            font-size: 30px;
            margin: 10px 10px;
        }

        .title-sep {
            margin: 20px 20px;
        }

        .content {
            margin: 0px 10px;
            font-family: Comic Sans MS, 微軟正黑體;
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
            z-index: 100;
        }

        .bottom-box {
            position: absolute;
            bottom: 20px;
        }

        .options {
            font-size: 20px;
            background: white;
            border-radius: 5px;
            margin: 0 10px;
        }

        .options:hover {
            background: rgb(186, 186, 186);
            transition: all 0.5s;
        }
    </style>
<?php } ?>
<link rel="stylesheet" href="editor/css/editormd.css"/>
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
    editormd.emoji = {
        path: "https://www.webfx.com/tools/emoji-cheat-sheet/graphics/emojis/",
        ext: ".png"
    };
    $(function () {
        var editor = editormd("content", {
            path: "editor/lib/",
            toolbarIcons: function () {
                return ["undo", "redo", "|", "bold", "italic", "del", "|", "hr", "quote", "html-entities", "emoji", "link", "image", "code", "code-block", "table", "datetime", "|", "ucwords", "uppercase", "lowercase", "|", "h1", "h2", "h3", "h4", "h5", "h6", "|", "list-ul", "list-ol", "|", "search", "clear", "watch"]
            },
            emoji: true,
            imageUpload: true,
            imageFormats: ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
            imageUploadURL: "attachments/upload.php",
        });
        $('#main-form-submit').click(function () {
            if (!$('#title').val()) {
                alert("標題不得為空!");
                return;
            }
            <?php
            if ($student) {
            ?>
            if (!$('#type').val()) {
                alert("分類不得為空!");
                return;
            }
            <?php
            }
            ?>
            $('#main-form').submit();
        });
    });
</script>
<?php
