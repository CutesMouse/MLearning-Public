<?php
require_once __DIR__."/../mysql/db.php";
$student = (isset($_GET["student"]) ? $_GET["student"] === "true" : false);
$dname = ($student) ? "edutopic" : "topic";
function canModify($data, $mysql) {
    $acc = getCurrentAcc($mysql);
    if (!$acc) return false;
    if ($acc["perm"] === "admin") return true;
    if ($data["author"] == $acc["username"]) return true;
    return false;
}
$data = $mysql->query("select * from $dname where id='$id'")->fetch_assoc();
if (!canModify($data,$mysql)) {
    echo "您沒有更改的權限!";
    if ($student) {
        header("refresh:2; url=index.php?type=student");
        return;
    }
    header("refresh:2; url=index.php?type=topic");
    return;
}
$mysql->query("delete from $dname where id='$id'");
echo "已刪除!";
if ($student) {
    header("refresh:2; url=index.php?type=student");
    return;
}
header("refresh:2; url=index.php?type=topic");