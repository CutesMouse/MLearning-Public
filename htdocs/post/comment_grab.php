<?php
if (!isset($_GET["post"])) return;
if (!isset($_GET["comment_id"])) return;
require_once __DIR__."/../mysql/db.php";
$info = $mysql->query("select comments from edutopic where id='{$_GET["post"]}'")->fetch_assoc();
if (!$info) {
    echo json_encode(array("status" => "failed"));
} else {
    $info = array_filter(json_decode($info["comments"]), function($item) {
        return $item->id === $_GET["comment_id"];
    });
    if (sizeof($info) != 1) {
        echo json_encode(array("status" => "failed"));
        return;
    }
    foreach ($info as $t) {
        echo json_encode(array("status" => "success","content" => $t));
    }
}