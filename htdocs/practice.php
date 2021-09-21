<?php
$header_cssPath = "content/header.css";
$header_jsPath = "script/header.js";
$php_self = $_SERVER["PHP_SELF"];
require_once "content/header.php";
?>
<link type="text/css" href="practice.css" rel="stylesheet">
<br>
<body>
<?php
if (!isset($_GET["id"])) die();
require_once "mysql/db.php";
$id = $_GET["id"];
$info = $mysql->query("select * from prainfo where id='$id'")->fetch_assoc();
if ($info["type"] == "english_vocabulary_high") $info["type"] = "english_vocabulary";
require_once "practice/".$info["type"].".php" ?>
</body>