<?php
 //if (!isset($header_cssPath) || !isset($header_jsPath)) return;
$header_cssPath = WEB_DIR."content/header.css";
$header_jsPath = WEB_DIR."script/header.js";
echo "<link href='$header_cssPath' rel=\"stylesheet\" type=\"text/css\">";
echo "<script src='$header_jsPath'></script>";
require_once __DIR__."/../mysql/db.php";
?>
<span class="top-list">
    <div class="menu-container">
        <button class="menu-header" onclick="url('index.php')">首頁</button>
    </div>
    <div class="menu-container">
        <button class="menu-header" onclick="url('index.php?type=topic')">奇文共賞</button>
    </div>
    <div class="menu-container">
        <button class="menu-header">學習</button>
        <div class="menu-content">
            <?php
            $type = $mysql->query("select * from type");
            while ($t = $type->fetch_assoc()) {
                $quest = $mysql->query("SELECT type,id,title,name,accept from prainfo where type='{$t["id"]}' order by title,time");
                $status = false;
                while ($item = $quest->fetch_assoc()) {
                    if (!$status) {
                        echo "<div class='sub-menu-container'><a href='javascript:void(0)' class='menu-item sub-menu-item'>".$t["name"]."</a>";
                        echo "<div class='sub-menu-content'>";
                    }
                    echo "<a href='javascript:void(0)' class='sub-menu-element' onclick=\"practice('".$item["id"]."')\">{$item["title"]}</a>";
                    $status = true;
                }
                if ($status) echo "</div></div>";
            }
            /*$task = $mysql->query("SELECT type,id,title,name,accept from prainfo");
            while ($t = $task->fetch_assoc()) {
                if (!($t["accept"])) continue;
                $type_name = $mysql->query("SELECT * from type where id='".$t["type"]."'")->fetch_assoc()["name"];
                echo "<a href='#' class='menu-item' onclick=\"practice('".$t["id"]."')\">".$type_name."-".$t["title"]."</a>";
            }*/
            ?>
        </div>
    </div>
    <div class="menu-container">
        <button class="menu-header">投稿</button>
        <div class="menu-content">
            <a href="index.php?type=post&id=topic" class="menu-item">奇文共賞</a>
        </div>
    </div>
    <div class="menu-container">
        <button class="menu-header">帳號</button>
            <div class="menu-content">
                <?php
                    require_once __DIR__."/../mysql/acmanager.php";
                    $acc = getCurrentAcc($mysql);
                    if ($acc != null) {
                        echo "<a href=\"account/logout.php\" class=\"menu-item\">登出</a>";
                        echo "<a href=\"?type=student\" class=\"menu-item\">學生專區</a>";
                        if ($acc["perm"] === "admin") {
                            echo "<a href=\"index.php?type=admin\" class=\"menu-item\">管理</a>";
                            echo "<a href=\"database.php\" class=\"menu-item\">資料庫</a>";
                        }

                    } else {
                        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        echo "<a href=\"index.php?type=login&redirect={$link}\" class=\"menu-item\">登入</a>";
                        echo "<a href=\"index.php?type=register\" class=\"menu-item\">註冊</a>";
                    }
                ?>
        </div>
    </div>
</span>
<?php

