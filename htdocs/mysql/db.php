<?php
$db_username = "root";
$db_password = "";
$db_server = "localhost";
$db_database = "learn";

$debug = true;

$mysql = new mysqli($db_server,$db_username,$db_password,$db_database);
$mysql->query("set names utf8");