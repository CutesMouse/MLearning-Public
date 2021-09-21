<?php
session_start();
require_once __DIR__."/../mysql/db.php";
$username = $_POST["username"];
$password = $_POST["password"];
if (isset($_POST["cpassword"])) {
    if (mb_strlen($password,"utf8") < 8) {
        echo "註冊失敗! 密碼至少需要八個字!";
        header("refresh:2;url=../index.php?type=register");
        return;
    }
    if ($password != $_POST["cpassword"]) {
        echo "註冊失敗! 你的密碼不一致!";
        header("refresh:2;url=../index.php?type=register");
        return;
    }
    if ($mysql->query("SELECT username from accounts where username='$username'")->fetch_assoc()) {
        echo "已經有相同名稱的帳號囉!";
        header("refresh:2;url=../index.php?type=register");
        return;
    }
    $mysql->query("INSERT INTO accounts (username, password, perm) VALUES ('$username','$password','general')");
    echo "註冊成功!";
    header("refresh:2;url=../index.php?type=login");
    return;
}
$_SESSION["username"] = $username;
$_SESSION["password"] = $password;

$acd = $mysql->query("SELECT * from accounts where username='$username' and password='$password'")->fetch_assoc();
if ($acd != null) {
    echo "登入成功! 即將開始跳轉";
} else {
    echo "登入失敗!";
}
if (isset($_GET["redirect"])) {
    header("refresh:2;url={$_GET["redirect"]}");
    return;
}
header("refresh:2;url=../index.php");