<title>MouseLearning</title>
<link href="favicon.ico" rel="icon">
<?php
define("WEB_DIR","http://".$_SERVER["HTTP_HOST"].substr($_SERVER["PHP_SELF"],0,strlen($_SERVER["PHP_SELF"])-9));
require_once "content/header.php";
echo "<style>.total_box{margin-top: 100px}</style><div class='total_box'>";
if (isset($_GET["type"])) {
    $type = $_GET["type"];
    switch ($type) {
        case "practice":
            if (!isset($_GET["id"])) die();
            $item = $_GET["id"];
            require_once "practice.php";
            break;
        case "login":
            require_once "account/login.php";
            break;
        case "topic":
            require_once "topic/topic.php";
            break;
        case "post":
            if (!isset($_GET["id"])) die();
            $item = $_GET["id"];
            require_once "post/post.php";
            break;
        case "register":
            require_once "account/register.php";
            break;
        case "modify":
            if (!isset($_GET["id"])) die();
            $id = $_GET["id"];
            require_once "post/topic_modify.php";
            break;
        case "delete":
            if (!isset($_GET["id"])) die();
            $id = $_GET["id"];
            require_once "post/topic_delete.php";
            break;
        case "admin":
            require_once "admin.php";
            break;
        case "student":
            require_once "student/index.php";
            break;
    }
    echo "</div>";
    return;
}
?>
<div class="list">
    <span class="title">MLearning 最懂你的學習網站</span><br>
    <div class="item_box">
        <div class="orp_block"><a href="?type=topic">奇文共賞</a>
            <div class="exp_block">廢文專區</div>
        </div>
        <div class="orp_block"><a href="?type=student">學生專區</a>
            <div class="exp_block">多樣教學資源</div>
        </div>
        <div class="orp_block"><a href="?type=post&id=topic">投稿奇文共賞</a>
            <div class="exp_block">勿提及私人資料與人身攻擊即可</div>
        </div>
        <div class="orp_block"><a href="https://www.facebook.com/CaoBeiNingMonTsa">靠北檸檬茶</a>
            <div class="exp_block">任何事情都可以發! 最優質的靠北粉專</div>
        </div>
    </div>
</div>
<style>
    body {
        background: url("index-bg.png");
    }
    .exp_block {
        display: none;
        position: absolute;
        background: rgba(0, 0, 0, 0.5);
        transform: translateX(10px) translateY(-5px);
        box-shadow: rgba(0, 0, 0, 0.2) 0px 0px 16px 5px;
        color: #bdfff0;
        font-size: 25px;
        padding: 5px;
        border-radius: 5px;
        -webkit-text-stroke: 0.3px #4659ff;
    }

    .orp_block {
        display: block;
        font-size: 35px;
        padding: 20px 0;
        border-radius: 10px;
    }

    .orp_block a {
        margin-left: 30px;
        padding: 5px;
        border-style: dashed;
        border-width: 3px;
        border-color: #fdffcb;
        text-decoration: none;
        color: #336100;
    }

    .orp_block:hover .exp_block {
        display: inline-block;
    }

    .list {
        display: block;
        padding: 25px 25px;
    }

    .title {
        font-size: 30px;
        display: inline-block;
        padding: 20px;
        border-radius: 20px;
        margin-bottom: 15px;
        background: rgba(253, 255, 203, 0.8);
        border-style: double;
        border-color: yellow;
        color: #525200;
    }

    .list .orp_block:nth-child(2n) {
        background: rgb(214, 248, 180);
    }

    .list .orp_block:nth-child(2n+1) {
        background: rgb(192, 245, 185);
    }

    .item_box {
        border-radius: 20px;
        border-style: solid;
        border-color: yellow;
        padding: 5px;
        background: #ffffc9;
    }
</style>