<?php
function getCurrentAcc($mysql) {
    if (session_status() !== 2) session_start();
    if (!isset($_SESSION["username"]) || !isset($_SESSION["password"])) return null;
    $username = $_SESSION["username"];
    $password = $_SESSION["password"];
    return $mysql->query("select * from accounts where username='$username' and password='$password'")->fetch_assoc();
}
function logout() {
    if (session_status() !== 2) session_start();
    session_unset();
    session_destroy();
}
