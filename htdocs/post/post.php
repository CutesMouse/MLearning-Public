<?php
switch ($item) {
    case "topic":
    case "student":
        require_once "topic_post.php";
        break;
    default:
        echo "不存在的投稿類別!";
        break;
}