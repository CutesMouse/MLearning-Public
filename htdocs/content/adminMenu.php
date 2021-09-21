<?php
if (!isset($admin)) return;
if (!$admin) return;
require_once __DIR__."/../mysql/db.php";
?>
<link rel="stylesheet" type="text/css" href="content/adminMenu.css">
<div class="left_box">
    <div class="title_box">
        <button class="title">管理學習專區</button>
    </div>
    <div class="title_box">
        <button class="title">使用者帳號管理</button>
    </div>
</div>
<div class="main_box">
    <?php
    $action = isset($_GET["action"]) ? $_GET["action"] : "main";
    switch ($action) {
        case "main":
            require_once __DIR__."/../admin/main.php";
            break;
        case "mysql":
            require_once
    }

    ?>
</div>
