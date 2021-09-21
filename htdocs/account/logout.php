<?php
require_once __DIR__."/../mysql/acmanager.php";
logout();
echo "您已成功登出..";
header("refresh:2;url=../index.php");