<script>
    function back() {
        history.go(-1);
    }
</script>
<?php
require_once __DIR__."/../mysql/db.php";
require_once __DIR__."/../mysql/acmanager.php";
if (getCurrentAcc($mysql) == null && !isset($_GET["type"])) return;
$type = $_GET["type"];
function genRID($length) {
    if ($length == 1) return chr(rand(97,122));
    return genRID($length-1).genRID(1);
}
$time = new DateTime("now",new DateTimeZone("asia/Taipei"));
$time = date_format($time,"Y/m/d H:i:s");
function canModify($data, $mysql) {
    $acc = getCurrentAcc($mysql);
    if (!$acc) return false;
    if ($acc["perm"] === "admin") return true;
    if ($data["author"] == $acc["username"]) return true;
    return false;
}
$student = isset($_GET["student"]) ? $_GET["student"] ==="true" : false;
$from = $student ? "edutopic" : "topic";
switch ($type) {
    case "topic":
        $Rid = genRID(20);
        $title = $_POST["title"];
        $content = mysqli_real_escape_string($mysql,$_POST["content"]);
        $author = getCurrentAcc($mysql)["username"];
        $mysql->query("INSERT INTO {$from} (id, title, content, time, author) values ('$Rid','$title','$content','$time','$author')");
        break;
    case "student":
        $Rid = genRID(15);
        $title = $_POST["title"];
        if (!$title) {
            echo "標題不可留空!";
            echo "<a href='javascript:back()'>回上頁</a>";
            return;
        }
        $typed = $_POST["type"];
        $typed = $mysql->query("select * from type where id='$typed'")->fetch_assoc();
        if (!$typed) {
            echo "找不到的分類! 請返回上頁更改!<br>";
            echo "<a href='javascript:back()'>回上頁</a>";
            return;
        }
        $content = mysqli_real_escape_string($mysql,$_POST["content"]);
        $author = getCurrentAcc($mysql)["username"];
        $mysql->query("INSERT INTO edutopic (type, id, title, content, time, author) values ('{$typed["id"]}','$Rid','$title','$content','$time','$author')");
        if (isset($_POST["attach"])) {
            $attach = $_POST["attach"];
            $mysql->query("update edutopic set attach='{$attach}' where id='{$Rid}'");
        }
        break;
    case "topic_modify":
        $id = (isset($_GET["id"]) ? $_GET["id"] : "");
        $data = $mysql->query("select * from {$from} where id='$id'")->fetch_assoc();
        if ($data == null) {
            echo "找不到相關貼文!";
            return;
        }
        $content = mysqli_real_escape_string($mysql,$_POST["content"]);
        $title = $_POST["title"];
        $attach = isset($_POST["attach"]) ? $_POST["attach"] : "";
        if (!canModify($data,$mysql)) {
            echo "您沒有權限編輯這篇文章!";
            return;
        }
        $mysql->query("update {$from} set content='$content',title='$title',time='$time' where id='$id'");
        if ($student && $attach) {
            $mysql->query("update {$from} set attach='{$attach}' where id='{$id}'");
        }
        break;
}
echo "投稿完成! 兩秒後開始跳轉..";
if ($student || $type == "student") {
    header("refresh:2;url=../index.php?type=student");
    return;
}
header("refresh:2;url=../index.php?type=topic");