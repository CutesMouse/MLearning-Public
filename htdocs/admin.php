<?php
require_once "mysql/acmanager.php";
require_once "mysql/db.php";
$user = getCurrentAcc($mysql);
if ($user["perm"] !== "admin") {
    echo "沒有Admin權限!";
    return;
}
$admin = true;
require_once "content/adminMenu.php";